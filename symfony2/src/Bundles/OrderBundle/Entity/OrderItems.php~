<?php

namespace Bundles\OrderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrderItems
 * @ORM\Entity(repositoryClass="Bundles\OrderBundle\Entity\Repository\OrderItemsRepository")
 * @ORM\Table(name="order_items")
 * @ORM\HasLifecycleCallbacks
 */
class OrderItems
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
     * @ORM\ManyToOne(targetEntity="Orders", inversedBy="order_items")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id")
     */
    protected $orders;
    
    /**
     * @ORM\Column(type="integer", nullable=false )
     */
    protected $quantity;

    /**
     * @ORM\Column(type="integer", length=200 )
     */
    protected $price;

    /**
     * @ORM\Column(type="integer", length=200 )
     */
    protected $discount;

    /**
     * @ORM\ManyToOne(targetEntity="Bundles\ProductBundle\Entity\Product", inversedBy="order_items")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    protected $product;

    /**
     * @ORM\Column(type="integer")
     */
    protected $product_id;

    /**
     * @ORM\Column(type="datetime", nullable=false )
     */
    protected $created_on;

    public function __construct()
    {
        $this->created_on = new \DateTime();
    }

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
     * Set orderId
     *
     * @param integer $orderId
     *
     * @return OrderItems
     */
    public function setOrderId($orderId)
    {
        $this->order_id = $orderId;

        return $this;
    }

    /**
     * Get orderId
     *
     * @return integer
     */
    public function getOrderId()
    {
        return $this->order_id;
    }

    /**
     * Set productId
     *
     * @param integer $productId
     *
     * @return OrderItems
     */
    public function setProductId($productId)
    {
        $this->product_id = $productId;

        return $this;
    }

    /**
     * Get productId
     *
     * @return integer
     */
    public function getProductId()
    {
        return $this->product_id;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return OrderItems
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set price
     *
     * @param integer $price
     *
     * @return OrderItems
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return integer
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set discount
     *
     * @param integer $discount
     *
     * @return OrderItems
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
     * Set createdOn
     *
     * @param \timestamp $createdOn
     *
     * @return OrderItems
     */
    public function setCreatedOn( \timestamp $createdOn)
    {
        $this->created_on = $createdOn;

        return $this;
    }

    /**
     * Get createdOn
     *
     * @return \timestamp
     */
    public function getCreatedOn()
    {
        return $this->created_on;
    }

    /**
     * Set order
     *
     * @param \Bundles\OrderBundle\Entity\Orders $order
     *
     * @return OrderItems
     */
    public function setOrder(\Bundles\OrderBundle\Entity\Orders $order = null)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return \Bundles\OrderBundle\Entity\Orders
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set product
     *
     * @param \Bundles\ProductBundle\Entity\Product $product
     *
     * @return OrderItems
     */
    public function setProduct(\Bundles\ProductBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \Bundles\ProductBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set orders
     *
     * @param \Bundles\OrderBundle\Entity\Orders $orders
     *
     * @return OrderItems
     */
    public function setOrders(\Bundles\OrderBundle\Entity\Orders $orders = null)
    {
        $this->orders = $orders;

        return $this;
    }

    /**
     * Get orders
     *
     * @return \Bundles\OrderBundle\Entity\Orders
     */
    public function getOrders()
    {
        return $this->orders;
    }
}
