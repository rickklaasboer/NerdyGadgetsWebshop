<?php

namespace App\Entities;

use App\Traits\ToJson;
use JsonSerializable;

/**
 * @Entity
 * @Table(name="stockitemimages")
 */
class StockItemImage extends Entity implements JsonSerializable
{
    /**
     * @Id
     * @Column(type="integer")
     */
    protected $StockItemID;

    /**
     * @Id
     * @Column(length=255)
     */
    protected $ImagePath;

    /**
     * @ManyToOne(targetEntity="StockItem", inversedBy="Images")
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
    public function getImagePath()
    {
        return $this->ImagePath;
    }

    /**
     * @param mixed $ImagePath
     */
    public function setImagePath($ImagePath): void
    {
        $this->ImagePath = $ImagePath;
    }

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


    public function jsonSerialize(): mixed
    {
        return [
            'StockItemID' => $this->StockItemID,
            'ImagePath' => $this->ImagePath,
        ];
    }
}