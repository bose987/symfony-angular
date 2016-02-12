<?php
namespace AppBundle\Service\SalesForce;

use AppBundle\Library\SalesForce\SforcePartnerClient;

abstract class AbstractSfService {
	
	public function __construct( $objContaner, $output ) {
		try {
				
			$this->container = $objContaner;
			$strUserName = $this->container->getParameter('sf_user');
			$strPassword = $this->container->getParameter('sf_pass');
			$strToken = $this->container->getParameter('sf_token');
			$strBaseDir = $this->container->getParameter('kernel.root_dir') . '/Resources/sf';
			
			$this->objEntityManager = $this->container->get('doctrine')->getEntityManager();
			$this->output = $output;
				
			$this->m_objSforcePartnerClient = new SforcePartnerClient();
			$this->m_objSoapClient 			= $this->m_objSforcePartnerClient->createConnection( $strBaseDir . '/partner.wsdl.xml' );
			$this->m_objSforceConnection 	= $this->m_objSforcePartnerClient->login( $strUserName, $strPassword . $strToken );
		
		} catch( \Exception $e ) {
			echo 'Unable to connect to Salesforce';
		}
	}
	
	abstract function run();
}