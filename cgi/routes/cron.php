<?php

/*
 *	CodeRush
 *	CRON Dispatcher
 */

// Include functions
require_once('../cgi/functions/cron.php');

// Parse URL
$cron_url = parseURLCRON();

$cron_job = $cron_url['job'];
$cron_meta = $cron_url['meta'];

// CRON replies storage
$cron_reply = array();

// Include CRONS
foreach(getCRON($cron_job) as $current_cron) {
	// Empty value?
	if(!$current_cron)
		continue;

	// Current CRON default reply
	$cron_reply[$current_cron] = array(
		'ns' 		=> $current_cron,
		'code'		=> '0',
		'message'	=> 'CRON Service Error'
	);

	// Current CRON vars
	$current_cron_path = $current_cron ? '../cgi/routes/cron.'.$current_cron.'.php' : null;

	// Include CRON?
	if(file_exists($current_cron_path)) {
		// Current CRON results
		$cron_result_code = '1';
		$cron_result_message = 'Success';

		// Execute current CRON
		include($current_cron_path);

		// Push current CRON vars to global result array
		$cron_reply[$current_cron]['code'] = $cron_result_code;
		$cron_reply[$current_cron]['message'] = $cron_result_message;

		// Flush current CRON results
		unset($cron_result_code);
		unset($cron_result_message);
	} else {
		$cron_reply[$current_cron]['message'] = 'CRON Not Found';
	}
}

// Build CRON Dispatcher response
$cron_response = null;

foreach($cron_reply as $current_reply) {
	$cron_response .= '<cron ns="'.htmlEntities($current_reply['ns'], ENT_QUOTES).'" code="'.htmlEntities($current_reply['code'], ENT_QUOTES).'" message="'.htmlEntities($current_reply['message'], ENT_QUOTES).'" />';
}

// Any response?
if($cron_response) {
	header('Content-Type: text/xml');

	$cron_final_response = '<coderush xmlns="coderush:cron:'.$cron_job.'">';
		$cron_final_response .= $cron_response;
	$cron_final_response .= '</coderush>';

	echo($cron_final_response);
} else {
	echo('No Response From CRON Dispatcher');
}

?>