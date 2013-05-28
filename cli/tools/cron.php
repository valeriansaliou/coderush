#!/usr/bin/env php

<?php

/*
 *	CodeRush
 *	CRON Dispatcher
 */

// Change current working dir
chdir(dirname(__FILE__));

// Include functions
require_once('../functions/common.php');
require_once('../functions/cron.php');

// Don't allow non-CLI requests
if(caller() != 'cli')
	exit('Command-line service. Please call me from your shell.');

// Parse URL
$cron_url = parseURLCRON();

$cron_job = $cron_url['job'];
$cron_meta = $cron_url['meta'];

// Markers
$cron_count_executed = $cron_count_success = $cron_count_fail = 0;

// Launch
print('[cron] Starting.'."\n");

// Include CRONS
foreach(getCRON($cron_job) as $current_cron) {
	// Empty value?
	if(!$current_cron)
		continue;

	// Launch CRON
	print("\n");
	print('[cron:'.$current_cron.'] Executing...'."\n");

	// Current CRON vars
	$current_cron_path = $current_cron ? './cron.'.$current_cron.'.php' : null;

	// Include CRON?
	if(file_exists($current_cron_path)) {
		// Current CRON results
		$cron_result_code = '1';
		$cron_result_message = 'Success';

		// Execute current CRON
		include($current_cron_path);

		// Read CRON results
		if($cron_result_code == '1') {
			print('[cron:'.$current_cron.'] Executed ('.$cron_result_message.')'."\n");
			$cron_count_success++;
		} else {
			print('[cron:'.$current_cron.'] Error ('.$cron_result_message.')'."\n");
			$cron_count_fail++;
		}

		// Flush current CRON results
		unset($cron_result_code);
		unset($cron_result_message);
	} else {
		print('[cron:'.$current_cron.'] Not Found (Skipped)'."\n");
		$cron_count_fail++;
	}

	print('[cron:'.$current_cron.'] Done.'."\n");
	print("\n");

	$cron_count_executed++;
}

// Any response?
if(count($cron_count_executed)) {
	print('[cron] Executed '.$cron_count_executed.' task'.(($cron_count_executed != 1) ? 's' : null).'. '.$cron_count_success.' successful, '.$cron_count_fail.' failed.'."\n");
} else {
	print('[cron] No Response From CRON Dispatcher.'."\n");
}

print('[cron] Done.'."\n");

exit;

?>