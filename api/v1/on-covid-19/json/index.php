<?php
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Methods: POST");
	header("Access-Control-Max-Age: 3600");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
	
	#include_once($_SERVER['DOCUMENT_ROOT'] .'/covid_estimator/covid-19-estimator-php/src/estimator.php');
		
	include_once($_SERVER['DOCUMENT_ROOT'] .'/src/estimator.php');
	
	$httpMethod = $_SERVER['REQUEST_METHOD'];
	
	$requestPath = $_SERVER['REQUEST_URI'];
	
	$response = '';
	
	$responseTime = '';
	
	$startTime = microtime(true);
			
	if(!empty($_POST)):
		
		$estimates = covid19ImpactEstimator($_POST);
		
		$response = 200;
		
	else:
		
		$estimates = array('ERROR'=>'No data received');		
		
		$response = 400;
		
	endif;
	
	http_response_code($response);
	
	print json_encode($estimates);
	
		
	$responseTime = microtime(true) - $startTime;
	
	# log response
	$logStr = $httpMethod ."\t\t". $requestPath ."\t\t". $response ."\t\t". $responseTime .' ms'. PHP_EOL;
	
	file_put_contents(BASEPATH .'api/v1/on-covid-19/logs/log.txt', $logStr, FILE_APPEND);