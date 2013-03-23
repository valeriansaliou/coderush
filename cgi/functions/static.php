<?php

/*
 *	CodeRush
 *	Smart Static File Get Functions
 */

// Parses static URL
function parseStatic() {
	// INFO: /static/LANG/REVISION/FILE_TYPE/(FILE_PATH|FILE_GROUP)
	
	global $CONTEXT_ROUTE;
	
	// Import required functions
	require_once('../cgi/functions/file.php');
	
	// Result array
	$result = array(
		'lang'	=> null,
		'hash'	=> null,
		'type'	=> null,
		'file'	=> null
	);
	
	// Populate array
	foreach($CONTEXT_ROUTE as $current_key => $current_value) {
		if(!$current_value)
			$current_value = null;
		
		switch($current_key) {
			// Root
			case 0:
				break;
			
			// Lang
			case 1:
				if($current_value && is_dir('../i18n/'.$current_value))
					$result['lang'] = $current_value;
				else
					$result['lang'] = 'en';
				
				break;
			
			// Hash
			case 2:
				// Must be an integer
				if(is_numeric($current_value))
					$result['hash'] = $current_value;
				
				break;
			
			// Type
			case 3:
				$result['type'] = $current_value;
				
				break;
			
			// Meta
			default:
				if($result['file'])
					$result['file'] .= '/'.$current_value;
				else
					$result['file'] = $current_value;
		}
	}
	
	return $result;
}

// Generates a cache path
function pathCacheStatic($hash) {
	return '../cache/static/'.$hash.'.cache';
}

// The function to check the cache presence
function hasCacheStatic($hash) {
	return file_exists(pathCacheStatic($hash));
}

// Reads the cached content
function readCacheStatic($hash) {
	return file_get_contents(pathCacheStatic($hash));
}

// Generates a cache file
function genCacheStatic($string, $mode, $cache) {
	if(!$mode) {
		$cache_dir = '../cache/static';
		$file_put = $cache_dir.'/'.$cache.'.cache';
		
		// Cache not yet wrote
		if(is_dir($cache_dir) && !file_exists($file_put))
			file_put_contents($file_put, $string, LOCK_EX);
	}
}

// Removes the BOM from a string
function rmBOMStatic($string) { 
	if(substr($string, 0, 3) == pack('CCC', 0xef, 0xbb, 0xbf))
		$string = substr($string, 3);
	
	return $string; 
}

// Compress the CSS
function compressCSSStatic($buffer) {
	// We remove the comments
	$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
	
	// We remove the useless spaces
	$buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '	 ', '	 '), '', $buffer);
	
	// We remove the last useless spaces
	$buffer = str_replace(array(' { ',' {','{ '), '{', $buffer);
	$buffer = str_replace(array(' } ',' }','} '), '}', $buffer);
	$buffer = str_replace(array(' : ',' :',': '), ':', $buffer);
 	
	return $buffer;
}

// Replaces classical path to get.php paths
function pathStatic($string, $type, $lang) {
	// Globals
	global $CONTEXT_REVISION;
	
	// Get static server
	$statics = statics();
	
	// Replace path to static
	if($type == 'javascripts') {
		// Links to JS (must have a lang parameter)
		$string = preg_replace('/\/(static)\/int\/revision\/(javascripts)\//', $statics.'/'.$lang.'/'.$CONTEXT_REVISION.'/$2/', $string);
	}
	
	// Other "normal" links (no lang parameter)
	$string = preg_replace('/\/(static)\/(int)\/revision\/((?!javascripts)[^\/]+)+\//', $statics.'/$2/'.$CONTEXT_REVISION.'/$3/', $string);
	
	return $string;
}

// Sets the good translation to a JS file
function translateStatic($string) {
	return preg_replace('/_e\("([^\"\"]+)"\)/e', "'_e(\"'.addslashes(T_gettext(stripslashes('$1'))).'\")'", $string);
}

// Sets the good configuration to JS
function configurationStatic($string) {
	// Globals
	global $CONFIG_COMMON;
	
	// Configuration array
	$array = array(
		// Configuration strings
		'string' => array(
			// Common configuration
			'CONFIG_DEV_NOPROD'	=> ($CONFIG_COMMON['dev']['noprod'] ? '1' : '0')
		),
		
		// Configuration objects
		'object' => array(
			// Object vars here (if any)
		)
	);
	
	// Apply it!
	foreach($array as $array_key => $array_value) {
		// Add quotes to strings
		if($array_key == 'string') {
			foreach($array_value as $sub_array_key => $sub_array_value)
				$string = preg_replace('/var '.$sub_array_key.'((\s+)?=(\s+)?)null;/', 'var '.$sub_array_key.'$1\''.addslashes($sub_array_value).'\';', $string);
		} else if($array_key == 'object') {
			foreach($array_value as $sub_array_key => $sub_array_value)
				$string = preg_replace('/var '.$sub_array_key.'((\s+)?=(\s+)?)null;/', 'var '.$sub_array_key.'$1'.$sub_array_value.';', $string);
		}
	}
	
	return $string;
}

// Get static directory file list
function listStatic($dir, $sub_statics) {
	// Initialize
	$result_list = array();
	
	// No need to proceed?
	if(!is_dir($dir))
		return $result_list;
	
	// Regex in use
	$legacy_regex = '/^([^\.]+)\.legacy\.(js|css)$/';
	$normal_regex = '/^(.+)\.(js|css)$/';
	
	$match_regex = ($sub_statics == 'legacy') ? $legacy_regex : $normal_regex;
	$not_regex = ($sub_statics != 'legacy') ? $legacy_regex : null;
	
	// Scan the directory
	$static_files = scandir($dir);
	
	foreach($static_files as $current_subfile) {
		// File is okay for our request?
		if($current_subfile && ($current_subfile != '.') && ($current_subfile != '..') && preg_match($match_regex, $current_subfile) && !preg_match($not_regex, $current_subfile)) {
			// Remove extension
			$current_parse = nameFile($current_subfile);
			$current_name = $current_parse['name'];
			
			// Push it
			array_push($result_list, $current_name);
		}
	}
	
	return $result_list;
}

?>