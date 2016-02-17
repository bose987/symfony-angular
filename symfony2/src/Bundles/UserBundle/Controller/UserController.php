<?php

namespace Bundles\UserBundle\Controller;

use AppBundle\Controller\AppController;
use Symfony\Component\HttpFoundation\Request;
use Bundles\UserBundle\Entity\Users;
use Bundles\UserBundle\Entity\Address;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations As Rest;

class UserController extends AppController
{
	/**
	 * Edit User
	 * @Rest\Options("/user" )
	 */
	public function optionsUserAction( Request $request ) {
		return array(
			'GET' => array(
				'description' => 'User details'
			),
			'PUT' => array(
				'description' => 'Update user details'
			)
		);
	}
	
	/**
	 * Edit User
	 * @Rest\Put("/user" )
 	 * @ApiDoc(
	 *  resource=true,
	 *  description="User update",
	 * )
	 */
	
	public function putUserAction( Request $request ) {
		try{
			$objCustomer = $this->getCustomer();
			$objCustomer->setPhone( $request->get('phone') );
			$strName = trim( $request->get('name') );
			list( $strFirstName, $strLastName ) = explode( ' ', $strName );
			$objCustomer->setFirstName( $strFirstName );
			$objCustomer->setLastName( $strLastName );
			$this->objEntityManager->persist( $objCustomer );
			$this->objEntityManager->flush();
			return ['success' => true ];
		} catch( \Exception $e) {
			return ['success' => false ];
		}
		
	}
	
	/**
	 * Get User
	 * @Rest\Get("/user" )
	 * @ApiDoc(
	 *  resource=true,
	 *  description="User Details",
	 * )
	 */
	public function getUserAction() {
		return $this->getCustomer( true );
	}
	
	
	/**
	 * Get User Addresses
	 * @Rest\Options("/user/billing" )
	 */
	public function optionsUserBillingAction() {
		return array(
			'GET' => array(
					'description' => 'User details'
			),
			'PUT' => array(
					'description' => 'Update user details'
			)
		);
	}
	
	/**
	 * Get User Addresses
	 * @Rest\Get("/user/billing" )
	 * @ApiDoc(
	 *  resource=true,
	 *  description="User Billing Details",
	 * )
	 */
	public function getUserBillingAction() {
		$arrmixAddresses 	= $this->objAddress->fetchAdressesByCustomerId( (int) $this->getCustomerId(), array( Address::TYPE_BILLING, Address::TYPE_SHIPPING ), true );
		$arrmixTempAddress	= array();

		foreach( $arrmixAddresses as $arrmixAddress ) {

			if( Address::TYPE_BILLING == $arrmixAddress['type'] ) {
				$arrmixTempAddress['billing_address'][] = $arrmixAddress;

			} else if( Address::TYPE_SHIPPING == $arrmixAddress['type']  ) {
				$arrmixTempAddress['shipping_address'][] = $arrmixAddress;
			}
		}

		return $arrmixTempAddress;
	}

	/**
	 * Edit User Billing Address
	 * @Rest\Options("/user/billing/{id}" )
	 */
	public function optionsUserBillingAddressAction() {
		return array(
			'PUT' => array(
					'description' => 'Update user details'
			)
		);
	}
	
	/**
	 * Edit User Billing Address
	 * @Rest\Put("/user/billing/{id}" )
	 * @ApiDoc(
	 *  resource=true,
	 *  description="Edit User Billing Details",
	 * )
	 */
	public function putUserBillingAddressAction( $id, Request $request ) {
		try{
			$objBillingAddress = $this->objAddress->fetchBillingDetailsByIdByCustomerId( $id, (int) $this->getCustomerId() );
			$objBillingAddress = $this->createAddress( $request, $objBillingAddress, Address::TYPE_BILLING );
			
			$this->objEntityManager->persist( $objBillingAddress );
			$this->objEntityManager->flush();
			
			return ['success' => true ];
		} catch( \Exception $e) {
			return ['success' => false ];
		}
	}

	/**
	 * Edit User Shipping Address
	 * @Rest\Options("/user/shipping/{id}" )
	 */
	public function optionsUserShippingAction() {
		return array(
			'PUT' => array(
					'description' => 'Update user details'
			)
		);
	}

