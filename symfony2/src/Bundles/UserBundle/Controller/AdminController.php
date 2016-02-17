<?php

namespace Bundles\UserBundle\Controller;

use AppBundle\Controller\AppController;
use Symfony\Component\HttpFoundation\Request;

use FOS\RestBundle\Controller\Annotations As Rest;
use Bundles\UserBundle\Entity\Users;
use Bundles\UserBundle\Entity\Customer;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;


class AdminController extends AppController
{
	
	/**
	 * Get User
	 * @Rest\Get("/{id}", requirements={"id":"\d+"} )
	 * @ApiDoc(
	 *  resource=true,
	 *  description="Get User Details",
	 * )
	 */
	
	public function showUserAction( $id ) {
		$objUser = $this->getDoctrine()->getManager()->getRepository('BundlesUserBundle:Users')->fetchById( $id );
		return $objUser;
	}
	
	/**
	 * Edit User
	 * @Rest\Put("/{id}" )
	 * @ApiDoc(
	 *  resource=true,
	 *  description="Edit User Details",
	 * )
	 */
	
	public function putUserAction( $id ) {
	
	}
	
	/**
	 * Add User
	 * @Rest\Post("/add" )
	 * @ApiDoc(
	 *  resource=true,
	 *  description="User Add",
	 * )
	 */
	
	public function postAddUserAction( Request $request ) {
		$objEntityManager = $this->getDoctrine()->getManager();
	
		$objCustomer = new Customer();
		$objCustomer->setEmailAddress( $request->get('email') );
		$objCustomer->setBillingAddressId(1);
		$objCustomer->setFirstName( $request->get('name') );
		$objCustomer->setLastName( $request->get('name') );
	
		$objUser = new User();
		$objUser->setEmail( $request->get('email') );
		$objUser->setPassword( $request->get('password') );
		$objUser->setName( $request->get('name') );
		$objUser->setCustomer( $objCustomer );
	
		$objEntityManager->persist( $objCustomer );
		$objEntityManager->persist( $objUser );
	
		$objEntityManager->flush();
	
		return array(
				'user' => array(
					'id' => $objUser->getId()
				)
		);
	}
	
	/**
	 * Add User
	 * @Rest\Get("/users" )
	 * @ApiDoc(
	 *  resource=true,
	 *  description="Users Details",
	 * )
	 */
	
	public function getUsersAction( Request $request )
	{
		$arrintUserIds = NULL;
		if( false == is_null( $strUserIds = $request->query->get('ids') ) ) {
			$arrintUserIds = json_decode( $strUserIds, true );
		}
		$objUserRepository = $this->getDoctrine()->getManager()->getRepository('BundlesUserBundle:Users');
		if( false == is_null( $arrintUserIds ) ) {
			$users = $objUserRepository->findById( $arrintUserIds );
		} else {
			$users = $objUserRepository->fetchUsers( 100 );
		}
		return $users;
	}
}