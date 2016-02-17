<?php

namespace Bundles\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Bundles\UserBundle\Entity\Users;
use Bundles\OrderBundle\Entity\Orders;
use Bundles\OrderBundle\Entity\Payment;

/**
 * Customer
 * @ORM\Entity(repositoryClass="Bundles\UserBundle\Entity\Repository\CustomerRepository")
 * @ORM\Table(name="customer")
  */
class Customer
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
     * @ORM\OneToMany(targetEntity="Bundles\OrderBundle\Entity\Orders", mappedBy="customer")
     */
    protected $orders;

    /**
     * @ORM\OneToMany(targetEntity="Bundles\OrderBundle\Entity\Payment", mappedBy="customer")
     */
    protected $payment;
    
    /**
     * @ORM\OneToOne(targetEntity="Users", mappedBy="customer")
     */
    protected $users;
    
    /**
     * @ORM\Column(type="string", length=100, unique = true )
     */
    protected $email_address;
    
    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $first_name;
    
    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $last_name;
    
    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    protected $phone;
    
    /**
     * @ORM\Column(type="datetime")
     */
    protected $created_on;

    /**
     * Get id
     *
     * @return integer
     */
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->orders = new \Doctrine\Common\Collections\ArrayCollection();
        $this->payment = new \Doctrine\Common\Collections\ArrayCollection();
        $this->setCreatedOn( new \DateTime() );
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
     * Set emailAddress
     *
     * @param string $emailAddress
     *
     * @return Customer
     */
    public function setEmailAddress($emailAddress)
    {
        $this->email_address = $emailAddress;

        return $this;
    }

    /**
     * Get emailAddress
     *
     * @return string
     */
    public function getEmailAddress()
    {
        return $this->email_address;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Customer
     */
    public function setFirstName($firstName)
    {
        $this->first_name = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Customer
     */
    public function setLastName($lastName)
    {
        $this->last_name = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * Set phone
     *
     * @param integer $phone
     *
     * @return Customer
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return integer
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set createdOn
     *
     * @param \DateTime $createdOn
     *
     * @return Customer
     */
    public function setCreatedOn($createdOn)
    {
        $this->created_on = $createdOn;

        return $this;
    }

    /**
     * Get createdOn
     *
     * @return \DateTime
     */
    public function getCreatedOn()
    {
        return $this->created_on;
    }

    /**
     * Add order
     *
     * @param \Bundles\OrderBundle\Entity\Orders $order
     *
     * @return Customer
     */
    public function addOrder(\Bundles\OrderBundle\Entity\Orders $order)
    {
        $this->orders[] = $order;

        return $this;
    }

    /**
     * Remove order
     *
     * @param \Bundles\OrderBundle\Entity\Orders $order
     */
    public function removeOrder(\Bundles\OrderBundle\Entity\Orders $order)
    {
        $this->orders->removeElement($order);
    }

    /**
     * Get orders
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * Add payment
     *
     * @param \Bundles\OrderBundle\Entity\Payment $payment
     *
     * @return Customer
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

    /**
     * Set users
     *
     * @param \Bundles\UserBundle\Entity\Users $users
     *
     * @return Customer
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
}
