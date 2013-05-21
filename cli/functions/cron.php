<?php

/*
 *	CodeRush
 *	CRON Dispatcher Functions
 */

// Parses the CRON URL
function parseURLCRON() {
	return array(
		'job'		=> isset($_GET['job']) ? trim($_GET['job']) : null,
		'meta'		=> isset($_GET['meta']) ? trim($_GET['meta']) : null
	);
}

// Returns the requested CRON list
function getCRON($cron_job) {
	$result_arr = array();

	// List all CRON?
	if(!$cron_job) {
		$available_files = scandir('./');

		foreach($available_files as $current_file) {
			if(preg_match('/^cron\.(.+)\.php$/', $current_file, $current_matches))
				array_push($result_arr, $current_matches[1]);
		}
	} else {
		array_push($result_arr, $cron_job);
	}

	return $result_arr;
}

?>