<?php

/*
 *	CodeRush
 *	CRON Dispatcher Functions
 */

// Parses the CRON URL
function parseURLCRON() {
	// INFO: /cron(/JOB(/META)?)?
	
	global $CONTEXT_ROUTE;
	
	// Result array
	$result = array(
		'job'		=> null,
		'meta'		=> null
	);
	
	// Populate array
	foreach($CONTEXT_ROUTE as $current_key => $current_value) {
		if(!$current_value)
			$current_value = null;
		
		switch($current_key) {
			// Root
			case 0:
				break;
			
			// Name
			case 1:
				$result['job'] = $current_value;
				
				break;
			
			// Meta
			default:
				if($result['meta'])
					$result['meta'] .= '/'.$current_value;
				else
					$result['meta'] = $current_value;
		}
	}
	
	return $result;
}

// Returns the requested CRON list
function getCRON($cron_job) {
	$result_arr = array();

	// List all CRON?
	if($cron_job == 'all') {
		$available_files = scandir('../cgi/routes');

		foreach($available_files as $current_file) {
			if(preg_match('/^cron\.(.+)\.php$/', $current_file, $current_matches))
				array_push($result_arr, $current_matches[1]);
		}
	} else {
		array_push($result_arr, $cron_job);
	}

	return $result_arr;
}

// Returns from where CRON task is invoked
function sourceCRON() {
	if((php_sapi_name() == 'cli') || empty($_SERVER['REMOTE_ADDR']))
		return 'cli';
	
	return 'cgi';
}

?>