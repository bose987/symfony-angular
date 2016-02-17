<?php

namespace Bundles\OrderBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest; 
use AppBundle\Controller\AppController;
use AppBundle\Service\SalesForce\SfInboundService;

class SyncDatabaseController extends AppController
{

	/**
	 * Get Order
	 * @Rest\Options( "/sync/database" )
	 */
	public function optionsDatabaseSyncAction( Request $request ) {
		return array(1);
	}

	/**
	 * Get Order
	 * @Rest\Get( "/sync/database" )
	 */
	public function getDatabaseSyncAction() {
		
		$objSfInbound = new SfInboundService( $this->container );
		$objSfInbound->run();
		return [ 'status_updates' => $objSfInbound->getOutput() ];
	}
}