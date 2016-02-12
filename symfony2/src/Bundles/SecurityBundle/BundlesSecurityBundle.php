<?php

namespace Bundles\SecurityBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Bundles\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactory;

class BundlesSecurityBundle extends Bundle
{
	public function build(ContainerBuilder $container)
	{
		parent::build($container);
	
		$extension = $container->getExtension('security');
		$extension->addSecurityListenerFactory( new SecurityFactory() );
	}
}
