<?php

/*
 *	CodeRush
 *	Deploy Functions
 */

// Increment revision number
function revisionDeploy() {
	try {
		// Read common config file
		$config_common_path = '';
		$config_common = file_get_contents($config_common_path);

		if(!$config_common)
			return 0;

		// Increment revision
		$config_common = preg_replace("/(('|\")revision('|\")(\s+)?=>(\s+)?)([0-9]+)/e", '"$1".("$6" + 1)', $config_common);

		// Write common config file
		file_put_contents($config_common_path, $config_common);

		return 1;
	} catch(exception $e) {
		return 0;
	}
}

// Empty static cache
function cacheStaticDeploy() {
	try {
		// List the caches to remove
		$cache_list = scandir('../../cache/static');

		// Proceed removal
		foreach($cache_list as $current_key => $current_cache) {
			// Not a cache file?
			if(!preg_match('/.cache$/', $current_cache)) {
				unset($cache_list[$current_key]);

				continue;
			}

			// Remove cache file on disk
			unlink($current_cache);
		}

		// Nothing to do?
		if(count($cache_list) == 0)
			return 2;
		
		return 1;
	} catch(exception $e) {
		return 0;
	}
}

?>