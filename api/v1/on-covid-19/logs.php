<?php
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: text/plain; charset=UTF-8");
	header("Access-Control-Allow-Methods: POST");
	header("Access-Control-Max-Age: 3600");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
	
	#include_once($_SERVER['DOCUMENT_ROOT'] .'/covid-estimator/src/estimator.php');
	
	include_once($_SERVER['DOCUMENT_ROOT'] .'/src/estimator.php');
	
	$handle = fopen(BASEPATH .'api/v1/on-covid-19/log.txt', "r");
	
	$logText = '';
	
	if($handle):
		
		while (($line = fgets($handle)) !== false):
		
			$logText .= $line;
			
		endwhile;
	
		fclose($handle);
		
		print $logText;		
		
		http_response_code(200);
		
	else:
		
		$response = 400;
		
		print 'Error loading file';		
	
	endif;