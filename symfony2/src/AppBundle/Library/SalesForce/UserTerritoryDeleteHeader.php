<?php
namespace AppBundle\Library\SalesForce;
class UserTerritoryDeleteHeader {
	public $transferToUserId;

	public function __construct($transferToUserId) {
		$this->transferToUserId = $transferToUserId;
	}
}