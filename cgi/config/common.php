<?php

/*
 *	CodeRush
 *	Common Configuration (secured)
 */

// Common configuration container
$CONFIG_COMMON = array(
	// Development
	'dev' 		=> array(
		'nocache' 		=> true,
		'noprod' 		=> true
	),
	
	// Performance
	'compress'	=> array(
		'files'			=> true
	),
	
	// Internationalization
	'i18n'		=> array(
		'enabled'		=> true,
		'cookie'		=> true,
		'url'			=> true
	),
	
	// Meta information
	'meta'		=> array(
		'revision'		=> 1
	)
);

?>