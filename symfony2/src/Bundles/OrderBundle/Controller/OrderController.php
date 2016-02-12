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
		
		set_time_limit(60);

		$objEntityManager 	= $this->getDoctrine()->getManager();
		$objOrder 			= new Orders();
		$strJson			= $request->getContent();
		$arrmixOrderDetails = json_decode( $strJson, true );
		$arrmixProducts 	= array();
		$arrmixTempProducts	= $arrmixOrderDetails['products'];

		$objSfGenerateOrderService = new SfGenerateOrderService( $this->container );


		foreach( $arrmixTempProducts as $arrmixproduct ) {
			$arrmixProducts[$arrmixproduct['id']] = $arrmixproduct['quantity'];
		}
		
		$products 			= $objEntityManager->getRepository('BundlesProductBundle:Product')->findById( array_keys( $arrmixProducts ) );
		$objCustomer 		= $objEntityManager->getRepository('BundlesUserBundle:Customer')->find( (int) $this->objSession->get( 'user/customer_id' ) );

		$intTotalSum 		= 0;
		$arrobjOrderItems 	= array();
		$intDiscount 		= 10;

		$objOrder->setCustomer( $objCustomer );
		$objOrder->setShippingDetails( implode( ' ', $arrmixOrderDetails['shipping_address'] ) );

		foreach( $products as $product ) {
			$intTotalSum += $arrmixProducts[$product->getId()] * $product->getListPrice() - ( ( (  $arrmixProducts[$product->getId()] * $product->getListPrice() ) * $product->getDiscount() ) / 100 );

			$product->setQuantityAvailable( $product->getQuantityAvailable() - $arrmixProducts[$product->getId()] );
		}

		$objOrder->setAmount( $intTotalSum );

		$objEntityManager->persist( $objOrder );

		foreach( $products as $product ) {
			$objOrderItem = new OrderItems();

			$objOrderItem->setProduct( $product );
			$objOrderItem->setPrice( $product->getListPrice() );
			$objOrderItem->setQuantity( $arrmixProducts[$product->getId()] );
			$objOrderItem->setDiscount( $product->getDiscount() );
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
			)
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