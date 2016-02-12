<?php
namespace AppBundle\Library\SalesForce;
class AllowFieldTruncationHeader {
	public $allowFieldTruncation;

	public function __construct($allowFieldTruncation) {
		$this->allowFieldTruncation = $allowFieldTruncation;
	}
}