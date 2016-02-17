<?php

namespace Bundles\OrderBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use FOS\RestBundle\Controller\Annotations as Rest; 
use Bundles\OrderBundle\Entity\Orders;
use Bundles\OrderBundle\Entity\OrderItems;
use Bundles\ProductBundle\Entity\Product;
use AppBundle\Controller\AppController;
use AppBundle\Service\SalesForce\SfGenerateOrderService;
use Symfony\Component\Console\Output\OutputInterface;
use Bundles\UserBundle\Entity\Address;

class OrderController extends AppController
{

	/**
	 * Get Order
	 * @Rest\Options("/order/{id}", requirements={"id":"\d+"} )
	 */
	public function optionsOrderDetailsAction( Request $request ) {
		return array(1);
	}

	/**
	 * Get Order
	 * @Rest\Get("/order/{id}", requirements={"id":"\d+"} )
	 */
	public function getOrderDetailsAction( $id, Request $request ) {
		$intCustomerId = $this->objSession->get( 'user/customer_id');
		$arrmixOrderDetails = $this->getDoctrine()->getManager()->getRepository('BundlesOrderBundle:Orders')->fetchDetailsByIdByCustomerId( $id, $intCustomerId );
		return $arrmixOrderDetails;

	}
	
	/**
	 * Place Order
	 * @Rest\Options("/order/place" )
	 */
	public function optionsPlaceOrderAction( Request $request ) {
		return array(1);
	}

	/**
	 * Place Order
	 * @Rest\Post("/order/place" )
	 */
	public function postPlaceOrderAction( Request $request ) {
		
		set_time_limit(120);
		ini_set('memory_limit', '4096M');

		$strJson			= $request->getContent();
		
		$arrmixProducts 	= array();
		$objEntityManager 	= $this->getDoctrine()->getManager();
		$objOrder 			= new Orders();
		$arrmixOrderDetails = json_decode( $strJson, true );
		
		$arrmixTempProducts	= $arrmixOrderDetails['products'];


		foreach( $arrmixTempProducts as $arrmixproduct ) {
			$arrmixProducts[$arrmixproduct['id']] = $arrmixproduct['quantity'];
		}
		
		$products 			= $objEntityManager->getRepository('BundlesProductBundle:Product')->findById( array_keys( $arrmixProducts ) );
		$objCustomer 		= $objEntityManager->getRepository('BundlesUserBundle:Customer')->find( (int) $this->objSession->get( 'user/customer_id' ) );
		$intTotalSum 		= 0;
		$arrobjOrderItems 	= array();
		$intDiscount 		= 10;

		$objSfGenerateOrderService = new SfGenerateOrderService( $this->container );
		if( false == $objSfGenerateOrderService->checkProductAvailibiltyOrderItems( $products, $arrmixProducts ) ) {
			return array(
				'Order' => array(
					'message' => 'Sorry products are not available.',
					'error'	=> true
				)
			);
		}
		
		$objOrder->setCustomer( $objCustomer );
		$objOrder->setShippingDetails( implode( ' ', $arrmixOrderDetails['shipping_address'] ) );

		foreach( $products as $product ) {
			$intTotalSum += $arrmixProducts[$product->getId()] * $product->getListPrice() - ( ( (  $arrmixProducts[$product->getId()] * $product->getListPrice() ) * $product->getDiscount() ) / 100 );

			$product->setQuantityAvailable( ( int ) ( $product->getQuantityAvailable() - $arrmixProducts[$product->getId()] ) );
		}

		$objOrder->setAmount( ( float ) $intTotalSum );
		$objEntityManager->persist( $objOrder );

		foreach( $products as $product ) {
			$objOrderItem = new OrderItems();

			$objOrderItem->setProduct( $product );
			$objOrderItem->setPrice( ( float ) $product->getListPrice() );
			$objOrderItem->setQuantity( ( int ) $arrmixProducts[$product->getId()] );
			$objOrderItem->setDiscount( ( float ) $product->getDiscount() );
			$objOrderItem->setOrders( $objOrder );

			$arrobjOrderItems[] = $objOrderItem;
		}

		foreach( $arrobjOrderItems as $objOrderItem ) {
			$objEntityManager->persist( $objOrderItem );
		}
	
		$objEntityManager->flush();
		
		$strOrderId = $objSfGenerateOrderService->insertOrder( $objOrder->getId() );
		$objSfGenerateOrderService->insertOrderItems( $objOrder->getId(), $strOrderId, $products );
		
		$objSfGenerateOrderService->updateSalesforceProducts();
		
		return array(
			'Order' => array(
				'id' => $objOrder->getId()	
			),
			'status_message' => $objSfGenerateOrderService->getOutput()
		);
	}

	/**
	 * Get Order
	 * @Rest\Options("/order/customer" )
	 */
	public function optionsCutomerOrdersAction( Request $request ) {
		return array(1);
	}

	/**
	 * Get Order
	 * @Rest\Get("/order/customer")
	 */
	public function getCutomerOrdersAction( Request $request ) {
		 
		$arrmixOrders = $this->getDoctrine()->getManager()->getRepository('BundlesOrderBundle:Orders')->fetchDetailsByCustomerId( $this->objSession->get( 'user/customer_id' ) );
		return $arrmixOrders;
	}
}