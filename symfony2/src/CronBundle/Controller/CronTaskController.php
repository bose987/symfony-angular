<?php
namespace CronBundle\Controller;

use CronBundle\Entity\CronTask;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use AppBundle\Controller\AppController;

class CronTaskController extends AppController
{
	/**
	 * Get Test
	 * @Rest\Get("/cron/test")
	 */
	public function testAction()
	{
		$entity = new CronTask();
	
		$entity
		->setName('Saleforce Inbound')
		->setInterval(3600) // Run once every hour
		->setCommands(array(
			'cron:sf:inbound'
		));
	
		$em = $this->getDoctrine()->getManager();
		$em->persist($entity);
		$em->flush();
	
		return new Response('OK!');
	}
}