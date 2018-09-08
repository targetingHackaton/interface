<?php

namespace AppBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(repositoryClass="\AppBundle\Document\ProductRepository")
 */
class Product
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @var int
     * @MongoDB\Field(type="integer", name="productId")
     * @MongoDB\Index(unique=true, order="asc")
     */
    protected $productId;

    /**
     * @var string
     * @MongoDB\Field(type="string")
     */
    protected $pnk;

    /**
     * @var string
     * @MongoDB\Field(type="string")
     */
    protected $imageUrl;

    /**
     * @var float
     * @MongoDB\Field(type="float")
     */
    protected $ratingScore;

    /**
     * @var string
     * @MongoDB\Field(type="integer")
     */
    protected $reviewsCount;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return $this
     */
    public function setId($id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getProductId(): int
    {
        return $this->productId;
    }

    /**
     * @param int $productId
     * @return $this
     */
    public function setProductId(int $productId): self
    {
        $this->productId = $productId;
        return $this;
    }

    /**
     * @return string
     */
    public function getPnk(): string
    {
        return $this->pnk;
    }

    /**
     * @param string $pnk
     * @return $this
     */
    public function setPnk(string $pnk): self
    {
        $this->pnk = $pnk;
        return $this;
    }

    /**
     * @return string
     */
    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    /**
     * @param string $imageUrl
     * @return $this
     */
    public function setImageUrl(string $imageUrl): self
    {
        $this->imageUrl = $imageUrl;
        return $this;
    }

    /**
     * @return float
     */
    public function getRatingScore(): float
    {
        return $this->ratingScore;
    }

    /**
     * @param float $ratingScore
     * @return $this
     */
    public function setRatingScore(float $ratingScore): self
    {
        $this->ratingScore = $ratingScore;
        return $this;
    }

    /**
     * @return string
     */
    public function getReviewsCount(): string
    {
        return $this->reviewsCount;
    }

    /**
     * @param string $reviewsCount
     * @return $this
     */
    public function setReviewsCount(string $reviewsCount): self
    {
        $this->reviewsCount = $reviewsCount;
        return $this;
    }
}
