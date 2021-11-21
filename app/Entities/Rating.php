<?php

namespace App\Entities;

use JsonSerializable;

/**
 * @Entity
 * @Table(name="ratings")
 */
class Rating extends Entity implements JsonSerializable
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $ID;

    /**
     * @Column(type="integer")
     */
    protected $Rating;

    /**
     * @Column(type="bigint")
     */
    protected $UserID;

    /**
     * @Column(type="integer")
     */
    protected $StockItemID;

    /**
     * @Column(type="text")
     */
    protected $RatingText;

    /**
     * @Column(type="boolean")
     */
    protected $ShouldDisplay;

    /**
     * @Column(type="datetime")
     */
    protected $CreatedAt;

    /**
     * @Column(type="datetime")
     */
    protected $UpdatedAt;

    /**
     * Many Ratings have one StockItem
     * @ManyToOne(targetEntity="StockItem", inversedBy="Ratings")
     * @JoinColumn(name="StockItemID", referencedColumnName="StockItemID")
     */
    protected $StockItem;

    /**
     * Many Ratings have one User
     * @ManyToOne(targetEntity="User", inversedBy="Ratings")
     * @JoinColumn(name="UserID", referencedColumnName="id")
     */
    protected $User;

    /**
     * @return mixed
     */
    public function getID()
    {
        return $this->ID;
    }

    /**
     * @param mixed $ID
     */
    public function setID($ID): void
    {
        $this->ID = $ID;
    }

    /**
     * @return mixed
     */
    public function getRating()
    {
        return $this->Rating;
    }

    /**
     * @param mixed $Rating
     */
    public function setRating($Rating): void
    {
        $this->Rating = $Rating;
    }

    /**
     * @return mixed
     */
    public function getUserID()
    {
        return $this->UserID;
    }

    /**
     * @param mixed $UserID
     */
    public function setUserID($UserID): void
    {
        $this->UserID = $UserID;
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
    public function getRatingText()
    {
        return $this->RatingText;
    }

    /**
     * @param mixed $RatingText
     */
    public function setRatingText($RatingText): void
    {
        $this->RatingText = $RatingText;
    }

    /**
     * @return mixed
     */
    public function getShouldDisplay()
    {
        return $this->ShouldDisplay;
    }

    /**
     * @param mixed $ShouldDisplay
     */
    public function setShouldDisplay($ShouldDisplay): void
    {
        $this->ShouldDisplay = $ShouldDisplay;
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

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->User;
    }

    /**
     * @param mixed $User
     */
    public function setUser($User): void
    {
        $this->User = $User;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->CreatedAt;
    }

    /**
     * @param mixed $CreatedAt
     */
    public function setCreatedAt($CreatedAt): void
    {
        $this->CreatedAt = $CreatedAt;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->UpdatedAt;
    }

    /**
     * @param mixed $UpdatedAt
     */
    public function setUpdatedAt($UpdatedAt): void
    {
        $this->UpdatedAt = $UpdatedAt;
    }

    public function jsonSerialize()
    {
        return [
            'ID' => $this->ID,
            'Rating' => $this->Rating,
            'UserID' => $this->UserID,
            'StockItemID' => $this->StockItemID,
            'RatingText' => $this->RatingText,
            'ShouldDisplay' => $this->ShouldDisplay,
        ];
    }
}