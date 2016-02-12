<?php
namespace Bundles\SecurityBundle\Controller;


use AppBundle\Controller\AppController;
use Symfony\Component\HttpFoundation\Request;

use FOS\RestBundle\Controller\Annotations as Rest;

use Bundles\UserBundle\Entity\User;
use Bundles\UserBundle\Entity\Customer;
use AppBundle\Validator\Security\SignUpValidator;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class SignUpController extends AppController
{
	/**
	 * @Rest\Options("/signup" )
	 */
	public function optionsSignUpAction(Request $request)
	{
		return array(
			'POST' => array(
				'description' => 'Sign Up',
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
					),
					'name' => array(
						'type' => 'string',
						'description' => 'Name',
						'required'=> true
					)
				)	
			)	
		);
	}
	
	/**
	 * @Rest\Post("/signup" )
	 * @ApiDoc(
	 *  resource=true,
	 *  description="Signup",
	 * )
	 */
	public function postSignUpAction( Request $request )
	{
		$objSignUpvalidator = new SignUpValidator( $this->createFormBuilder(), $request );
		$objSignUpvalidator->validate();
		
		if( $objSignUpvalidator->hasError() ) {
			return [ 'errors' => $objSignUpvalidator->getErrors() ];
		}
		
		$objUser = new User();
		$objCustomer = new Customer();
		
		$objCustomer->setEmailAddress( $objSignUpvalidator->getData( 'email' ) );
		$strName = trim( $objSignUpvalidator->getData( 'name' ) );
		list( $strFirstName, $strLastName ) = explode( ' ', $strName );
		$objCustomer->setFirstName( $strFirstName );
		$objCustomer->setLastName( $strLastName );
		
		$objUser->setEmail( $objSignUpvalidator->getData( 'email' ) );
		$objUser->setPassword( $objSignUpvalidator->getData( 'password' ) );
		$objUser->setCustomer( $objCustomer );
		
		$this->objEntityManager->persist( $objCustomer );
		$this->objEntityManager->persist( $objUser );
		
		$this->objEntityManager->flush();
		
		$objSecurityToken = $this->get('security.authentication.token');
		
		$this->objSession->set('user/id', $objUser->getId() );
		$this->objSession->set('user/customer_id', $objCustomer->getId() );
		$this->objSession->set('security/token', $objSecurityToken->getSessionToken() );
		
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
	}
}