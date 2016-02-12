<?php

namespace Bundles\SecurityBundle\Controller;

use AppBundle\Controller\AppController;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;

use Bundles\UserBundle\Entity\User;
use Bundles\UserBundle\Entity\Customer;
use AppBundle\Validator\Security\FacebookValidator;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class FacebookController extends AppController
{
	/**
	 * login
	 * @Rest\Options("/login" )
	 */
	public function optionsLoginAction(Request $request)
	{
		return array(
			'POST' => array(
				'description' => 'Login Authentication',
			)
		);
	}
	
	/**
	 * login
	 * @Rest\Post("/login" )
 	 * @ApiDoc(
	 *  resource=true,
	 *  description="Facebook Login User",
	 * )
	 */
	
	public function postLoginAction(Request $request)
	{
		
		$objFacebookValidator = new FacebookValidator( $this->createFormBuilder( [] ), $request );
		$objFacebookValidator->validate( ['code', 'clientId', 'redirectUri'] );
		
		if( $objFacebookValidator->hasError() ) {
			return [ 'error' => $objFacebookValidator->getErrors() ];
		}
		
		$accessTokenUrl = 'https://graph.facebook.com/v2.3/oauth/access_token';
		$accessTokenUrl .= '?code=' . $objFacebookValidator->getData( 'code' );
		$accessTokenUrl .= '&client_id=' . $objFacebookValidator->getData( 'clientId' );
		$accessTokenUrl .= '&redirect_uri=' . $objFacebookValidator->getData( 'redirectUri' );
		$accessTokenUrl .= '&client_secret=' . $this->container->getParameter('facebook_app_secret');
		$strJsonResponse = $this->makeGetRequestToEndPoint( $accessTokenUrl );
		
		$objFacebookAccessTokenValidator = new FacebookValidator( $this->createFormBuilder() );
		$objFacebookAccessTokenValidator->setDataByJson( $strJsonResponse );
		$objFacebookAccessTokenValidator->validate( ['access_token', 'token_type', 'expires_in'] );
		
		if( $objFacebookAccessTokenValidator->hasError() ) {
			return [ 'error' => $objFacebookAccessTokenValidator->getErrors() ];
		}
		
		$graphApiUrl = 'https://graph.facebook.com/v2.3/me';
		$graphApiUrl .= '?access_token=' . $objFacebookAccessTokenValidator->getData( 'access_token' ); 
		$strJsonResponse = $this->makeGetRequestToEndPoint( $graphApiUrl );
		
		$objFacebookUserValidator = new FacebookValidator( $this->createFormBuilder() );
		$objFacebookUserValidator->setDataByJson( $strJsonResponse );
		$objFacebookUserValidator->validate( ['id', 'email', 'first_name', 'last_name', 'link', 'name', 'gender' ] );
		
		if( $objFacebookUserValidator->hasError() ) {
			return [ 'error' => $objFacebookUserValidator->getErrors() ];
		}
		
		$objUser = $this->objEntityManager->getRepository('BundlesUserBundle:User')->fetchByEmail( $objFacebookUserValidator->getData('email') );
		
		if( true == is_null( $objUser ) ) {
			$objCustomer = new Customer();
			$objCustomer->setFirstName( $objFacebookUserValidator->getData('first_name') );
			$objCustomer->setLastName( $objFacebookUserValidator->getData('last_name') );
			$objCustomer->setEmailAddress( $objFacebookUserValidator->getData('email') );
			
			$objUser = new User();
			$objUser->setName( $objFacebookUserValidator->getData('name') );
			$objUser->setEmail( $objFacebookUserValidator->getData('email') );
			$objUser->setFacebookId( $objFacebookUserValidator->getData('id') );
			$objUser->setFacebookAccessToken( $objFacebookAccessTokenValidator->getData( 'access_token' ) );
			$objUser->setCustomer( $objCustomer );		
	
			$this->objEntityManager->persist( $objUser );
			$this->objEntityManager->persist( $objCustomer );
			$this->objEntityManager->flush();
		}
		$objSecurityToken = $this->get('security.authentication.token');
		
		$this->objSession->set('user/id', $objUser->getId() );
		$this->objSession->set('security/token' , $objSecurityToken->getSessionToken() );
			
		if( false == is_null( $objUser->getCustomer() ) ) {
			$this->objSession->set( 'user/customer_id', $objUser->getCustomer()->getId() );
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
	}
	
	private function makeGetRequestToEndPoint( $strEndPoint ) {
	
		$objCurlSession = curl_init($strEndPoint);
	
		curl_setopt( $objCurlSession, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $objCurlSession, CURLOPT_CONNECTTIMEOUT , 120 );
		curl_setopt( $objCurlSession, CURLOPT_SSL_VERIFYPEER , false );
		curl_setopt( $objCurlSession, CURLOPT_FOLLOWLOCATION , true );
		curl_setopt( $objCurlSession, CURLOPT_HEADER, 1 );
	
		$strResponse = curl_exec( $objCurlSession );
		$intHeaderSize = curl_getinfo( $objCurlSession, CURLINFO_HEADER_SIZE );
		
		$strResponse = substr( $strResponse, $intHeaderSize );
		
		curl_close( $objCurlSession );
		return $strResponse;
	}
}