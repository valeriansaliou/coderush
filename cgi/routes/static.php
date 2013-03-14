<?php

/*
 *	CodeRush
 *	Smart Static File Get
 */

// Include functions
require_once('../cgi/functions/static.php');

// Cache headers
if($CONFIG_COMMON['dev']['nocache']) {
	header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
} else {
	$expires = 31536000;
	header('Cache-Control: maxage='.$expires);
	header('Expires: '.gmdate('D, d M Y H:i:s', (time() + $expires)).' GMT');
}

// Parse URL
$static_url = parseStatic();

$lang = $static_url['lang'];
$revision = $static_url['hash'];
$type = ($static_url['type'] && is_dir('../static/'.$static_url['type'])) ? $static_url['type'] : null;
$file = $static_url['file'];

// We check if the data was submitted
if($revision && $file && $type) {
	// We define some stuffs
	$dir = '../static/'.$type.'/';
	$path = $dir.$file;
	$parse_filename = nameFile($file);
	$continue = true;
	
	// Read request headers
	$request_headers = function_exists('getallheaders') ? getallheaders() : array();
	$if_modified_since = isset($request_headers['If-Modified-Since']) ? trim($request_headers['If-Modified-Since']) : null;
	$if_modified_since = $if_modified_since ? strtotime($if_modified_since) : null;
	$if_none_match = isset($request_headers['If-None-Match']) ? trim($request_headers['If-None-Match']) : null;

	// JS and CSS special stuffs
	if(($type == 'stylesheets') || ($type == 'javascripts')) {
		// Check filename is '/filename' or '/legacy/filename'
		if(preg_match('/^legacy\/(.+)/', $file)) {
			// Remove that legacy thing
			$file = preg_replace('/^legacy\/(.+)/', '$1', $file);
			
			// Store legacy marker
			$sub_statics = 'legacy';
		} else if(preg_match('/^([^\/]+)$/', $file)) {
			$sub_statics = 'main';
		} else {
			$continue = false;
		}
		
		// Filter filename (if extension defined & valid)
		$file = $parse_filename['name'];
		
		// Compression var
		if($CONFIG_COMMON['compress']['files'])
			$cache_encoding = 'deflate';
		else
			$cache_encoding = 'plain';
		
		// Get the vars
		$cache_hash = md5($path.$CONTEXT_REVISION).'_'.$cache_encoding;
		
		// Check if the browser supports DEFLATE
		$deflate_support = false;
		
		if(isset($_SERVER['HTTP_ACCEPT_ENCODING']) && substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'deflate') && $CONFIG_COMMON['compress']['files'] && !$CONFIG_COMMON['dev']['nocache'])
			$deflate_support = true;
		
		// Internationalization
		$locale = ($type == 'javascripts') ? $lang : '';
		$cache_lang = $locale ? $cache_hash.'_'.$locale : $cache_hash;
		
		// Get file list
		if(file_exists('../cgi/statics/'.$file.'.php'))
			include('../cgi/statics/'.$file.'.php');
		else
			$CURRENT_STATICS = array();
		
		// No need to loop? (already cached)
		$no_loop = !$CONFIG_COMMON['dev']['nocache'] && (($type == 'stylesheets') || ($type == 'javascripts')) && hasCacheStatic($cache_lang);
		
		// Check if all files exist
		if($continue && !$no_loop) {
			if(empty($CURRENT_STATICS) || !isset($CURRENT_STATICS[$sub_statics]) || !isset($CURRENT_STATICS[$sub_statics][$type])) {
				$continue = false;
			} else {
				$check_statics_sub = isset($CURRENT_STATICS[$sub_statics][$type]) ? $CURRENT_STATICS[$sub_statics][$type] : array();
				$check_statics_ext = ($type == 'javascripts') ? 'js': 'css';
				
				// Check each sub-folder
				foreach($check_statics_sub as $check_static_folder => $check_static_files) {
					if(!$continue)
						break;
					
					// Replace jockers with dynamically-generated file list
					if($check_static_files == '*') {
						$CURRENT_STATICS[$sub_statics][$type][$check_static_folder] = listStatic($dir.$check_static_folder, $sub_statics);
						
						continue;
					}
					
					// Check each sub-file
					if(is_array($check_static_files)) {
						foreach($check_static_files as $check_static_file) {
							// Not found
							if(!file_exists($dir.$check_static_folder.'/'.$check_static_file.'.'.$check_statics_ext)) {
								$continue = false;
								
								break;
							}
						}
						
						continue;
					}
					
					// Remove invalid array entries
					unset($CURRENT_STATICS[$sub_statics][$type][$check_static_folder]);
				}
			}
		}
	} else {
		if(!file_exists($path))
			$continue = false;
	}
	
	// We can read the file(s)
	if($continue) {
		// We set up a known MIME type (and some other headers)
		if(($type == 'stylesheets') || ($type == 'javascripts')) {
			// DEFLATE header
			if($deflate_support)
				header('Content-Encoding: deflate');
			
			// MIME header
			if($type == 'stylesheets')
				header('Content-Type: text/css; charset=utf-8');
			else if($type == 'javascripts')
				header('Content-Type: application/javascript; charset=utf-8');
		} else {
			// We get the file MIME type
			$mime = $parse_filename['ext'];
			
			switch($mime) {
				case 'png':
					header('Content-Type: image/png');
					break;
				
				case 'gif':
					header('Content-Type: image/gif');
					break;
				
				case 'jpg':
				case 'jpeg':
					header('Content-Type: image/jpeg');
					break;
				
				case 'woff':
					header('Content-Type: application/x-font-woff');
					break;
				
				case 'ttf':
					header('Content-Type: application/x-font-ttf');
					break;
				
				case 'svg':
					header('Content-Type: image/svg+xml');
					break;
				
				case 'eot':
					header('Content-Type: application/vnd.ms-fontobject');
					break;
				
				case 'oga':
				case 'ogg':
					header('Content-Type: audio/ogg');
					break;
				
				case 'mp3':
					header('Content-Type: audio/mpeg');
					break;
				
				case 'm4a':
					header('Content-Type: audio/mp4a-latm');
					break;
				
				default:
					header('Content-Type: '.mimeFile($path));
			}
		}
		
		// Read the text files (pack them)
		if(($type == 'stylesheets') || ($type == 'javascripts')) {
			// Storage vars
			$last_modified = $output_data = null;

			// Any cache file?
			if(hasCacheStatic($cache_lang) && !$CONFIG_COMMON['dev']['nocache']) {
				$last_modified = filemtime(pathCacheStatic($cache_lang));
				$cache_read = readCacheStatic($cache_lang);
				
				if($deflate_support || !$CONFIG_COMMON['compress']['files'])
					$output_data = $cache_read;
				else
					$output_data = gzinflate($cache_read);
			} else {
				// Read cache reference
				if(hasCacheStatic($cache_hash) && !$CONFIG_COMMON['dev']['nocache']) {
					// Read the reference
					$last_modified = filemtime(pathCacheStatic($cache_hash));
					$cache_reference = readCacheStatic($cache_hash);
					
					// Filter the cache reference
					if($CONFIG_COMMON['compress']['files'])
						$output = gzinflate($cache_reference);
					else
						$output = $cache_reference;
				} else {
					// Last modified date is now
					$last_modified = time();

					// Initialize the loop
					$looped = '';
					
					// Add the content of the current file
					$statics_sub = isset($CURRENT_STATICS[$sub_statics][$type]) ? $CURRENT_STATICS[$sub_statics][$type] : array();
					$statics_ext = ($type == 'javascripts') ? 'js': 'css';
					
					// Read each sub-folder
					foreach($statics_sub as $static_folder => $static_files) {
						// Check each sub-file
						if(is_array($static_files)) {
							// Count the number of static files
							$static_count = count($static_files);
							
							// Append each sub-file
							foreach($static_files as $static_index => $static_file) {
								// Append file debug header (debug mode only)
								if($CONFIG_COMMON['dev']['noprod']) {
									$looped .= "/*\n";
									$looped .= " * ----- ".$static_file.".".$statics_ext." -----\n";
									$looped .= " */";
									$looped .= "\n\n";
								}
								
								// Append file content
								$looped .= rmBOMStatic(file_get_contents($dir.$static_folder.'/'.$static_file.'.'.$statics_ext));
								
								// Append file margin (for next file)
								if($static_index < ($static_count - 1))
									$looped .= "\n\n\n\n";
							}
						}
					}
					
					// Filter the CSS
					if($type == 'stylesheets')
						$looped = pathStatic($looped, $type, $lang);
					
					// Optimize the code rendering
					if($type == 'stylesheets') {
						// Can minify the CSS
						if($CONFIG_COMMON['compress']['files'] && !$CONFIG_COMMON['dev']['nocache'])
							$output = compressCSSStatic($looped);
						else
							$output = $looped;
					} else {
						// Can minify the JS (sloooooow!)
						if($CONFIG_COMMON['compress']['files'] && !$CONFIG_COMMON['dev']['nocache']) {
							require_once('../cgi/libs/jsmin.php');
							
							$output = JSMin::minify($looped);
						} else {
							$output = $looped;
						}
					}
					
					// Generate the reference cache
					if($CONFIG_COMMON['compress']['files'])
						$final = gzdeflate($output, 9);
					else
						$final = $output;
					
					// Write it!
					genCacheStatic($final, $CONFIG_COMMON['dev']['nocache'], $cache_hash);
				}
				
				// Filter the JS
				if($type == 'javascripts') {
					// Set the Get API paths
					$output = pathStatic($output, $type, $locale);
					
					// Translate the JS script
					$output = translateStatic($output);
					
					// Generate the cache
					if($CONFIG_COMMON['compress']['files'])
						$final = gzdeflate($output, 9);
					else
						$final = $output;
					
					// Write it!
					genCacheStatic($final, $CONFIG_COMMON['dev']['nocache'], $cache_lang);
				}
				
				// Output a well-encoded string
				if($deflate_support || !$CONFIG_COMMON['compress']['files'])
					$output_data = $final;
				else
					$output_data = gzinflate($final);
			}

			// Any data to output?
			if($output_data) {
				// Process re-usable HTTP headers values
				$http_etag = md5($output_data);

				// File HTTP headers
				if(!$CONFIG_COMMON['dev']['noprod']) {
					header('ETag: '.$http_etag);
					header('Last-Modified: '.gmdate('D, d M Y H:i:s', $last_modified).' GMT');
				}

				// Check browser cache
				if(!$CONFIG_COMMON['dev']['noprod'] && (($http_etag && ($http_etag == $if_none_match)) || ($last_modified && ($last_modified <= $if_modified_since)))) {
					// Use browser cache
					header('Status: 304 Not Modified', true, 304);

					exit;
				} else {
					// More file HTTP headers
					header('Content-Length: '.strlen($output_data));

					// Output data
					echo $output_data;
				}
			}
		} else {
			// Process re-usable HTTP headers values
			$http_etag = md5_file($path);
			$http_last_modified = filemtime($path);

			// File HTTP headers
			if(!$CONFIG_COMMON['dev']['noprod']) {
				header('ETag: '.$http_etag);
				header('Last-Modified: '.$http_last_modified.' GMT');
			}

			// Check browser cache
			if(!$CONFIG_COMMON['dev']['noprod'] && (($http_etag == $if_none_match) || ($http_last_modified <= $if_modified_since))) {
				// Use browser cache
				header('Status: 304 Not Modified', true, 304);
				
				exit;
			} else {
				// More file HTTP headers
				header('Content-Length: '.filesize($path));

				// Simple binary read (no packing needed)
				readfile($path);
			}
		}
		
		exit;
	}
	
	// The file was not found
	header('Status: 404 Not Found', true, 404);
	exit('HTTP/1.1 404 Not Found');
}

// The request is not correct
header('Status: 400 Bad Request', true, 400);
exit('HTTP/1.1 400 Bad Request');

?>