<?php

/*
 *	CodeRush
 *	Common Functions
 */

// Return locale code
function lang() {
	global $CONTEXT_LANG;
	
	return $CONTEXT_LANG;
}

// Print locale code
function _lang() {
	echo lang();
}

// Return revision number
function revision() {
	global $CONTEXT_REVISION;
	
	return $CONTEXT_REVISION;
}

// Print revision number
function _revision() {
	echo revision();
}

// Return static host
function statics() {
	global $CONFIG_HOSTS;
	
	// Generate proper path to static server
	$static = $CONFIG_HOSTS['api']['static'];
	$static = preg_replace('/(\/+)?$/', '', $static);
	
	if(!$static)
		$static = '/static';
	
	return $static;
}

// Print static host
function _statics() {
	echo statics();
}

// Return link prefix
function prefix() {
	global $CONFIG_COMMON, $CONTEXT_LANG;

	return $CONFIG_COMMON['i18n']['url'] ? ('/'.($CONTEXT_LANG ? $CONTEXT_LANG : 'en')) : '';
}

// Print link prefix
function _prefix() {
	echo prefix();
}

?>