<?php
namespace AppBundle\Service\SalesForce;

use Bundles\OrderBundle\Entity\Orders;
use Bundles\OrderBundle\Entity\OrderItems;
use AppBundle\Service\SalesForce\AbstractSfService;
use AppBundle\Library\SalesForce\SObject;
use AppBundle\Library\SalesForce\QueryResult;

class SfGenerateOrderService extends AbstractSfService {
	
	public function __construct( $objContaner ) {
		parent::__construct( $objContaner );
	}
	
	public function run() {
	}

	public function updateSalesforceProducts() {
		$arrobjProducts = $this->objEntityManager->getRepository('BundlesProductBundle:Product')->getProducts(null, false );
		$arrmixRecords  = array();
		
		if( 0 < count( $arrobjProducts ) ) {
			foreach( $arrobjProducts as $objProduct ) {
				$objSObject = new SObject();
				$objSObject->Id = $objProduct->getProductNumber();
				$objSObject->fields = array(
					'quantity_available__c' => $objProduct->getQuantityAvailable(),
				);
				
				$objSObject->type 	= 'CustomProduct__C';
				$arrmixRecords[] 	= $objSObject;
			}
			
			try {
				$arrmixResponse = $this->m_objSforcePartnerClient->update( $arrmixRecords );

			} catch( \Exception $e ) {
				echo 'Unable to update products.';
			}
		}
		$this->output[] = 'SF Products Updated';
		
	}

	public function insertOrder( $intOrderId ) {

		$objOrder 		= $this->objEntityManager->getRepository('BundlesOrderBundle:Orders')->find( $intOrderId );
		$objSObject 	= new SObject();
		$objDateTime 	= new \DateTime();

		$objSObject->fields = array(
			'order_number__c' => $objOrder->getId(),
			'shipping_details__c' => $objOrder->getShippingDetails(),
			'amount__c' => $objOrder->getAmount(),
			'placed_on__c' => $objDateTime->format('Y-m-d H:i:s')
		);

		$objSObject->type	= 'Order__C';
		$arrmixResponse		= $this->m_objSforcePartnerClient->create( array( $objSObject ) );

		$this->output[] = 'Order Inserted';
		return current( $arrmixResponse )->id;
	}

	public function insertOrderItems( $intOrderId, $strSFOrderId, $arrobjProducts ) {

		
		$arrobjOrderItems 	= $this->objEntityManager->getRepository('BundlesOrderBundle:OrderItems')->fetchOrderItemsByOrderId( $intOrderId );
		$arrmixRecords		= array();

 		$arrobjRekeyedProducts = [];
		foreach( $arrobjProducts as $objProduct ) {
			$arrobjRekeyedProducts[$objProduct->getId()] = $objProduct;
		}

		foreach( $arrobjOrderItems as $objOrderItem ) {
			$objSObject 		= new SObject();
			$objSObject->fields = array(
				'order_item_number__c' 	=> $objOrderItem->getId(),
				'order_id__c' 			=> $strSFOrderId,
				'product_id__c' 		=> $arrobjRekeyedProducts[$objOrderItem->getProduct()->getId()]->getProductNumber(),
				'price__c' 				=> $objOrderItem->getPrice(),
				'discount__c' 			=> $objOrderItem->getDiscount(),
				'quantity__c' 			=> $objOrderItem->getQuantity()
			);

			$objSObject->type 	= 'order_item__c';

			$arrmixRecords[] = $objSObject;
		}
		$this->output[] = 'Order Items Inserted';
		$arrmixResponse = $this->m_objSforcePartnerClient->create( $arrmixRecords );
	
	}

	public function checkProductAvailibiltyOrderItems( $arrobjProducts, $arrmixOrderItems ) {

 		$arrstrProductIds = array();
 		$arrintItems	  = array();

		foreach( $arrobjProducts as $objProduct ) {
			$arrstrProductIds[] 							= '\'' . $objProduct->getProductNumber() . '\'';
			$arrintItems[$objProduct->getProductNumber()] 	= $arrmixOrderItems[$objProduct->getId()];
		}

		$strQueryString = '	SELECT
								Id,
								Quantity_Available__c
							FROM
								CustomProduct__C WHERE Id IN ( ' . implode( ',', $arrstrProductIds ) . ' )';
								
		try {
			$arrmixTempProducts = $this->m_objSforcePartnerClient->query( $strQueryString );
		} catch( \Exception $e ) {
			echo 'Unable to fetch records from Salesforce';
			return;
		}
	
		$objQueryResult 		= new QueryResult( $arrmixTempProducts );
		$this->arrmixProducts 	= array();
		$boolIsProductAvailable = true;
	
		for( $objQueryResult->rewind(); $objQueryResult->pointer < $objQueryResult->size; $objQueryResult->next() ) {
			$arrmixRecords 	= $objQueryResult->current();
			
			if( $arrmixRecords->fields->Quantity_Available__c < $arrintItems[$arrmixRecords->Id] ) {
				$boolIsProductAvailable = false;
				break;
			}
		}
		$this->output[] = 'Checked Product Avalaibility';
		return $boolIsProductAvailable;
	}
}