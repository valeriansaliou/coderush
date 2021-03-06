<?php

/*
 *	CodeRush
 *	Cleanup CRON
 */

// Cleanup static caches
try {
	// List the caches to remove
	$cache_path = '../../cache/static';
	$cache_list = scandir($cache_path);

	// Proceed removal
	foreach($cache_list as $current_key => $current_cache) {
		// Not a cache file?
		if(!preg_match('/.cache$/', $current_cache)) {
			unset($cache_list[$current_key]);

			continue;
		}

		// Remove cache file on disk
		unlink($cache_path.'/'.$current_cache);
	}

	$cache_count = count($cache_list);

	// Output message
	if($cache_count == 0)
		$cron_result_message = 'Already Clean';
	else if($cache_count == 1)
		$cron_result_message = '1 Cache Removed';
	else
		$cron_result_message = $cache_count.' Caches Removed';
} catch(Exception $e) {
	$cron_result_code = '0';
	$cron_result_message = $e;
}

?>