<?php

/*
 *	AirDrive
 *	File Functions
 */

// Returns the given file extension
function getFileExt($name) {
	return strtolower(preg_replace('/^(.+)(\.)([^\.]+)$/i', '$3', $name));
}

// Parses a file name
function parseFileName($file) {
	return array(
		'name' => $file ? pathinfo($file, PATHINFO_FILENAME) : null,
		'ext' => $file ? strtolower(pathinfo($file, PATHINFO_EXTENSION)) : null
	);
}

// Gets the MIME type of a file
function getFileMIME($path) {
	$finfo = finfo_open(FILEINFO_MIME_TYPE);
	$cmime = finfo_file($finfo, $path);
	finfo_close($finfo);
	
	return $cmime;
}

?>