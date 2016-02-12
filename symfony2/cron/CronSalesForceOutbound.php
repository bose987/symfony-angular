<?php
	require_once( __DIR__ . '/CronSalesForceConfig.php' );
	require_once( __DIR__ . '/services/ServiceSalesForceOutbound.php' );
	
	$objServiceSalesForceOutbound = new ServiceSalesForceOutbound();
	$objServiceSalesForceOutbound->updateSalesforceProducts();
?>