	/**
	 * Edit User Shipping Address
	 * @Rest\Put("/user/shipping/{id}" )
	 * @ApiDoc(
	 *  resource=true,
	 *  description="Edit User Shipping Details"
	 * )
	 */
	public function putUserShippingAction( $id, Request $request ) {
		try{
			$objShippingAddress = $this->objAddress->fetchShippingDetailsByIdByCustomerId( $id, (int) $this->getCustomerId() );
			$objShippingAddress = $this->createAddress( $request, $objShippingAddress, Address::TYPE_SHIPPING );

			$this->objEntityManager->persist( $objShippingAddress );
			$this->objEntityManager->flush();
			
			return ['success' => true ];
		} catch( \Exception $e) {
			return ['success' => false ];
		}
	}

	/**
	 * Delete User Address
	 * @Rest\Options("/delete/address/{id}" )
	 */
	public function optionsUserAddressAction() {
		return array(
			'PUT' => array(
					'description' => 'Delete user details'
			)
		);
	}
	
	/**
	 * Delete User Address
	 * @Rest\Delete("/delete/address/{id}" )
	 * @ApiDoc(
	 *  resource=true,
	 *  description="Delete User Address"
	 * )
	 */
	public function deleteUserAddressAction( $id )
	{	
	    $objAddress = $this->objEntityManager->getRepository('BundlesUserBundle:Address')->find( $id );
	    
	    if( !$objAddress ) {
	       return ['success' => false ];
	    }

	    $this->objEntityManager->remove( $objAddress );
		$this->objEntityManager->flush();

		return ['success' => true ];
	}

	/**
	 * Create User Shipping Address
	 * @Rest\Options("/address/shipping/create" )
	 */
	public function optionsUserAddressShippingAction() {
		return array(
			'PUT' => array(
					'description' => 'Create user Shipping address'
			)
		);
	}

	/**
	 * Create User Shipping Address
	 * @Rest\Post("/address/shipping/create" )
	 * @ApiDoc(
	 *  resource=true,
	 *  description="Create User Shipping Details"
	 * )
	 */
	public function postUserAddressShippingAction( Request $request ) {
		try{
			$objShippingAddress = new Address();
			$objShippingAddress = $this->createAddress( $request, $objShippingAddress, Address::TYPE_SHIPPING );
			$objCustomer 		= $this->objEntityManager->getRepository('BundlesUserBundle:Customer')->find(  (int) $this->getCustomerId() );
			
			$objShippingAddress->setCustomer( $objCustomer );
			$this->objEntityManager->persist( $objShippingAddress );
			$this->objEntityManager->flush();
			
			return ['success' => true ];
		} catch( \Exception $e) {
			return array( $e->getMessage() );
			return ['success' => false ];
		}
	}

	/**
	 * Create User Billing Address
	 * @Rest\Options("/address/billing/create" )
	 */
	public function optionsUserAddressBillingAction() {
		return array(
			'PUT' => array(
					'description' => 'Create user Billing address'
			)
		);
	}

	/**
	 * Create User Billing Address
	 * @Rest\Post("/address/billing/create" )
	 * @ApiDoc(
	 *  resource=true,
	 *  description="Create user Billing address"
	 * )
	 */
	public function postUserAddressBillingAction( Request $request ) {
		try{
			$objBillingAddress 	= new Address();
			$objBillingAddress 	= $this->createAddress( $request, $objBillingAddress, Address::TYPE_BILLING );
			$objCustomer 		= $this->objEntityManager->getRepository('BundlesUserBundle:Customer')->find(  (int) $this->getCustomerId() );
			
			$objBillingAddress->setCustomer( $objCustomer );
			$this->objEntityManager->persist( $objBillingAddress );
			$this->objEntityManager->flush();

			return ['success' => true ];
		} catch( \Exception $e) {
			return ['success' => false ];
		}
	}

	public function createAddress( $request, $objAddress, $intAddressTypeId ) {
		$objAddress->setFirstname( $request->get('first_name') );
		$objAddress->setLastname( $request->get('last_name') );
		$objAddress->setType( $intAddressTypeId );
		$objAddress->setCellphone( $request->get('cell_phone') );
		$objAddress->setPhone( $request->get('phone') );
		$objAddress->setAddress1( $request->get('address1') );
		$objAddress->setAddress2( $request->get('address2') );
		$objAddress->setAddress3( $request->get('address3') );
		$objAddress->setZipcode( $request->get('zip') );
		$objAddress->setCity( $request->get('city') );
		$objAddress->setCountry( $request->get('country') );

		return $objAddress;
	}
}