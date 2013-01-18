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
	header('Cache-Control: no-store, no-cache, must-revalidate');
	header('Cache-Control: post-check=0, pre-check=0', false);
	header('Pragma: no-cache');
} else {
	$expires = 31536000;
	header('Pragma: public');
	header('Cache-Control: maxage='.$expires);
	header('Expires: '.gmdate('D, d M Y H:i:s', (time() + $expires)).' GMT');
}

// Parse URL
$static_url = parseStaticURL();

$lang = $static_url['lang'];
$revision = $static_url['hash'];
$type = ($static_url['type'] && is_dir('../static/'.$static_url['type'])) ? $static_url['type'] : null;
$file = $static_url['file'];

// We check if the data was submitted
if($revision && $file && $type) {
	// We define some stuffs
	$dir = '../static/'.$type.'/';
	$path = $dir.$file;
	$parse_filename = parseFileName($file);
	$continue = true;
	
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
		$no_loop = !$CONFIG_COMMON['dev']['nocache'] && (($type == 'stylesheets') || ($type == 'javascripts')) && hasCache($cache_lang);
		
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
						$CURRENT_STATICS[$sub_statics][$type][$check_static_folder] = getStaticFiles($dir.$check_static_folder, $sub_statics);
						
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
			
			if($mime == 'png')
				header('Content-Type: image/png');
			else if($mime == 'gif')
				header('Content-Type: image/gif');
			else if(($mime == 'jpg') || ($mime == 'jpeg'))
				header('Content-Type: image/jpeg');
			else if($mime == 'ttf')
				header('Content-Type: application/x-font-ttf');
			else if($mime == 'eot')
				header('Content-Type: application/vnd.ms-fontobject');
			else if(($mime == 'oga') || ($mime == 'ogg'))
				header('Content-Type: audio/ogg');
			else if($mime == 'mp3')
				header('Content-Type: audio/mpeg');
			else if($mime == 'm4a')
				header('Content-Type: audio/mp4a-latm');
			else
				header('Content-Type: '.getFileMIME($path));
		}
		
		// Read the text files (pack them)
		if(($type == 'stylesheets') || ($type == 'javascripts')) {
			// Any cache file?
			if(hasCache($cache_lang) && !$CONFIG_COMMON['dev']['nocache']) {
				$cache_read = readCache($cache_lang);
				
				if($deflate_support || !$CONFIG_COMMON['compress']['files'])
					echo $cache_read;
				else
					echo gzinflate($cache_read);
			} else {
				// Read cache reference
				if(hasCache($cache_hash) && !$CONFIG_COMMON['dev']['nocache']) {
					// Read the reference
					$cache_reference = readCache($cache_hash);
					
					// Filter the cache reference
					if($CONFIG_COMMON['compress']['files'])
						$output = gzinflate($cache_reference);
					else
						$output = $cache_reference;
				} else {
					// Initialize the loop
					$looped = '';
					
					// Add the content of the current file
					$statics_sub = isset($CURRENT_STATICS[$sub_statics][$type]) ? $CURRENT_STATICS[$sub_statics][$type] : array();
					$statics_ext = ($type == 'javascripts') ? 'js': 'css';
					
					// Read each sub-folder
					foreach($statics_sub as $static_folder => $static_files) {
						// Check each sub-file
						if(is_array($static_files)) {
							// Append each sub-file
							foreach($static_files as $static_file) {
								$looped .= rmBOM(file_get_contents($dir.$static_folder.'/'.$static_file.'.'.$statics_ext))."\n";
							}
						}
					}
					
					// Filter the CSS
					if($type == 'stylesheets')
						$looped = setPath($looped, $type, $lang);
					
					// Optimize the code rendering
					if($type == 'stylesheets') {
						// Can minify the CSS
						if($CONFIG_COMMON['compress']['files'] && !$CONFIG_COMMON['dev']['nocache'])
							$output = compressCSS($looped);
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
					genCache($final, $CONFIG_COMMON['dev']['nocache'], $cache_hash);
				}
				
				// Filter the JS
				if($type == 'javascripts') {
					// Set the Get API paths
					$output = setPath($output, $type, $locale);
					
					// Translate the JS script
					includeTranslation($locale, 'main', 'static');
					$output = setTranslation($output);
					
					// Generate the cache
					if($CONFIG_COMMON['compress']['files'])
						$final = gzdeflate($output, 9);
					else
						$final = $output;
					
					// Write it!
					genCache($final, $CONFIG_COMMON['dev']['nocache'], $cache_lang);
				}
				
				// Output a well-encoded string
				if($deflate_support || !$CONFIG_COMMON['compress']['files'])
					echo $final;
				else
					echo gzinflate($final);
			}
		} else {
			// Simple binary read (no packing needed)
			readfile($path);
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