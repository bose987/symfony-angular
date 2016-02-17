<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
	public function registerBundles()
	{
		$bundles = array(
// 			Symfony
			new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
			new Symfony\Bundle\SecurityBundle\SecurityBundle(),
			new Symfony\Bundle\TwigBundle\TwigBundle(),
			new Symfony\Bundle\MonologBundle\MonologBundle(),
			new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),

//			Doctrine
			new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),

			new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
			new Sensio\Bundle\DistributionBundle\SensioDistributionBundle(),
			new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle(),

			new JMS\SerializerBundle\JMSSerializerBundle(),

			new FOS\RestBundle\FOSRestBundle(),

			new Nelmio\ApiDocBundle\NelmioApiDocBundle(),

			new Bazinga\Bundle\HateoasBundle\BazingaHateoasBundle(),
			new Bazinga\Bundle\RestExtraBundle\BazingaRestExtraBundle(),

			new Hautelook\TemplatedUriBundle\HautelookTemplatedUriBundle(),
			
// 			new Omnipay\Omnipay(),
// 			User Bundles
			new Bundles\UserBundle\BundlesUserBundle(),
			new Bundles\ProductBundle\BundlesProductBundle(),
			new Bundles\SecurityBundle\BundlesSecurityBundle(),
			new Bundles\OrderBundle\BundlesOrderBundle(),
			new CronBundle\CronBundle(),
			new AppBundle\AppBundle(),
		
		
		);

		if (in_array($this->getEnvironment(), array('dev', 'test', 'prod'))) {
			$bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
		}


		return $bundles;
	}

	public function registerContainerConfiguration(LoaderInterface $loader)
	{
		$loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
	}
}
