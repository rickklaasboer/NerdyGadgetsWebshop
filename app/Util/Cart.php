<?php

namespace App\Util;

use App\Entities\StockItem;
use Doctrine\ORM\EntityManager;
use Exception;
use PDO;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Cart
 *
 * @package App\Util
 * @author Rick Klaasboer <rick@klaasboer.org>
 */
class Cart
{
    /**
     * Add the given amount to the existing amount
     *
     * @var string
     */
    const MODIFY_MODE_ADD = 'add';

    /**
     * Set the given amount and will forget the existing amount
     *
     * @var string
     */
    const MODIFY_MODE_SET = 'set';

    /**
     * Query for testing if a product exists in the database
     *
     * @var string
     */
    const PRODUCT_EXISTS_QUERY = "SELECT StockItemID FROM stockitems WHERE StockItemID = ?";

    /**
     * Query for testing if a product is in stock
     *
     * @var string
     */
    const PRODUCT_IN_STOCK_QUERY = "SELECT s.StockItemID, sh.QuantityOnHand FROM stockitems s JOIN stockitemholdings sh ON sh.StockItemID = s.StockItemID WHERE s.StockItemID = ?";

    /**
     * Items in cart
     *
     * @var array
     */
    protected array $items = [];

    /**
     * Discount applied to cart
     *
     * @var float|int
     */
    protected float $discount = 0;

    /**
     * The database connection
     *
     * @var mixed|PDO
     */
    protected EntityManager $manager;

    /**
     * Cart constructor.
     */
    public function __construct(Request $request, EntityManager $manager)
    {
        start_session_if_not_exists();

        $this->manager = $manager;

        if ($request->getSession()->get('discount')) {
            $this->setDiscount($request->getSession()->get('discount'));
        }

        $this->reverseSync();
    }

    /**
     * Sync session with cart
     */
    public function sync()
    {
        $_SESSION['cart'] = $this->items;
    }

    /**
     * Sync cart with session
     */
    public function reverseSync()
    {
        // Only sync cart if cart has been set in session
        if (isset($_SESSION['cart'])) {
            $this->items = $_SESSION['cart'];
        }
    }

    /**
     * Add a product to cart
     *
     * @param $productId
     * @param int $amount
     * @return array
     */
    public function add($productId, $amount = 1)
    {
        // If the id/key is already in cart
        // forward call to modify()
        if (isset($this->items[$productId])) {
            return $this->modify($productId, $amount);
        }

        $this->items[$productId] = [
            'product_id' => $productId,
            'amount' => $amount
        ];

        $this->sync();

        return $this->all();
    }

    /**
     * Modify an existing item in cart
     *
     * @param $productId
     * @param null $amount
     * @param string $mode
     * @return array
     */
    public function modify($productId, $amount = null, $mode = self::MODIFY_MODE_ADD)
    {
        // Check if id/key has been set
        if (isset($this->items[$productId])) {

            // Get the amount of specific product
            $amountInCart = $this->itemCount($productId);

            // Update item in cart
            $this->items[$productId] = [
                'product_id' => $productId,
                'amount' => $mode === self::MODIFY_MODE_ADD ? $amountInCart + $amount : $amount,
            ];
        }

        $this->sync();

        return $this->all();
    }

    /**
     * Remove a product from cart
     *
     * @param $productId
     * @return array
     */
    public function remove($productId)
    {
        // Remove item from cart if set
        if (isset($this->items[$productId])) {
            unset($this->items[$productId]);
        }

        $this->sync();

        return $this->all();
    }

    /**
     * Test if a product exists in the database
     *
     * @param $productId
     * @return bool
     */
    public function exists($productId): bool
    {
        try {
            $response = $this->manager->find('App\Entities\StockItem', $productId);
            return boolval($response);
        } catch (Exception) {
            return false;
        }
    }

    /**
     * Test if product has item (with given amount) in stock
     *
     * @param $productId
     * @param int $amount
     * @return bool
     */
    public function hasAmountInStock($productId, $amount = 1)
    {
        return $this->manager->find(StockItem::class, $productId)?->getStockItemHolding()?->getQuantityOnHand() >= $amount;
    }

    /**
     * Empty cart
     *
     * @return bool
     */
    public function clear()
    {
        $this->items = [];

        $this->sync();

        return true;
    }

    /**
     * Get prices of cart
     *
     * @param array $products
     * @param float $vat
     * @return array
     */
    public function price(array $products, $vat = 0.21)
    {
        $price = 0;

        foreach ($this->items as $item) {
            // Find product in cart by id
            // Then, run array_values over it to reset keys
            $filtered = array_values(array_filter($products, function ($product) use ($item) {
                return $product->getStockItemID() === (int)$item['product_id'];
            }));

            // If a product was found, get it's price and process it
            if (isset($filtered[0])) {
                $price += $item['amount'] * (float)$filtered[0]->getRecommendedRetailPrice();
            }
        }

        if ($this->hasDiscount()) {
            $discount = $price / 100 * $this->discount;
            $price_with_discount = $price - $discount;
            $priceTotal = $this->priceWithTax($price_with_discount, $vat);
            $difference = $priceTotal - $price_with_discount;
        } else {
            $priceTotal = $this->priceWithTax($price, $vat);
            $difference = $priceTotal - $price;
        }

        return [
            'price' => $this->formatPrice($price),
            'price_total' => $this->formatPrice($priceTotal),
            'difference' => $this->formatPrice($difference),
            'discount' => $this->formatPrice($discount ?? 1),
        ];
    }

    /**
     * Return all items in cart
     *
     * @return array
     */
    public function all()
    {
        return $this->items;
    }

    /**
     * Get count of items in cart
     *
     * @return int
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * Get the count of items in cart, taking item amount into account
     * or get the count of 1 specific product
     *
     * @param null $productId
     * @return int|mixed
     */
    public function itemCount($productId = null)
    {
        $sum = 0;

        if (is_null($productId)) {
            foreach ($this->items as $item) {
                $sum += $item['amount'];
            }
        } else {
            $sum = $this->items[$productId]['amount'];
        }

        return $sum;
    }

    /**
     * Get all product ids
     *
     * @return array
     */
    public function getIds()
    {
        $collection = [];

        foreach ($this->items as $item) {
            $collection[] = $item['product_id'];
        }

        return $collection;
    }

    /**
     * Apply a discount to cart
     *
     * @param $percentage
     */
    public function setDiscount($percentage)
    {
        $this->discount = $percentage;
    }

    /**
     * Determine if cart has a set discount
     *
     * @return bool
     */
    public function hasDiscount()
    {
        return $this->discount > 0;
    }

    /**
     * Get discount percentage
     *
     * @return float|int
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Determine if cart has items
     *
     * @return bool
     */
    public function hasItems()
    {
        return $this->count() > 0;
    }

    /**
     * Determine if cart is empty
     *
     * @return bool
     */
    public function isEmpty()
    {
        return !$this->hasItems();
    }

    /**
     * Get price with tax
     *
     * @param $price
     * @param float $taxRate
     * @return float|int
     */
    protected function priceWithTax($price, $taxRate = 0.21)
    {
        return $price * (1 + 0.21);
    }

    /**
     * Format price in Dutch format (€ 9.999,99)
     * because Dutch is the only right format
     *
     * @param $number
     * @return string
     */
    protected function formatPrice($number)
    {
        return number_format(round($number, 2), 2, ',', '.');
    }
}