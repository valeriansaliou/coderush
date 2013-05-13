<?php

/*
 *	CodeRush
 *	Greet API
 */

// Initialize
$xml_output = $error_reason = null;
$error = false;

// Enough data?
if(($api_action == 'hello') || ($api_action == 'goodbye')) {
	if($api_action == 'hello') {
		$xml_output .= '<greet>Hello World!</greet>';
	} else if($api_action == 'goodbye') {
		$xml_output .= '<greet>Goodbye.</greet>';
	}
} else {
	$error = true;
	$error_reason = 'Bad Request';
	
	$api_action = 'error';
}

// Generate the response
$status_code = '1';
$status_message = 'Success';

if($error) {
	$status_code = '0';
	$status_message = 'Server Error';
	
	if($error_reason)
		$status_message = $error_reason;
}

$api_response = '<coderush xmlns="coderush:api:'.$api_name.':'.$api_action.'">';
	$api_response .= '<query id="'.htmlEntities($query_id, ENT_QUOTES).'">';
		$api_response .= '<status>'.htmlspecialchars($status_code).'</status>';
		$api_response .= '<message>'.htmlspecialchars($status_message).'</message>';
	$api_response .= '</query>';
	
	if($xml_output) {
		$api_response .= '<data>';
			$api_response .= $xml_output;
		$api_response .= '</data>';
	}
$api_response .= '</coderush>';

?>