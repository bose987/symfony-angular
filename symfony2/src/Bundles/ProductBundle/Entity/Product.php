<?php

namespace Bundles\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\Entity(repositoryClass="Bundles\ProductBundle\Entity\Repository\ProductRepository")
 * @ORM\Table(name="product")
 * @ORM\HasLifecycleCallbacks
 */
class Product
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
     * @ORM\Column(type="integer",  nullable=true )
     */
    protected $category_id;

    /**
     * @ORM\Column(type="string", length=200 )
     */
    protected $name;
    
    /**
     * @ORM\Column(type="string", length=200 )
     */
    protected $product_number;
    
    /**
     * @ORM\Column(type="integer", length=200 )
     */
    protected $list_price;
    
    /**
     * @ORM\Column(type="string", length=30, nullable=true )
     */
    protected $size;
    
    /**
     * @ORM\Column(type="string", length=200 )
     */
    protected $description;
    
    /**
     * @ORM\Column(type="string", length=100 )
     */
    protected $color;
    
    /**
     * @ORM\Column(type="string", length=200 )
     */
    protected $link;
    
    /**
     * @ORM\Column(type="integer", nullable=true )
     */
    protected $quantity_available;
    
    /**
     * @ORM\Column(type="integer", nullable=true )
     */
    protected $discount;

    /**
     * @ORM\OneToMany(targetEntity="Bundles\OrderBundle\Entity\OrderItems", mappedBy="product")
     */
    protected $order_items;

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
     * Set color
     *
     * @param string $color
     *
     * @return Product
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set link
     *
     * @param string $link
     *
     * @return Product
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set quantityAvailable
     *
     * @param integer $quantityAvailable
     *
     * @return Product
     */
    public function setQuantityAvailable($quantityAvailable)
    {
        $this->quantity_available = $quantityAvailable;

        return $this;
    }

    /**
     * Get quantityAvailable
     *
     * @return integer
     */
    public function getQuantityAvailable()
    {
        return $this->quantity_available;
    }

    /**
     * Set discount
     *
     * @param integer $discount
     *
     * @return Product
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * Get discount
     *
     * @return integer
     */
    public function getDiscount()
    {
        return $this->discount;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->order_items = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add orderItem
     *
     * @param \Bundles\OrderBundle\Entity\OrderItems $orderItem
     *
     * @return Product
     */
    public function addOrderItem(\Bundles\OrderBundle\Entity\OrderItems $orderItem)
    {
        $this->order_items[] = $orderItem;

        return $this;
    }

    /**
     * Remove orderItem
     *
     * @param \Bundles\OrderBundle\Entity\OrderItems $orderItem
     */
    public function removeOrderItem(\Bundles\OrderBundle\Entity\OrderItems $orderItem)
    {
        $this->order_items->removeElement($orderItem);
    }

    /**
     * Get orderItems
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrderItems()
    {
        return $this->order_items;
    }
}
