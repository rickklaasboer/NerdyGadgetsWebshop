<?php

namespace App\Entities;

use JsonSerializable;

/**
 * @Entity
 * @Table(name="stockitemholdings")
 */
class StockItemHolding extends Entity implements JsonSerializable
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $StockItemID;

    /**
     * @Column(type="bigint")
     */
    protected $QuantityOnHand;

    /**
     * @Column(length="255")
     */
    protected $BinLocation;

    /**
     * @Column(type="bigint")
     */
    protected $LastStocktakeQuantity;

    /**
     * @Column(type="decimal")
     */
    protected $LastCostPrice;

    /**
     * @Column(type="integer")
     */
    protected $ReorderLevel;

    /**
     * @Column(type="bigint")
     */
    protected $TargetStockLevel;

    /**
     * @Column(type="integer")
     */
    protected $LastEditedBy;

    /**
     * @Column(type="datetime")
     */
    protected $LastEditedWhen;

    /**
     * @return mixed
     */
    public function getStockItem()
    {
        return $this->StockItem;
    }

    /**
     * @param mixed $StockItem
     */
    public function setStockItem($StockItem): void
    {
        $this->StockItem = $StockItem;
    }

    /**
     * One StockItemHolding has one StockItem
     * @OneToOne(targetEntity="StockItem", mappedBy="StockItemHolding")
     * @JoinColumn(name="StockItemID", referencedColumnName="StockItemID")
     */
    protected $StockItem;

    /**
     * @return mixed
     */
    public function getStockItemID()
    {
        return $this->StockItemID;
    }

    /**
     * @param mixed $StockItemID
     */
    public function setStockItemID($StockItemID): void
    {
        $this->StockItemID = $StockItemID;
    }

    /**
     * @return mixed
     */
    public function getQuantityOnHand()
    {
        return $this->QuantityOnHand;
    }

    /**
     * @param mixed $QuantityOnHand
     */
    public function setQuantityOnHand($QuantityOnHand): void
    {
        $this->QuantityOnHand = $QuantityOnHand;
    }

    /**
     * @return mixed
     */
    public function getBinLocation()
    {
        return $this->BinLocation;
    }

    /**
     * @param mixed $BinLocation
     */
    public function setBinLocation($BinLocation): void
    {
        $this->BinLocation = $BinLocation;
    }

    /**
     * @return mixed
     */
    public function getLastStocktakeQuantity()
    {
        return $this->LastStocktakeQuantity;
    }

    /**
     * @param mixed $LastStocktakeQuantity
     */
    public function setLastStocktakeQuantity($LastStocktakeQuantity): void
    {
        $this->LastStocktakeQuantity = $LastStocktakeQuantity;
    }

    /**
     * @return mixed
     */
    public function getLastCostPrice()
    {
        return $this->LastCostPrice;
    }

    /**
     * @param mixed $LastCostPrice
     */
    public function setLastCostPrice($LastCostPrice): void
    {
        $this->LastCostPrice = $LastCostPrice;
    }

    /**
     * @return mixed
     */
    public function getReorderLevel()
    {
        return $this->ReorderLevel;
    }

    /**
     * @param mixed $ReorderLevel
     */
    public function setReorderLevel($ReorderLevel): void
    {
        $this->ReorderLevel = $ReorderLevel;
    }

    /**
     * @return mixed
     */
    public function getTargetStockLevel()
    {
        return $this->TargetStockLevel;
    }

    /**
     * @param mixed $TargetStockLevel
     */
    public function setTargetStockLevel($TargetStockLevel): void
    {
        $this->TargetStockLevel = $TargetStockLevel;
    }

    /**
     * @return mixed
     */
    public function getLastEditedBy()
    {
        return $this->LastEditedBy;
    }

    /**
     * @param mixed $LastEditedBy
     */
    public function setLastEditedBy($LastEditedBy): void
    {
        $this->LastEditedBy = $LastEditedBy;
    }

    /**
     * @return mixed
     */
    public function getLastEditedWhen()
    {
        return $this->LastEditedWhen;
    }

    /**
     * @param mixed $LastEditedWhen
     */
    public function setLastEditedWhen($LastEditedWhen): void
    {
        $this->LastEditedWhen = $LastEditedWhen;
    }

    public function jsonSerialize()
    {
        return [
            'StockItemID' => $this->StockItemID,
            'QuantityOnHand' => $this->QuantityOnHand,
            'BinLocation' => $this->BinLocation,
            'LastStocktakeQuantity' => $this->LastStocktakeQuantity,
            'LastCostPrice' => $this->LastCostPrice,
            'ReorderLevel' => $this->ReorderLevel,
            'TargetStockLevel' => $this->TargetStockLevel,
            'LastEditedBy' => $this->LastEditedBy,
            'LastEditedWhen' => $this->LastEditedWhen,
        ];
    }
}