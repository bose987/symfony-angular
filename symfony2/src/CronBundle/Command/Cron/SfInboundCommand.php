<?php
namespace CronBundle\Command\Cron;

use AppBundle\Service\SalesForce\SfInboundService;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class SfInboundCommand extends ContainerAwareCommand{
		
	protected function configure()
	{
		$this
		->setName('cron:sf:inbound')
		->setDescription('Sf Cron Inbound');
	}
	
	public function execute(InputInterface $input, OutputInterface $output )
	{

		try{
			$objSfInbound = new SfInboundService( $this->getContainer(), $output );
			$objSfInbound->run();
		} catch( \Exception $objException ){
			$this->output->writeln( $objException->getMessage() );
		}
	}
	
}