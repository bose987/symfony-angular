<?php

namespace Bundles\UserBundle\Controller;

use AppBundle\Controller\AppController;
use Symfony\Component\HttpFoundation\Request;
use Bundles\UserBundle\Entity\User;
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
	 * Edit User
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
	 * Get User
	 * @Rest\Get("/user/billing" )
	 * @ApiDoc(
	 *  resource=true,
	 *  description="User Billing Details",
	 * )
	 */
	public function getUserBillingAction() {
		return $this->objAddress->fetchBillingDetailsByCustomerId( (int) $this->getCustomerId(), true );
	}
	
	/**
	 * Get User
	 * @Rest\Put("/user/billing/{id}" )
	 * @ApiDoc(
	 *  resource=true,
	 *  description="Edit User Billing Details",
	 * )
	 */
	public function putUserBillingAction( $id, Request $request ) {
		try{
			$objBillingAddress = $this->objAddress->fetchBillingDetailsByIdByCustomerId( $id, (int) $this->getCustomerId() );
			
			$objBillingAddress->setFirstname( $request->get('first_name') );
			$objBillingAddress->setLastname( $request->get('last_name') );
			$objBillingAddress->setCellphone( $request->get('cell_phone') );
			$objBillingAddress->setPhone( $request->get('phone') );
			$objBillingAddress->setAddress1( $request->get('address1') );
			$objBillingAddress->setAddress2( $request->get('address2') );
			$objBillingAddress->setAddress3( $request->get('address3') );
			$objBillingAddress->setZipcode( $request->get('zip') );
			$objBillingAddress->setCity( $request->get('city') );
			$objBillingAddress->setCountry( $request->get('country') );
			$this->objEntityManager->persist( $objBillingAddress );
			$this->objEntityManager->flush();
			
			return ['success' => true ];
		} catch( \Exception $e) {
			return ['success' => false ];
		}
	}
	
}