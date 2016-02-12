<?php

namespace Bundles\OrderBundle\Controller;

use AppBundle\Controller\AppController;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;

use Bundles\OrderBundle\Entity\Payment;
use Bundles\OrderBundle\Entity\Orders;
use Omnipay\Omnipay;


class PaymentController extends AppController
{

	/**
	 * Option Payment
	 * @Rest\Options("/payment" )
	 */
	public function optionsMakePaymentAction( Request $request ) {
		return array(1);
	}

	/**
	 * Put Payment
	 * @Rest\POST("/payment" )
	 */
	public function getMakePaymentAction( Request $request ) {
		
		$objEntityManager 		= $this->getDoctrine()->getManager();
		$objGateway 			= Omnipay::create('Stripe');
		$strJson				= $request->getContent();
		$arrmixPaymentDetails 	= json_decode( $strJson, true );
		
		$order 					= $objEntityManager->getRepository('BundlesOrderBundle:Orders')->find( $arrmixPaymentDetails['order_id'] );
		$objCustomer 			= $objEntityManager->getRepository('BundlesUserBundle:Customer')->find( (int) $this->objSession->get( 'user/customer_id' ) );
		// Set secret key
		$objGateway->setApiKey('sk_test_EU7usBJNPLxb5c1r48LPaT6B');

		// Make Form data
        $formData = [
            'number' => $arrmixPaymentDetails['card_number'] . '',
            'expiryMonth' => $arrmixPaymentDetails['expiry_month'] . '',
            'expiryYear' => $arrmixPaymentDetails['expiry_year'] . '',
            'cvv' => $arrmixPaymentDetails['cvv'] . ''
        ];

        // Send purchase request
        $objResponse = $objGateway->purchase(
            [
                'amount' => $order->getAmount() . '.00',
                'currency' => 'USD',
                'card' => $formData
            ]
        )->send();

        // Process response
        if( $objResponse->isSuccessful() || $objResponse->isRedirect() ) {
//         if( true ) {
			$objPayment = new Payment();

			$objPayment->setOrders( $order );
			$objPayment->setCustomer( $objCustomer );
			$objPayment->setTransactionId( 'transaction_' . (int) $this->objSession->get( 'user/customer_id' ) );
			$objPayment->setIsSuccess( true );
			$objPayment->setPaymentStatus( 'Completed' );

			$objEntityManager->persist( $objPayment );
			$objEntityManager->flush();

			return array(
				'Payment' => array(
					'id' => $objPayment->getId(),
					'order_id' => $order->getId(),
					'status' => 'success'
				)
			);

        } else {
        	return array(
				'Payment' => array(
					'status' => 'failed',
					'order_id' => $order->getId()
				) 
			);
        }
	}
}