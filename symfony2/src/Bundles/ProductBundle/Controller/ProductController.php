<?php

namespace Bundles\ProductBundle\Controller;

use AppBundle\Controller\AppController;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class ProductController extends AppController
{
	/**
	 * @Rest\Options("/product/{id}" )
	 */
	public function optionsProductAction( $id, Request $requst ) {
		return array(1);
	}
	
	/**
	 * Get Product
	 * @Rest\Get("/product/{id}" )
	 * @ApiDoc(
	 *  resource=true,
	 *  description="Product Details",
	 * )
	 */
	public function getProductAction( $id, Request $requst ) {
		$em = $this->getDoctrine()->getManager();
		
		$product = $em->getRepository('BundlesProductBundle:Product')->getDetails( $id );
		
		return $product;
	}
	
	/**
	 * Get Product
	 * @Rest\Options("/category/{id}/products" )
	 */
	public function optionsCategoryProductsAction( $id, Request $requst ) {
		return array(1);
	}
	
	/**
	 * Get Product
	 * @Rest\Get("/category/{id}/products" )
	 * @ApiDoc(
	 *  resource=true,
	 *  description="Product By Category",
	 * )
	 */
	public function getCategoryProductsAction( $id, Request $requst ) {
		$em = $this->getDoctrine()->getManager();
	
		$product = $em->getRepository('BundlesProductBundle:Product')->getProductsByCategoryId($id);
	
		return $product;
	}
	
	

}