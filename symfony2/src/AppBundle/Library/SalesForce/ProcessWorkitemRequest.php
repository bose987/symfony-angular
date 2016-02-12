<?php
namespace AppBundle\Library\SalesForce;

use AppBundle\Library\SalesForce\ProcessRequest;

class ProcessWorkitemRequest extends ProcessRequest {
	public $action;
	public $workitemId;
}