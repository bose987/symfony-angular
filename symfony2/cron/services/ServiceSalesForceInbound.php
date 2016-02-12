<?php
class ServiceSalesForceInbound{

	protected $m_objSforcePartnerClient;
	protected $m_objSforceConnection;
	protected $m_objSoapClient;
	protected $m_objDatabase;
	
	protected $m_arrmixProducts;
	protected $m_arrstrProductCategories;
	
	
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
	
	public function getAllProducts() {
		$strQueryString 	= '	SELECT 
									Id, 
									Name,
									Catagory_Id__c,
									Color_Code__c,
									Discount__c,
									List_Price__c,
									Product_Code__c,
									Product_Description__c,
									Product_Image_Url__c,
									Quantity_Available__c,
									Size__c 
								FROM 
									CustomProduct__C';
		
		try {
			$arrmixTempProducts = $this->m_objSforcePartnerClient->query( $strQueryString );
		} catch( Exception $e ) {
			echo 'Unable to fetch records from Salesforce';
		}
		
		$objQueryResult 	= new QueryResult( $arrmixTempProducts );
		$this->arrmixProducts		= array();
		
		for( $objQueryResult->rewind(); $objQueryResult->pointer < $objQueryResult->size; $objQueryResult->next() ) {
		    $arrmixRecords 	= $objQueryResult->current();
			
			$this->arrmixProducts[$arrmixRecords->Id]['product_number'] 	= $arrmixRecords->Id;
			$this->arrmixProducts[$arrmixRecords->Id]['name'] 				= $arrmixRecords->fields->Name;
			$this->arrmixProducts[$arrmixRecords->Id]['category'] 			= $arrmixRecords->fields->Catagory_Id__c;
			$this->arrmixProducts[$arrmixRecords->Id]['link'] 				= $arrmixRecords->fields->Product_Image_Url__c;
			$this->arrmixProducts[$arrmixRecords->Id]['list_price'] 		= $arrmixRecords->fields->List_Price__c;
			$this->arrmixProducts[$arrmixRecords->Id]['description'] 		= $arrmixRecords->fields->Product_Description__c;
			$this->arrmixProducts[$arrmixRecords->Id]['color'] 				= $arrmixRecords->fields->Color_Code__c;
			$this->arrmixProducts[$arrmixRecords->Id]['size'] 				= $arrmixRecords->fields->Size__c;
			$this->arrmixProducts[$arrmixRecords->Id]['quantity_available'] = $arrmixRecords->fields->Quantity_Available__c;
			$this->arrmixProducts[$arrmixRecords->Id]['discount'] 			= $arrmixRecords->fields->Discount__c;
		}	
	}
	
	public function getAllCategories() {
		
		$this->m_arrstrProductCategories = array();
		
		$strQueryString 	= '	SELECT Id,Name,ParentCatagoryId__c from Catagory__C order by Id asc';
		
		try {
			$arrmixTempProducts = $this->m_objSforcePartnerClient->query( $strQueryString );
		} catch( Exception $e ) {
			echo 'Unable to fetch records from Salesforce';
		}
		
		$objQueryResult 	= new QueryResult( $arrmixTempProducts );
		$this->arrmixProducts		= array();
		
		for( $objQueryResult->rewind(); $objQueryResult->pointer < $objQueryResult->size; $objQueryResult->next() ) {
		    $arrmixRecords 	= $objQueryResult->current();
			
			$this->m_arrstrProductCategories[$arrmixRecords->Id]['id'] 					= $arrmixRecords->Id;
			$this->m_arrstrProductCategories[$arrmixRecords->Id]['name'] 				= $arrmixRecords->fields->Name;
			$this->m_arrstrProductCategories[$arrmixRecords->Id]['parent_category_id'] 	= $arrmixRecords->fields->ParentCatagoryId__c;
		}	
	}
	
	public function fetchProductCategories() {
		$strQuery 		= "SELECT * from category";
		$arrmixResult 	= $this->m_objDatabase->query( $strQuery, MYSQLI_USE_RESULT );
		$arrstrTemp 	= array();

		if( $arrmixResult ) {
			$arrmixResult = mysqli_fetch_all( $arrmixResult, MYSQLI_ASSOC );
			foreach( $arrmixResult as $arrmixData ) {
				$arrstrTemp[$arrmixData['salesforce_id']]['parent_salesforce_id'] = $arrmixData['parent_salesforce_id'];
				$arrstrTemp[$arrmixData['salesforce_id']]['id'] = $arrmixData['id'];
			}
		}
		return $arrstrTemp;
	}
	
