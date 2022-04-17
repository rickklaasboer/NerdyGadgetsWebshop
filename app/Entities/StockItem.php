<?php

namespace App\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use JsonSerializable;

/**
 * @Entity
 * @Table(name="stockitems")
 */
class StockItem extends Entity implements JsonSerializable
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $StockItemID;

    /**
     * @Column(length=255)
     */
    protected $StockItemName;

    /**
     * @Column(type="integer")
     */
    protected $SupplierID;

    /**
     * @Column(type="integer")
     */
    protected $ColorID;

    /**
     * @Column(type="integer")
     */
    protected $UnitPackageID;

    /**
     * @Column(type="integer")
     */
    protected $OuterPackageID;

    /**
     * @Column(length=50)
     */
    protected $Brand;

    /**
     * @Column(length=50)
     */
    protected $Size;

    /**
     * @Column(type="integer")
     */
    protected $LeadTimeDays;

    /**
     * @Column(type="integer")
     */
    protected $QuantityPerOuter;

    /**
     * @Column(type="boolean")
     */
    protected $IsChillerStock;

    /**
     * @Column(length=50)
     */
    protected $Barcode;

    /**
     * @Column(type="decimal")
     */
    protected $TaxRate;

    /**
     * @Column(type="decimal")
     */
    protected $UnitPrice;

    /**
     * @Column(type="decimal")
     */
    protected $RecommendedRetailPrice;

    /**
     * @Column(type="decimal")
     */
    protected $TypicalWeightPerUnit;

    /**
     * @Column(type="text")
     */
    protected $MarketingComments;

    /**
     * @Column(type="text")
     */
    protected $InternalComments;

    /**
     * @Column(type="text")
     */
    protected $CustomFields;

    /**
     * @Column(type="text")
     */
    protected $Tags;

    /**
     * @Column(type="text")
     */
    protected $SearchDetails;

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
    protected $Video;

    /**
     * One StockItem has many StockItemImages
     * @OneToMany(targetEntity="StockItemImage", mappedBy="StockItem")
     */
    protected $Images;

    /**
     * Many StockItems have Many StockGroups.
     * @ManyToMany(targetEntity="StockGroup", inversedBy="StockItems")
     * @JoinTable(
     *     name="stockitemstockgroups",
     *     joinColumns={@JoinColumn(name="StockItemID", referencedColumnName="StockItemID")},
     *     inverseJoinColumns={@JoinColumn(name="StockGroupID", referencedColumnName="StockGroupID")}
     *     )
     */
    protected $StockGroups;

    /**
     * One StockItem has one StockItemHolding
     * @OneToOne(targetEntity="StockItemHolding", mappedBy="StockItem")
     */
    protected $StockItemHolding;

    /**
     * One StockItem has many Ratings
     * @OneToMany(targetEntity="Rating", mappedBy="StockItem")
     */
    protected $Ratings;

    public function __construct()
    {
        $this->Images = new ArrayCollection();
        $this->StockGroups = new ArrayCollection();
        $this->Ratings = new ArrayCollection();
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
     * @return mixed
     */
    public function getRatings()
    {
        return $this->Ratings;
    }

    /**
     * @param mixed $Ratings
     */
    public function setRatings($Ratings): void
    {
        $this->Ratings = $Ratings;
    }

    /**
     * Json serialize
     *
     * @return mixed
     */
    public function jsonSerialize(): mixed
    {

        return array_merge(
            get_object_vars($this),
            ['ImagePath' => $this->getImages()->first()?->getImagePath()]
        );
    }
}