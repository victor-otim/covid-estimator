<?php
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/xml; charset=UTF-8");
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
	
	function array_to_xml( $data, &$xml_data ) {
		
		foreach( $data as $key => $value ) {
			if( is_array($value) ) {
				if( is_numeric($key) ){
					$key = 'item'.$key; //dealing with <0/>..<n/> issues
				}
				$subnode = $xml_data->addChild($key);
				array_to_xml($value, $subnode);
			} else {
				$xml_data->addChild("$key",htmlspecialchars("$value"));
			}
		 }
	}
	
	$xml = new SimpleXMLElement('<?xml version="1.0"?><root></root>');
	
	if(!empty($_POST)):
		
		$estimates = covid19ImpactEstimator($_POST);
		
		$response = 200;
		
		array_to_xml($estimates, $xml);
		
	else:
	
		$response = 400;
		
		array_to_xml(array('ERROR'=>'No data received'), $xml);
		
	endif;
	
	http_response_code($response);
	
	print $xml->asXML();
	
	
	$responseTime = microtime(true) - $startTime;
	
	# log response
	$logStr = $httpMethod ."\t\t". $requestPath ."\t\t". $response ."\t\t". $responseTime .' ms'. PHP_EOL;
	
	file_put_contents(BASEPATH .'api/v1/on-covid-19/logs/log.txt', $logStr, FILE_APPEND);