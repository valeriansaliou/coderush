<?php

/*
 *	CodeRush
 *	Common Functions
 */

// Returns from where script is invoked
function caller() {
	if((php_sapi_name() == 'cli') || empty($_SERVER['REMOTE_ADDR']))
		return 'cli';
	
	return 'cgi';
}

?>