<?php
namespace CronBundle\Command\Cron;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Service\SalesForce\SfOutboundService;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class SfOutboundCommand extends ContainerAwareCommand{
		
	protected function configure()
	{
		$this
		->setName('cron:sf:outbound')
		->setDescription('Sf Cron Outbound');
	}
	
	public function execute(InputInterface $input, OutputInterface $output )
	{

		try{
			$objSfOutbound = new SfOutboundService( $this->getContainer(), $output );
			$objSfOutbound->run();
		} catch( \Exception $objException ){
			
		}
	}
}