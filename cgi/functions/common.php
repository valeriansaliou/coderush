<?php

/*
 *	AirDrive
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

?>