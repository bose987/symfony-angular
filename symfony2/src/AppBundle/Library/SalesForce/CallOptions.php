<?php
namespace AppBundle\Library\SalesForce;


class CallOptions {
	public $client;
	public $defaultNamespace;

	public function __construct($client, $defaultNamespace=NULL) {
		$this->client = $client;
		$this->defaultNamespace = $defaultNamespace;
	}
}