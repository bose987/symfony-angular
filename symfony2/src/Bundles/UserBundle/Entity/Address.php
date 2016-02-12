<?php

namespace Bundles\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Address
 * @ORM\Entity(repositoryClass="Bundles\UserBundle\Entity\Repository\AddressRepository")
 * @ORM\Table(name="customer_address")
 */
class Address
{
	const TYPE_BILLING = 1;
	const TYPE_SHIPPING = 2;
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="type", type="integer", nullable=false, options={"default" = 1} )
     */
    protected $type;

    /**
     * @ORM\Column(name="first_name", type="string", length=255, nullable=false)
     */
    protected $firstname;
    
    /**
     * @ORM\Column(name="last_name", type="string", length=255, nullable=false)
     */
    protected $lastname;
    
    /**
     * @ORM\Column(name="cellphone", type="string", length=255, nullable=false)
     */
    protected $cellphone;
    
    /**
     * @ORM\Column(name="phone", type="string", length=255, nullable=false)
     */
    protected $phone;
    
    /**
     * @ORM\Column(name="address1", type="string", length=255, nullable=false)
     */
    protected $address1;
    
    /**
     * @ORM\Column(name="address2", type="string", length=255, nullable=true)
     */
    protected $address2;
    
    /**
     * @ORM\Column(name="address3", type="string", length=255, nullable=true)
     */
    protected $address3;
    
    /**
     * @ORM\Column(name="zipcode", type="string", length=255, nullable=false)
     */
    protected $zipcode;
    
    /**
     * @ORM\Column(name="city", type="string", length=255, nullable=false)
     */
    protected $city;
    
    /**
     * @ORM\Column(name="country", type="string", length=255, nullable=false)
     */
    protected $country;
    
    /**
     * @ORM\ManyToOne(targetEntity="Bundles\UserBundle\Entity\Customer")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    protected $customer;

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
     * Set firstname
     *
     * @param string $firstname
     *
     * @return Address
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return Address
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set cellphone
     *
     * @param string $cellphone
     *
     * @return Address
     */
    public function setCellphone($cellphone)
    {
        $this->cellphone = $cellphone;

        return $this;
    }

    /**
     * Get cellphone
     *
     * @return string
     */
    public function getCellphone()
    {
        return $this->cellphone;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Address
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set address1
     *
     * @param string $address1
     *
     * @return Address
     */
    public function setAddress1($address1)
    {
        $this->address1 = $address1;

        return $this;
    }

    /**
     * Get address1
     *
     * @return string
     */
    public function getAddress1()
    {
        return $this->address1;
    }

    /**
     * Set address2
     *
     * @param string $address2
     *
     * @return Address
     */
    public function setAddress2($address2)
    {
        $this->address2 = $address2;

        return $this;
    }

    /**
     * Get address2
     *
     * @return string
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * Set address3
     *
     * @param string $address3
     *
     * @return Address
     */
    public function setAddress3($address3)
    {
        $this->address3 = $address3;

        return $this;
    }

    /**
     * Get address3
     *
     * @return string
     */
    public function getAddress3()
    {
        return $this->address3;
    }

    /**
     * Set zipcode
     *
     * @param string $zipcode
     *
     * @return Address
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    /**
     * Get zipcode
     *
     * @return string
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Address
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return Address
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set customer
     *
     * @param \Bundles\UserBundle\Entity\Customer $customer
     *
     * @return Address
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
     * Set type
     *
     * @param integer $type
     *
     * @return Address
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }
}
