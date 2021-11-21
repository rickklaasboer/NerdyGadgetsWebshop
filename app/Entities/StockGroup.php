<?php

namespace App\Entities;

use App\Traits\ToJson;
use Doctrine\Common\Collections\ArrayCollection;
use JsonSerializable;

/**
 * @Entity
 * @Table(name="stockgroups")
 */
class StockGroup extends Entity implements JsonSerializable
{
    use ToJson;
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $StockGroupID;

    /**
     * @Column(length=255)
     */
    protected $StockGroupName;

    /**
     * @Column(type="integer")
     */
    protected $LastEditedBy;

    /**
     * @Column(type="datetime")
     */
    protected $ValidFrom;

    /**
     * @Column(type="datetime")
     */
    protected $ValidTo;

    /**
     * @Column(length=255)
     */
    protected $ImagePath;

    /**
     * Many StockGroups have Many StockItems.
     * @ManyToMany(targetEntity="StockItem", inversedBy="StockGroups")
     * @JoinTable(name="stockitemstockgroups",
     *     joinColumns={@JoinColumn(name="StockGroupID", referencedColumnName="StockGroupID")},
     *     inverseJoinColumns={@JoinColumn(name="StockItemID", referencedColumnName="StockItemID")}
     * )
     */
    protected $StockItems;

    public function __construct()
    {
        $this->StockItems = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getStockGroupID()
    {
        return $this->StockGroupID;
    }

    /**
     * @param mixed $StockGroupID
     */
    public function setStockGroupID($StockGroupID): void
    {
        $this->StockGroupID = $StockGroupID;
    }

    /**
     * @return mixed
     */
    public function getStockGroupName()
    {
        return $this->StockGroupName;
    }

    /**
     * @param mixed $StockGroupName
     */
    public function setStockGroupName($StockGroupName): void
    {
        $this->StockGroupName = $StockGroupName;
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
    public function getValidFrom()
    {
        return $this->ValidFrom;
    }

    /**
     * @param mixed $ValidFrom
     */
    public function setValidFrom($ValidFrom): void
    {
        $this->ValidFrom = $ValidFrom;
    }

    /**
     * @return mixed
     */
    public function getValidTo()
    {
        return $this->ValidTo;
    }

    /**
     * @param mixed $ValidTo
     */
    public function setValidTo($ValidTo): void
    {
        $this->ValidTo = $ValidTo;
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
     * @return ArrayCollection
     */
    public function getStockItems()
    {
        return $this->StockItems;
    }

    /**
     * @param ArrayCollection $StockItems
     */
    public function setStockItems($StockItems)
    {
        $this->StockItems = $StockItems;
    }


}