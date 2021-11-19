<?php

namespace App\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use JsonSerializable;

/**
 * @Entity
 * @Table(name="stockitems")
 */
class StockItem implements JsonSerializable
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    private $StockItemID;

    /**
     * @Column(length=255)
     */
    private $StockItemName;

    /**
     * @Column(type="integer")
     */
    private $SupplierID;

    /**
     * @Column(type="integer")
     */
    private $ColorID;

    /**
     * @Column(type="integer")
     */
    private $UnitPackageID;

    /**
     * @Column(type="integer")
     */
    private $OuterPackageID;

    /**
     * @Column(length=50)
     */
    private $Brand;

    /**
     * @Column(length=50)
     */
    private $Size;

    /**
     * @Column(type="integer")
     */
    private $LeadTimeDays;

    /**
     * @Column(type="integer")
     */
    private $QuantityPerOuter;

    /**
     * @Column(type="boolean")
     */
    private $IsChillerStock;

    /**
     * @Column(length=50)
     */
    private $Barcode;

    /**
     * @Column(type="decimal")
     */
    private $TaxRate;

    /**
     * @Column(type="decimal")
     */
    private $UnitPrice;

    /**
     * @Column(type="decimal")
     */
    private $RecommendedRetailPrice;

    /**
     * @Column(type="decimal")
     */
    private $TypicalWeightPerUnit;

    /**
     * @Column(type="text")
     */
    private $MarketingComments;

    /**
     * @Column(type="text")
     */
    private $InternalComments;

    /**
     * @Column(type="text")
     */
    private $CustomFields;

    /**
     * @Column(type="text")
     */
    private $Tags;

    /**
     * @Column(type="text")
     */
    private $SearchDetails;

    /**
     * @Column(type="integer")
     */
    private $LastEditedBy;

    /**
     * @Column(type="datetime")
     */
    private $ValidFrom;

    /**
     * @Column(type="datetime")
     */
    private $ValidTo;

    /**
     * @return mixed
     */
    public function getStockItemHolding()
    {
        return $this->StockItemHolding;
    }

    /**
     * @param mixed $StockItemHolding
     */
    public function setStockItemHolding($StockItemHolding): void
    {
        $this->StockItemHolding = $StockItemHolding;
    }

    /**
     * @Column(length=255)
     */
    private $Video;

    /**
     * One StockItem has many StockItemImages
     * @OneToMany(targetEntity="StockItemImage", mappedBy="StockItem")
     */
    private $Images;

    /**
     * Many StockItems have Many StockGroups.
     * @ManyToMany(targetEntity="StockGroup", inversedBy="StockItems")
     * @JoinTable(
     *     name="stockitemstockgroups",
     *     joinColumns={@JoinColumn(name="StockItemID", referencedColumnName="StockItemID")},
     *     inverseJoinColumns={@JoinColumn(name="StockGroupID", referencedColumnName="StockGroupID")}
     *     )
     */
    private $StockGroups;

    /**
     * One StockItem has one StockItemHolding
     * @OneToOne(targetEntity="StockItemHolding", mappedBy="StockItem")
     */
    private $StockItemHolding;

    public function __construct()
    {
        $this->Images = new ArrayCollection();
        $this->StockGroups = new ArrayCollection();
    }

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
    public function getStockItemName()
    {
        return $this->StockItemName;
    }

    /**
     * @param mixed $StockItemName
     */
    public function setStockItemName($StockItemName): void
    {
        $this->StockItemName = $StockItemName;
    }

    /**
     * @return mixed
     */
    public function getSupplierID()
    {
        return $this->SupplierID;
    }

    /**
     * @param mixed $SupplierID
     */
    public function setSupplierID($SupplierID): void
    {
        $this->SupplierID = $SupplierID;
    }

    /**
     * @return mixed
     */
    public function getColorID()
    {
        return $this->ColorID;
    }

    /**
     * @param mixed $ColorID
     */
    public function setColorID($ColorID): void
    {
        $this->ColorID = $ColorID;
    }

    /**
     * @return mixed
     */
    public function getUnitPackageID()
    {
        return $this->UnitPackageID;
    }

    /**
     * @param mixed $UnitPackageID
     */
    public function setUnitPackageID($UnitPackageID): void
    {
        $this->UnitPackageID = $UnitPackageID;
    }

    /**
     * @return mixed
     */
    public function getOuterPackageID()
    {
        return $this->OuterPackageID;
    }

    /**
     * @param mixed $OuterPackageID
     */
    public function setOuterPackageID($OuterPackageID): void
    {
        $this->OuterPackageID = $OuterPackageID;
    }

    /**
     * @return mixed
     */
    public function getBrand()
    {
        return $this->Brand;
    }

    /**
     * @param mixed $Brand
     */
    public function setBrand($Brand): void
    {
        $this->Brand = $Brand;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->Size;
    }

    /**
     * @param mixed $Size
     */
    public function setSize($Size): void
    {
        $this->Size = $Size;
    }

    /**
     * @return mixed
     */
    public function getLeadTimeDays()
    {
        return $this->LeadTimeDays;
    }

    /**
     * @param mixed $LeadTimeDays
     */
    public function setLeadTimeDays($LeadTimeDays): void
    {
        $this->LeadTimeDays = $LeadTimeDays;
    }

    /**
     * @return mixed
     */
    public function getQuantityPerOuter()
    {
        return $this->QuantityPerOuter;
    }

    /**
     * @param mixed $QuantityPerOuter
     */
    public function setQuantityPerOuter($QuantityPerOuter): void
    {
        $this->QuantityPerOuter = $QuantityPerOuter;
    }

    /**
     * @return mixed
     */
    public function getIsChillerStock()
    {
        return $this->IsChillerStock;
    }

    /**
     * @param mixed $IsChillerStock
     */
    public function setIsChillerStock($IsChillerStock): void
    {
        $this->IsChillerStock = $IsChillerStock;
    }

    /**
     * @return mixed
     */
    public function getBarcode()
    {
        return $this->Barcode;
    }

    /**
     * @param mixed $Barcode
     */
    public function setBarcode($Barcode): void
    {
        $this->Barcode = $Barcode;
    }

    /**
     * @return mixed
     */
    public function getTaxRate()
    {
        return $this->TaxRate;
    }

    /**
     * @param mixed $TaxRate
     */
    public function setTaxRate($TaxRate): void
    {
        $this->TaxRate = $TaxRate;
    }

    /**
     * @return mixed
     */
    public function getUnitPrice()
    {
        return $this->UnitPrice;
    }

    /**
     * @param mixed $UnitPrice
     */
    public function setUnitPrice($UnitPrice): void
    {
        $this->UnitPrice = $UnitPrice;
    }

    /**
     * @return mixed
     */
    public function getRecommendedRetailPrice()
    {
        return $this->RecommendedRetailPrice;
    }

    /**
     * @param mixed $RecommendedRetailPrice
     */
    public function setRecommendedRetailPrice($RecommendedRetailPrice): void
    {
        $this->RecommendedRetailPrice = $RecommendedRetailPrice;
    }

    /**
     * @return mixed
     */
    public function getTypicalWeightPerUnit()
    {
        return $this->TypicalWeightPerUnit;
    }

    /**
     * @param mixed $TypicalWeightPerUnit
     */
    public function setTypicalWeightPerUnit($TypicalWeightPerUnit): void
    {
        $this->TypicalWeightPerUnit = $TypicalWeightPerUnit;
    }

    /**
     * @return mixed
     */
    public function getMarketingComments()
    {
        return $this->MarketingComments;
    }

    /**
     * @param mixed $MarketingComments
     */
    public function setMarketingComments($MarketingComments): void
    {
        $this->MarketingComments = $MarketingComments;
    }

    /**
     * @return mixed
     */
    public function getInternalComments()
    {
        return $this->InternalComments;
    }

    /**
     * @param mixed $InternalComments
     */
    public function setInternalComments($InternalComments): void
    {
        $this->InternalComments = $InternalComments;
    }

    /**
     * @return mixed
     */
    public function getCustomFields()
    {
        return json_decode($this->CustomFields, true);
    }

    /**
     * @param mixed $CustomFields
     */
    public function setCustomFields($CustomFields): void
    {
        $this->CustomFields = $CustomFields;
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return json_decode($this->Tags, true);
    }

    /**
     * @param mixed $Tags
     */
    public function setTags($Tags): void
    {
        $this->Tags = $Tags;
    }

    /**
     * @return mixed
     */
    public function getSearchDetails()
    {
        return $this->SearchDetails;
    }

    /**
     * @param mixed $SearchDetails
     */
    public function setSearchDetails($SearchDetails): void
    {
        $this->SearchDetails = $SearchDetails;
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
    public function getVideo()
    {
        return $this->Video;
    }

    /**
     * @param mixed $Video
     */
    public function setVideo($Video): void
    {
        $this->Video = $Video;
    }

    /**
     * @return ArrayCollection
     */
    public function getImages()
    {
        return $this->Images;
    }

    /**
     * @param ArrayCollection $Images
     */
    public function setImages($Images)
    {
        $this->Images = $Images;
    }

    /**
     * @return ArrayCollection
     */
    public function getStockGroups()
    {
        return $this->StockGroups;
    }

    /**
     * @param ArrayCollection $StockGroups
     */
    public function setStockGroups($StockGroups)
    {
        $this->StockGroups = $StockGroups;
    }

    /**
     * Json serialize
     *
     * @return array
     */
    public function jsonSerialize()
    {

        return array_merge(
            get_object_vars($this),
            ['ImagePath' => $this->getImages()->first()?->getImagePath()]
        );
    }
}