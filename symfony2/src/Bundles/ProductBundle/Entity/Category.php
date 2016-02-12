<?php

namespace Bundles\ProductBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\Entity(repositoryClass="Bundles\ProductBundle\Entity\Repository\CategoryRepository")
 * @ORM\Table(name="category")
 * @ORM\HasLifecycleCallbacks
 */
class Category
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true )
     */
    protected $category_id;

    /**
     * @ORM\Column(type="string", length=200 )
     */
    protected $name;
    
    /**
     * @ORM\Column(type="string", length=200 )
     */
    protected $description;
    
    /**
     * @ORM\Column(type="string", length=200, nullable=true )
     */
    protected $image_link;

    /**
     * @ORM\Column(type="string", length=200 )
     */
    protected $salesforce_id;

    /**
     * @ORM\Column(type="string", length=200, nullable=true )
     */
    protected $parent_salesforce_id;
    
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set categoryId
     *
     * @param integer $categoryId
     *
     * @return Product
     */
    public function setCategoryId($categoryId)
    {
        $this->category_id = $categoryId;

        return $this;
    }

    /**
     * Get categoryId
     *
     * @return integer
     */
    public function getCategoryId()
    {
        return $this->category_id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set productNumber
     *
     * @param string $productNumber
     *
     * @return Product
     */
    public function setProductNumber($productNumber)
    {
        $this->product_number = $productNumber;

        return $this;
    }

    /**
     * Get productNumber
     *
     * @return string
     */
    public function getProductNumber()
    {
        return $this->product_number;
    }

    /**
     * Set listPrice
     *
     * @param integer $listPrice
     *
     * @return Product
     */
    public function setListPrice($listPrice)
    {
        $this->list_price = $listPrice;

        return $this;
    }

    /**
     * Get listPrice
     *
     * @return integer
     */
    public function getListPrice()
    {
        return $this->list_price;
    }

    /**
     * Set size
     *
     * @param integer $size
     *
     * @return Product
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return integer
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set imageLink
     *
     * @param string $imageLink
     *
     * @return Category
     */
    public function setImageLink($imageLink)
    {
        $this->image_link = $imageLink;

        return $this;
    }

    /**
     * Get imageLink
     *
     * @return string
     */
    public function getImageLink()
    {
        return $this->image_link;
    }

    /**
     * Set salesforceId
     *
     * @param string $salesforceId
     *
     * @return Category
     */
    public function setSalesforceId($salesforceId)
    {
        $this->salesforce_id = $salesforceId;

        return $this;
    }

    /**
     * Get salesforceId
     *
     * @return string
     */
    public function getSalesforceId()
    {
        return $this->salesforce_id;
    }

    /**
     * Set parentSalesforceId
     *
     * @param string $parentSalesforceId
     *
     * @return Category
     */
    public function setParentSalesforceId($parentSalesforceId)
    {
        $this->parent_salesforce_id = $parentSalesforceId;

        return $this;
    }

    /**
     * Get parentSalesforceId
     *
     * @return string
     */
    public function getParentSalesforceId()
    {
        return $this->parent_salesforce_id;
    }
}
