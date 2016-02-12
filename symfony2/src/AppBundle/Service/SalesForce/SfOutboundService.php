<?php
namespace AppBundle\Service\SalesForce;

use Symfony\Component\Console\Output\OutputInterface;
use CronBundle\Service\SalesForce\AbstractSfService;

class SfOutboundService extends AbstractSfService {
	
	public function __construct( $objContaner, OutputInterface $output ) {
		parent::__construct( $objContaner, $output );
	}
	
	public function run() {
		echo 'test';
	}
	
}
	