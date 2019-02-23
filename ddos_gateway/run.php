<?php
$folder = dirname(__FILE__);
include("$folder/DdosGateway.php");

$gateway = new DdosGateway();
if(!$gateway->checkExistSession()){
	if($url = $gateway->handleSessionRequest()){
		header("location: $url");
		exit();
	}
	// if do not have session => render view
	$currentRequestUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$uuid = $gateway->saveSessionUuid();
	require($folder . "/view.php");
	exit();
}