<?php
namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
// use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;

class AppController extends FOSRestController
{
	protected $objSession;
	protected $objEntityManager;
	
	public function create() {
		$this->objEntityManager = $this->getDoctrine()->getManager();
		$this->objUserRepository = $this->objEntityManager->getRepository('BundlesUserBundle:User');
		$this->objCustomerRepository = $this->objEntityManager->getRepository('BundlesUserBundle:Customer');
		$this->objAddress = $this->objEntityManager->getRepository('BundlesUserBundle:Address');
	}
	
	public function initialize() {
		$this->initializeSession();
	}
	
	private function initializeSession() {
		$this->objSession = $this->container->get('session');
		if( !$this->objSession->isStarted() ) {
			$this->objSession->start();
		}
		return $this->objSession;
	}
	
	public function getUserId() {
		return $this->objSession->get('user/id');
	}
	
	public function getCustomerId() {
		return $this->objSession->get('user/customer_id');
	}
	
	public function getUser( $boolRetunArray = false ) {
		return $this->objUserRepository->fetchById( (int) $this->getUserId(), $boolRetunArray );
	}
	
	public function getCustomer( $boolRetunArray = false ) {
		return $this->objCustomerRepository->fetchDetailsById( (int) $this->getCustomerId(), $boolRetunArray );
	}
	
	/**
	 * login
	 * @Rest\Options("/check" )
	 */
	public function optionsCheckAuthAction(Request $request)
	{
		return array(
			'GET' => array(
					'description' => 'Login Check',
			)
		);
	}
	
	/**
	 * login
	 * @Rest\Get("/check" )
	 */
	public function getCheckAuthAction()
	{
		return array( 'status' => 1 );
	}
	
}
