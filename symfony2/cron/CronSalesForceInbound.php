<?php
	require_once( __DIR__ . '/CronSalesForceConfig.php' );
	require_once( __DIR__ . '/services/ServiceSalesForceInbound.php' );
	
	$objServiceSalesForceInbound = new ServiceSalesForceInbound();
	$objServiceSalesForceInbound->insertProductCategories();
	$objServiceSalesForceInbound->syncCategories();
	$objServiceSalesForceInbound->insertProducts();
?>