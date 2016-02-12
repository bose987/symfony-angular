<?php
namespace AppBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\DependencyInjection\Container;

class ControllerListener
{
	/**
	 * @var Container
	 */
	private $container;
	
	/**
	 * Constructor
	 *
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
		$this->container = $container;
	}
	
	public function onKernelController(FilterControllerEvent $event)
	{
		list( $objController, $strActionName ) = $event->getController();
		
		if( !is_object( $objController ) ) {
			return;
		}
		
		$request = $event->getRequest();
		
		if( true == method_exists( $objController,'create') ) {
			$objController->create();
		}
		
		if( true == method_exists( $objController,'initialize') ) {
			$objController->initialize();
		}
			
	}
}