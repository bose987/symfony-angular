<?php
namespace Bundles\ProductBundle\Controller;

use AppBundle\Controller\AppController;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class ProductsController extends AppController
{
	/**
	* @Rest\Options("/products" )
	*/
	public function optionsProductsAction() {
		return array(1);
	}
	
	/**
	* @Rest\Get("/products" )
	* @ApiDoc(
	*  resource=true,
	*  description="Product Details",
	* )
	* 
	*/
	
	public function getProductsAction( Request $request ) {

		$arrintproductIds = NULL;
		if( false == is_null( $strproductIds = $request->query->get('ids') ) ) {
			$arrintproductIds = json_decode( $strproductIds, true );
		}
		$objProductRepository = $this->getDoctrine()->getManager()->getRepository('BundlesProductBundle:Product');
		if( false == is_null( $arrintproductIds ) ) {
			$product = $objProductRepository->findById( $arrintproductIds );
		} else {
			$product = $objProductRepository->getProducts( 100 );
		}
		return $product;
	}
	
	
	/**
	* @Rest\Options("/category" )
	*/
	public function optionsCategoryAction( Request $request ) {
		return array(1);
	}
	 
	/**
	* @Rest\Get("/category" )
	* @ApiDoc(
	*  resource=true,
	*  description="Category Details",
	* )
	*/
	
	public function getCategoryAction() {
		 
		$em = $this->getDoctrine()->getManager();
	
		$category = $em->getRepository('BundlesProductBundle:Category')->getCategory(100);
	
		return $category;
	}
	
	/**
	 * Get Order
	 * @Rest\Get("/test" )
	 */
	public function getTestAction() {
		
		$arrmixOrderDetails = $this->getDoctrine()->getManager()->getRepository('BundlesOrderBundle:OrderItems')->fetchItems();
		return $arrmixOrderDetails;
	
	}
}