<?php
namespace Bundles\SecurityBundle\Controller;

use AppBundle\Controller\AppController;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;

use Bundles\UserBundle\Entity\Users;
use Bundles\UserBundle\Entity\Customer;


class GoogleController extends AppController
{
	/**
	 * login
	 * @Rest\Options("/login" )
	 */
	public function optionsLoginAction()
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
	 */
	
	public function postLoginAction(Request $request)
	{
		
	}
	
	
}