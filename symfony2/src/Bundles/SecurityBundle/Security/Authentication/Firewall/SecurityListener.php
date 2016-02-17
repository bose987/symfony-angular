<?php
namespace Bundles\SecurityBundle\Security\Authentication\Firewall;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

use Bundles\SecurityBundle\Security\Authentication\Token\SecurityToken;


class SecurityListener implements ListenerInterface{

	protected $tokenStorage;
	protected $authenticationManager;
	protected $strRequestAuthToken;
	
	public function __construct(TokenStorageInterface $tokenStorage, AuthenticationManagerInterface $authenticationManager, Container $container )
	{
		$this->tokenStorage = $tokenStorage;
		$this->authenticationManager = $authenticationManager;
		$this->container = $container;
	}
	
	public function handle(GetResponseEvent $event )
	{

		if( !$this->getToken( $event ) ) {
			$event->setResponse( $this->getUnauthorizedResponse() );
			return;
		}
		
		$objSession = $this->container->get('session');
		if( false == $objSession->isStarted() ) { 
			$objSession->start();
		}
		
		$objSecurityToken = new SecurityToken();
		$objSecurityToken->generateAuthToken(
			array(
				$objSession->get('user')['id'],
 				$objSession->get('security')['token'],
// 				$event->getRequest()->getClientIp(),
 				$event->getRequest()->headers->get('User-Agent')
			)
		);
		
		$objSecurityToken->setRequestToken( $this->strRequestAuthToken );
		try {
			$objSecurityToken = $this->authenticationManager->authenticate( $objSecurityToken );
			$this->tokenStorage->setToken( $objSecurityToken );
			return;
		} catch (AuthenticationException $failed) {
			
            // ... you might log something here

            // To deny the authentication clear the token. This will redirect to the login page.
            // Make sure to only clear your token, not those of other authentication listeners.
            // $token = $this->tokenStorage->getToken();
            // if ($token instanceof WsseUserToken && $this->providerKey === $token->getProviderKey()) {
            //     $this->tokenStorage->setToken(null);
            // }
            // return;
        }
        $this->setResponseMessage(
			array(
				'code' => 401,
				'msg' => 'Unauthorized Access. Please login to access.'
			)
		);
		$event->setResponse( $this->getUnauthorizedResponse() );
		return;
	}
	
	private function getToken( $event ) {
		$request = $event->getRequest();
		$strRequestAuthToken = $request->headers->get('authorization');
		
		if( '' ==  $strRequestAuthToken ) {
			$this->setResponseMessage(
				array(
					'code' => 401,
					'msg' => 'Secure Area. Please login to access.'
				)
			);
			return false;
		}
		
		$strRegex = '/Bearer[[:blank:]](.*)/';
		preg_match( $strRegex, $strRequestAuthToken, $arrstrTokenMatch );
		$this->strRequestAuthToken = $arrstrTokenMatch[1];
		return true;
	}
	
	private function getUnauthorizedResponse() {
		$response = new Response( $this->getResponseMessage() );
		$response->setStatusCode(Response::HTTP_OK );
		return $response;
	}
	
	private function setResponseMessage( $arrstrResponse ) {
		$this->arrstrResponse = $arrstrResponse;
	}
	
	private function getResponseMessage() {
		return json_encode( $this->arrstrResponse );
	}
}
