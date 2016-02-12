<?php
class ServiceSalesForceOutbound{

	protected $m_objSforcePartnerClient;
	protected $m_objSforceConnection;
	protected $m_objSoapClient;
	protected $m_objDatabase;
	
	protected $m_arrmixProducts;	
	
	public function __construct() {
       
		try {
			$this->m_objSforcePartnerClient = new SforcePartnerClient();
			$this->m_objSoapClient 			= $this->m_objSforcePartnerClient->createConnection( SOAP_CLIENT_BASEDIR.'/partner.wsdl.xml' );
			$this->m_objSforceConnection 	= $this->m_objSforcePartnerClient->login( USERNAME, PASSWORD . TOKEN );
			$this->m_objDatabase			= new mysqli('localhost', 'root', '', DATABASE_NAME );
			
			if (mysqli_connect_errno()) {
				echo "Failed to connect to MySQL: " . mysqli_connect_error();
				exit;
		    }
  
		} catch( Exception $e ) {
			echo 'Unable to connect to Salesforce';
		}
	}
	
	public function fetchProducts() {
		$arrmixResult = mysqli_query( $this->m_objDatabase, 'SELECT id, product_number, quantity_available from product' );
		$arrstrTemp   = array();
		
		if( $arrmixResult ) { 
			$arrmixResult = mysqli_fetch_all( $arrmixResult, MYSQLI_ASSOC );
			foreach( $arrmixResult as $arrmixData ) {
				$arrstrTemp[$arrmixData['product_number']] = $arrmixData['quantity_available'];
			}
		}
		return $arrstrTemp;
	}
	
	public function updateSalesforceProducts() {
		$arrmixProducts = $this->fetchProducts();
		$arrmixRecords  = array();
		
		if( 0 < count( $arrmixProducts ) ) {
			foreach( $arrmixProducts as $strKey => $intQuantity ) {
				$objSObject = new SObject();
				$objSObject->Id = $strKey;
				$objSObject->fields = array(
					'quantity_available__c' => $intQuantity,
				);
				
				$objSObject->type 	= 'CustomProduct__C';
				$arrmixRecords[] 	= $objSObject;
			}
			
			try {
				$arrmixResponse = $this->m_objSforcePartnerClient->update( $arrmixRecords );

				foreach( $arrmixResponse as $objResult) {
					if( true == $objResult->success )
						echo 'Product Id: ' . $objResult->id . " updated successfully. <br/>";
				}
			} catch( Exception $e ) {
				echo 'Unable to update records to Salesforce';
			}
		}
	}
}
?>