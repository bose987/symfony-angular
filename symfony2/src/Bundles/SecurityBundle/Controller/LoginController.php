<?php

namespace Bundles\SecurityBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;

use AppBundle\Controller\AppController;
use AppBundle\Validator\Security\LoginValidator;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class LoginController extends AppController
{
	/**
	 * @Rest\Options("/login" )
	 */
	public function optionsLoginAction(Request $request)
	{
		return array(
			'POST' => array(
				'description' => 'Login Authentication',
				'parameters' => array(
					'email' => array(
						'type' => 'string',
						'description' => 'Email',
						'required'=> true
					),
					'password' => array(
						'type' => 'string',
						'description' => 'Password',
						'required'=> true
					)
				)	
			)	
		);
	}
	
	/**
	 * @Rest\Post("/login" )
	 * @ApiDoc(
	 *  resource=true,
	 *  description="Login User",
	 * )
	 */
	public function postLoginAction(Request $request)
	{
	
		$objLoginvalidator = new LoginValidator( $this->createFormBuilder(), $request );
		$objLoginvalidator->validate();
		
		if( $objLoginvalidator->hasError() ) {
			return [ 'error' => $objLoginvalidator->getErrors() ];
		}
		
		$objEntityManager = $this->getDoctrine()->getEntityManager();
		$objUser = $objEntityManager->getRepository('BundlesUserBundle:User')->findOneBy( $objLoginvalidator->getData() );

		if( false == is_null( $objUser ) ) {
			$objSecurityToken = $this->get('security.authentication.token');
			
			$this->objSession->set( 'user/id', $objUser->getId() );
			$this->objSession->set( 'security/token' , $objSecurityToken->getSessionToken() );

			if( false == is_null( $objUser->getCustomer() ) ) {
				$this->objSession ->set( 'user/customer_id', $objUser->getCustomer()->getId() );
			}
			
			$objSecurityToken->generateAuthToken(
				array(
					$objUser->getId(),
					$objSecurityToken->getSessionToken(),
					$request->getClientIp(),
					$request->headers->get('User-Agent')
				)
			);
			return array(
				'token' => $objSecurityToken->getAuthToken()
			);
		} else {
			return array(
				'error' => true,
				'msg' => 'No user found.'
			);
		}
	}
	
	/**
	 * @Rest\Post("/logout")
	 * @ApiDoc(
	 *  resource=true,
	 *  description="Logout user",
	 * )
	 */
	
	public function postLogoutAction()
	{
		try {
			$this->objSession->invalidate(1);
			return array('logout' => true );
		} catch (\Exception $e) {
			return array('logout' => false );
		}
	}
}
