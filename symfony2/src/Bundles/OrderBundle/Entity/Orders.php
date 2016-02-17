<?php

namespace Bundles\OrderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Orders
 * @ORM\Entity(repositoryClass="Bundles\OrderBundle\Entity\Repository\OrdersRepository")
 * @ORM\Table(name="orders")
 * @ORM\HasLifecycleCallbacks
 */
class Orders
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
     * @ORM\OneToMany(targetEntity="OrderItems", mappedBy="orders")
     */
    protected $order_items;

    /**
     * @ORM\OneToMany(targetEntity="Payment", mappedBy="orders")
     */
    protected $payment;
    
    /**
     * @ORM\Column(type="string", length=350 )
     */

    protected $shipping_details;

    /**
     * @ORM\ManyToOne(targetEntity="Bundles\UserBundle\Entity\Customer", inversedBy="orders")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    protected $customer;
    
    /**
     * @ORM\Column(type="decimal", scale=2)
     */
    protected $amount;

    /**
     * @ORM\Column(type="datetime", nullable=false )
     */
    protected $created_on;
    
    public function __construct()
    {
        $this->created_on = new \DateTime();
    }
    
    public function setOrderItems($orderItems)
    {
    	$this->order_items[] = $orderItems;
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
     * Set customerId
     *
     * @param integer $customerId
     *
     * @return Orders
     */
    public function setCustomerId($customerId)
    {
        $this->customer_id = $customerId;

        return $this;
    }

    /**
     * Get customerId
     *
     * @return integer
     */
    public function getCustomerId()
    {
        return $this->customer_id;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     *
     * @return Orders
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return integer
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set createdOn
     *
     * @param \DateTime $createdOn
     *
     * @return Orders
     */
    public function setCreatedOn(\DateTime $createdOn)
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
     * Set customer
     *
     * @param \Bundles\OrderBundle\Entity\Customer $customer
     *
     * @return Orders
     */
    public function setCustomer(\Bundles\UserBundle\Entity\Customer $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return \Bundles\OrderBundle\Entity\Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Set user
     *
     * @param \Bundles\UserBundle\Entity\Users $users
     *
     * @return Orders
     */
    public function setUser(\Bundles\UserBundle\Entity\Users $users = null)
    {
        $this->users = $users;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Bundles\UserBundle\Entity\Users
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Set shippingDetails
     *
     * @param string $shippingDetails
     *
     * @return Orders
     */
    public function setShippingDetails($shippingDetails)
    {
        $this->shipping_details = $shippingDetails;

        return $this;
    }

    /**
     * Get shippingDetails
     *
     * @return string
     */
    public function getShippingDetails()
    {
        return $this->shipping_details;
    }

    /**
     * Add orderItem
     *
     * @param \Bundles\OrderBundle\Entity\OrderItems $orderItem
     *
     * @return Orders
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

    /**
     * Add payment
     *
     * @param \Bundles\OrderBundle\Entity\Payment $payment
     *
     * @return Orders
     */
    public function addPayment(\Bundles\OrderBundle\Entity\Payment $payment)
    {
        $this->payment[] = $payment;

        return $this;
    }

    /**
     * Remove payment
     *
     * @param \Bundles\OrderBundle\Entity\Payment $payment
     */
    public function removePayment(\Bundles\OrderBundle\Entity\Payment $payment)
    {
        $this->payment->removeElement($payment);
    }

    /**
     * Get payment
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPayment()
    {
        return $this->payment;
    }
}
