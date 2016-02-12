<?php
namespace Bundles\ProductBundle\Controller;

/*
 * Not in use
 */

use AppBundle\Controller\AppController;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;

class CartController extends AppController
{

	/**
	 * Get Cart Items
	 * @Rest\Get("/cart/{id}" )
	 */
	function showAction( $id, Request $request ) {
		return array( 
			'cart_id' => $id,
			'items' => $request->getSession()->get('cart_'. $id )
		);
	}
	
	/**
	 * Add Cart Items
	 * @Rest\Post("/cart/{id}/items" )
	 */

	function postItemsAction( $id, Request $request ) {
		
		$items = $request->query->get('items');
		$items = json_decode( $items, true );
		$rekeyedItems = array();
		foreach( $items as $item ) {
			$rekeyedItems[$item['id']] = $item; 
		}

		$request->getSession()->set('cart_' . $id, $rekeyedItems );
		return array( 'success', true );
	}
	
	/**
	 * Delete Cart Items
	 * @Rest\Delete("/cart/{id}/items/{itemId}" )
	 */
	
	function deleteItemAction( $id, $itemId, Request $request ) {
		$arrmixCartData = $request->getSession()->get('cart_'. $id );
		unset( $arrmixCartData[$itemId] );
		$request->getSession()->set('cart_' . $id, $arrmixCartData );;
		return array( 
			'cart_id' => $id,
			'items' => $request->getSession()->get('cart_'. $id )
		);
	}
}