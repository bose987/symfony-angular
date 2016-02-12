<?php

namespace Bundles\OrderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Payment
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Payment
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
     * @ORM\ManyToOne(targetEntity="Orders", inversedBy="payment")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id")
     */
    protected $orders;

    /**
     * @ORM\ManyToOne(targetEntity="Bundles\UserBundle\Entity\Customer", inversedBy="payment")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    protected $customer;

    /**
     * @ORM\Column(type="string", length=50 )
     */
    protected $transaction_id;
    
    /**
     * @ORM\Column(type="boolean")
     */
    protected $is_success;

    /**
     * @ORM\Column(type="string", length=50 )
     */
    protected $payment_status;

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
     * @return Payment
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
     * Set customerId
     *
     * @param integer $customerId
     *
     * @return Payment
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
     * Set transactionId
     *
     * @param integer $transactionId
     *
     * @return Payment
     */
    public function setTransactionId($transactionId)
    {
        $this->transaction_id = $transactionId;

        return $this;
    }

    /**
     * Get transactionId
     *
     * @return integer
     */
    public function getTransactionId()
    {
        return $this->transaction_id;
    }

    /**
     * Set isSuccess
     *
     * @param boolean $isSuccess
     *
     * @return Payment
     */
    public function setIsSuccess($isSuccess)
    {
        $this->is_success = $isSuccess;

        return $this;
    }

    /**
     * Get isSuccess
     *
     * @return boolean
     */
    public function getIsSuccess()
    {
        return $this->is_success;
    }

    /**
     * Set paymentStatus
     *
     * @param string $paymentStatus
     *
     * @return Payment
     */
    public function setPaymentStatus($paymentStatus)
    {
        $this->payment_status = $paymentStatus;

        return $this;
    }

    /**
     * Get paymentStatus
     *
     * @return string
     */
    public function getPaymentStatus()
    {
        return $this->payment_status;
    }

    /**
     * Set createdOn
     *
     * @param \timestamp $createdOn
     *
     * @return Payment
     */
    public function setCreatedOn(\timestamp $createdOn)
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
     * @return Payment
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
     * Set customer
     *
     * @param \Bundles\UserBundle\Entity\Customer $customer
     *
     * @return Payment
     */
    public function setCustomer(\Bundles\UserBundle\Entity\Customer $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return \Bundles\UserBundle\Entity\Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Set orders
     *
     * @param \Bundles\OrderBundle\Entity\Orders $orders
     *
     * @return Payment
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
