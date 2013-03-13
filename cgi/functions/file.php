<?php

/*
 *	CodeRush
 *	File Functions
 */

// Parses a file name
function nameFile($file) {
	return array(
		'name' => $file ? pathinfo($file, PATHINFO_FILENAME) : null,
		'ext' => $file ? strtolower(pathinfo($file, PATHINFO_EXTENSION)) : null
	);
}

// Gets the MIME type of a file
function mimeFile($path) {
	$finfo = finfo_open(FILEINFO_MIME_TYPE);
	$cmime = finfo_file($finfo, $path);
	finfo_close($finfo);
	
	return $cmime;
}

?>