<?php

/*
 *	CodeRush
 *	API Dispatcher
 */

// Include functions
require_once('../cgi/functions/api.php');

// Parse URL
$api_url = parseURLAPI();

$api_name = $api_url['name'];
$api_action = $api_url['action'];
$api_meta = $api_url['meta'];

// API globals
$api_path = $api_name ? '../cgi/routes/api.'.$api_name.'.php' : null;
$api_response = null;

// Any API name?
if($api_name) {
	// Get query ID
	$query_id = isset($_POST['id']) ? trim($_POST['id']) : 'none';
	
	// Include API if existing
	if(!$api_path || !file_exists($api_path)) {
		$api_response = 'API Not Found';
	} else {
		header('Content-Type: text/xml');
		
		include($api_path);
	}
} else {
	// Error response
	if(!$api_name) {
		$api_response = 'API URL Malformed';
	} else {
		$api_response = 'API Error';
	}
}

echo($api_response ? $api_response : 'No Response From API Router');

?>