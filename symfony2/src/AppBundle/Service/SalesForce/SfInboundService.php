<?php
namespace AppBundle\Service\SalesForce;


use AppBundle\Library\SalesForce\QueryResult;
use Bundles\ProductBundle\Entity\Category;
use Symfony\Component\Console\Output\OutputInterface;
use Bundles\ProductBundle\Entity\Product;
use AppBundle\Service\SalesForce\AbstractSfService;

class SfInboundService extends AbstractSfService {
	
	public function __construct( $objContaner, OutputInterface $output ) {
		parent::__construct( $objContaner, $output );
	}
	
	public function run() {
		$this->insertOrUpdateCategories();
		$this->updateCategoryRelation();
		$this->insertOrUpdateProducts();
	}
	
	private function getAllProducts() {
		$strQueryString 	= 'SELECT
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
		} catch( \Exception $e ) {
			echo 'Unable to fetch records from Salesforce';
		}
	
		$objQueryResult = new QueryResult( $arrmixTempProducts );
		$this->arrmixProducts = array();
	
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
	
	private function getAllCategories() {
	
		$this->m_arrstrProductCategories = array();
	
		$strQueryString 	= '	SELECT Id,Name,ParentCatagoryId__c from Catagory__C order by Id asc';
	
		try {
			$arrmixTempProducts = $this->m_objSforcePartnerClient->query( $strQueryString );
		} catch( \Exception $e ) {
			echo 'Unable to fetch records from Salesforce';
		}
	
		$objQueryResult 	= new QueryResult( $arrmixTempProducts );
	
		for( $objQueryResult->rewind(); $objQueryResult->pointer < $objQueryResult->size; $objQueryResult->next() ) {
			$arrmixRecords 	= $objQueryResult->current();
				
			$this->m_arrstrProductCategories[$arrmixRecords->Id]['id'] 					= $arrmixRecords->Id;
			$this->m_arrstrProductCategories[$arrmixRecords->Id]['name'] 				= $arrmixRecords->fields->Name;
			$this->m_arrstrProductCategories[$arrmixRecords->Id]['parent_category_id'] 	= $arrmixRecords->fields->ParentCatagoryId__c;
		}
	}
	
	private function insertOrUpdateCategories() {
		$this->arrobjCategories = $this->objEntityManager->getRepository('BundlesProductBundle:Category')->getCategory(null, false );
		$this->getAllCategories();

		//update existing
		foreach( $this->arrobjCategories as $objCategory ) {
			if( isset( $this->m_arrstrProductCategories[$objCategory->getSalesforceId()] ) ) {
				$arrmixCategory = $this->m_arrstrProductCategories[$objCategory->getSalesforceId()];
				$objCategory->setName( $arrmixCategory['name'] );
				$objCategory->setParentSalesforceId( $arrmixCategory['parent_category_id'] );
				$objCategory->setDescription( $arrmixCategory['name'] );
				unset( $this->m_arrstrProductCategories[$objCategory->getSalesforceId()] );
				$this->objEntityManager->persist( $objCategory );
			}
		}

		//Add
		if( is_array( $this->m_arrstrProductCategories ) && count( $this->m_arrstrProductCategories ) > 0 ) {
			foreach( $this->m_arrstrProductCategories as $arrstrData ) {
				$objCategory = new Category();

				$objCategory->setName( $arrstrData['name'] );
				$objCategory->setSalesforceId( $arrstrData['id'] );
				$objCategory->setParentSalesforceId( $arrstrData['parent_category_id'] );
				$objCategory->setDescription( $arrstrData['name'] );
				$this->objEntityManager->persist( $objCategory );
			}
		}

		$this->objEntityManager->flush();
		$this->output->writeln( 'Categories created/updated successfully.' );
	}
	
	private function updateCategoryRelation() {
		$arrobjCategories = $this->objEntityManager->getRepository('BundlesProductBundle:Category')->getCategory( null, false );
		
		$arrobjRekeyedCategories = [];
		foreach( $arrobjCategories as $arrobjCategory ) {
			$arrobjRekeyedCategories[$arrobjCategory->getSalesForceId()] = $arrobjCategory;
		}
		
		foreach( $arrobjCategories as $objCategory ) {
			if( !is_null( $objCategory->getParentSalesForceId() ) ) {
				$objCategory->setCategoryId( $arrobjRekeyedCategories[$objCategory->getParentSalesForceId()]->getId() );
				$this->objEntityManager->persist( $objCategory );
			}
		}
		$this->objEntityManager->flush();
		$this->output->writeln( 'Categories relations updated successfully.' );
	}

	private function insertOrUpdateProducts() {
		$this->arrobjProducts = $this->objEntityManager->getRepository('BundlesProductBundle:Product')->getProducts(null, false );
		$arrmixCategories = $this->objEntityManager->getRepository('BundlesProductBundle:Category')->getCategory(null);
		$arrmixCategories = $this->rekeyArray( $arrmixCategories, 'salesforce_id' );
		$this->getAllProducts();

		foreach( $this->arrobjProducts as $objProduct ) {
			if( isset( $this->arrmixProducts[$objProduct->getProductNumber()] ) ) {
				$arrmixProduct= $this->arrmixProducts[$objProduct->getProductNumber()];
	
				$objProduct->setName( $arrmixProduct['name'] );
				$objProduct->setDescription( $arrmixProduct['description'] );
				$objProduct->setSize( $arrmixProduct['size'] );
				$objProduct->setListPrice( $arrmixProduct['list_price'] );
				$objProduct->setColor( $arrmixProduct['color'] );
				$objProduct->setLink( $arrmixProduct['link'] );
				$objProduct->setQuantityAvailable( $arrmixProduct['quantity_available'] );
				$objProduct->setDiscount( $arrmixProduct['discount'] );
				$objProduct->setCategoryId( $arrmixCategories[$arrmixProduct['category']]['id'] );
				unset( $this->arrmixProducts[$objProduct->getProductNumber()] );
				$this->objEntityManager->persist( $objProduct );
			}
		}
	
		if( is_array( $this->arrmixProducts ) && count( $this->arrmixProducts ) > 0 ) {
			foreach( $this->arrmixProducts as $arrmixProduct ) {
				$objProduct = new Product();
				$objProduct->setName( $arrmixProduct['name'] );
				$objProduct->setProductNumber( $arrmixProduct['product_number'] );
				$objProduct->setDescription( $arrmixProduct['description'] );
				$objProduct->setSize( $arrmixProduct['size'] );
				$objProduct->setListPrice( $arrmixProduct['list_price'] );
				$objProduct->setColor( $arrmixProduct['color'] );
				$objProduct->setLink( $arrmixProduct['link'] );
				$objProduct->setQuantityAvailable( $arrmixProduct['quantity_available'] );
				$objProduct->setDiscount( $arrmixProduct['discount'] );
				$objProduct->setCategoryId( $arrmixCategories[$arrmixProduct['category']]['id'] );
				$this->objEntityManager->persist( $objProduct );
			}
		}
	
		$this->objEntityManager->flush();
		$this->output->writeln( 'Product Records created/updated successfully.' );
	}
	
	public function rekeyArray( $arrmixArrayData, $strKey ) {
		$arrmixRekeyedArray = [];
		foreach( $arrmixArrayData as $arrmixArray ) {
			$arrmixRekeyedArray[$arrmixArray[$strKey]] = $arrmixArray; 
		}
		return $arrmixRekeyedArray;
	}
}