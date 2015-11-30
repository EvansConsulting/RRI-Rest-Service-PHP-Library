<?php
	use RRI\Rest\API\Service as Service;
	//This file is to autoload and bootstrap the TR Rest Services API PHP Library
	
	//Load Client Component
	require '../vendor/autoload.php';
	
	
	
	$trClient = new Service\Client('WMFP_WEB_APP','v1','TheNotSoSecretKey','https://www.tonyrobbins.com/api/rest/');
	
	//bootstrapping over
	