	public function fetchProducts() {
		$arrmixResult = $this->m_objDatabase->query( 'SELECT * from product', MYSQLI_USE_RESULT );
		$arrstrTemp   = array();
		
		if( $arrmixResult ) {
			$arrmixResult = mysqli_fetch_all( $arrmixResult, MYSQLI_ASSOC );
			
			foreach( $arrmixResult as $arrmixData ) {
				$arrstrTemp[$arrmixData['product_number']] = $arrmixData['name'];
			}
		}
		return $arrstrTemp;
	}
	
	public function insertProductCategories() {
		$arrmixExistingCategories 	= $this->fetchProductCategories();
		$strQuery					= 'START TRANSACTION; ';
		
		$this->getAllCategories();
		
		foreach( $this->m_arrstrProductCategories as $strData ) {
			if( false == array_key_exists( $strData['id'], $arrmixExistingCategories ) ) {
				$strQuery .= "INSERT INTO category( name, description, parent_salesforce_id, salesforce_id )
				VALUES (" . "'" . mysql_real_escape_string( $strData['name'] ) . "','" . mysql_real_escape_string( $strData['name'] ) . "','" . $strData['parent_category_id'] . "','" . $strData['id'] . "');";
			} else {
				$strQuery .= " UPDATE category
					set name = '" . mysql_real_escape_string( $strData['name'] ) . "',
					description = '" . mysql_real_escape_string( $strData['name'] ) . "',
					parent_salesforce_id = '" . $strData['parent_category_id'] . "' 
					WHERE salesforce_id = '" . $strData['id'] . "';";
			}
		}
		
		$strQuery .= ' COMMIT;';

		if ( strlen( $strQuery ) > 10 && $resResult = $this->m_objDatabase->multi_query( $strQuery ) === true ) {
		    echo "Categories created/updated successfully.";
		}
		
		$this->m_objDatabase->close();
	}
	
	public function syncCategories() {			
		$this->m_objDatabase 		= new mysqli('localhost', 'root', '', DATABASE_NAME );
		$arrmixExistingCategories 	= $this->fetchProductCategories();
		$strQuery 					= '';	
		
		foreach( $this->m_arrstrProductCategories as $strData ) {
			if( true == array_key_exists( $strData['id'], $arrmixExistingCategories ) && null != $strData['parent_category_id'] ) {
				$strQuery .= " UPDATE category
					set category_id = " . $arrmixExistingCategories[$strData['parent_category_id']]['id'] . " 
					WHERE salesforce_id = '" . $strData['id'] . "';";
			} 
		}
		
		if ( strlen( $strQuery ) > 10 && $resResult = $this->m_objDatabase->multi_query( $strQuery ) == true ) {
			echo "<br> Relations updated successfully";
		}
	}
	
	public function insertProducts() {
		$this->m_objDatabase		= new mysqli('localhost', 'root', '', DATABASE_NAME );
		$arrmixExistingProducts 	= $this->fetchProducts();
		$arrmixProductCatagories	= $this->fetchProductCategories();
		$strQuery					= '';
		
		$this->getAllProducts();

		foreach( $this->arrmixProducts as $strData ) {
			if( 0 < count( $arrmixExistingProducts ) && true == array_key_exists( $strData['product_number'], $arrmixExistingProducts ) ) {
				
				$strQuery .= "UPDATE product
					set name = '" . mysql_real_escape_string( $strData['name'] ) . "',
					category_id = " . $arrmixProductCatagories[$strData['category']]['id'] . ",
					list_price = " . $strData['list_price'] . ",
					size = '" . $strData['size'] . "', 
					description = '" . mysql_real_escape_string( $strData['description'] ) . "', 
					color = '" . $strData['color'] . "', 
					link = '" . $strData['link']  . "', 
					quantity_available = " . ( int ) $strData['quantity_available'] . ",
					discount = " . ( int ) $strData['discount'] . "
					WHERE product_number = '" . $strData['product_number'] . "';";
					
			} else {

				if( 0 < count( $arrmixProductCatagories ) && true == array_key_exists( $strData['category'], $arrmixProductCatagories ) ) {
						$strQuery .= "INSERT INTO product( category_id, name, product_number, list_price, size, description, color, link, quantity_available )
							VALUES (" . 
							$arrmixProductCatagories[$strData['category']]['id'] . 
							",'" . mysql_real_escape_string( $strData['name'] ) . 
							"','" . $strData['product_number'] . 
							"',". $strData['list_price'] . ",'" . $strData['size'] . 
							"','" . mysql_real_escape_string( $strData['description'] ) . "','" . $strData['color'] .
							"discount = " . ( int ) $strData['discount'] .
							"','" . $strData['link'] . "'," . ( int ) $strData['quantity_available'] .");";
				}	
			}
		}
		if ( strlen( $strQuery ) > 10 && $this->m_objDatabase->multi_query( $strQuery ) === true ) {
		    echo "<br> Records created/updated successfully";
		}
		
		$this->m_objDatabase->close();
	}
}
?>