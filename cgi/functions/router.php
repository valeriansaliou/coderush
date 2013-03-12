<?php

/*
 *	CodeRush
 *	Page Routing Functions
 */

// Checks if request is secure
function secureRequest($request) {
	return !preg_match('/(^\.\.)|(\.\.\/)|(\.\.$)/', $request);
}

// Normalizes request (removes extra slashes and so on)
function normalizeRequest($request) {
	$normalized = $request;
	
	if($normalized) {
		// Remove blank spaces on extremities
		$normalized = trim($normalized);
		
		// Remove double folder separators
		$normalized = preg_replace('/(\/+)?$/', '/', $normalized);
		
		// Remove last folder separator
		$normalized = preg_replace('/(\/+)?$/', '', $normalized);
	}
	
	return $normalized;
}

// Routes a request
function routeRequest() {
	// Read request
	$request = isset($_GET['q']) ? $_GET['q'] : null;
	
	// Secure request
	if(!secureRequest($request)) {
		$route = array('pod');
	} else {
		// Read query
		$normalized = normalizeRequest($request);
		
		// Explode route path
		$exploded = $normalized ? explode('/', $normalized) : array();
		
		// Route page
		if(count($exploded)) {
			// Trim route content
			foreach($exploded as $exploded_key => $exploded_value)
				$exploded[$exploded_key] = trim($exploded_value);
			
			// Check route root
			$route_root = isset($exploded[0]) ? $exploded[0] : '';
			
			if($route_root)
				$route = $exploded;
		} else {
			$route = array('');
		}
	}
	
	return $route;
}

// Stringify the request route
function stringRequest($lang_inc = true) {
	global $CONTEXT_ROUTE;
	
	// Initiliaze route string
	$string_route = '';
	
	// Generate route string
	foreach($CONTEXT_ROUTE as $sub_id => $sub_value) {
		// No more data
		if(!$sub_value)
			break;
		
		// Do not include lang parameter?
		if(($sub_id == 0) && localesTranslation($sub_value))
			continue;
		
		$string_route .= '/'.$sub_value;
	}
	
	// Root route?
	if(!$string_route)
		$string_route = '/';
	
	return $string_route;
}

// Get a part of a request URL
function partRequest($id) {
	global $CONFIG_COMMON, $CONTEXT_ROUTE;
	
	// Sub ID
	$sub_id = ($CONFIG_COMMON['i18n']['url'] && localesTranslation(isset($CONTEXT_ROUTE) ? $CONTEXT_ROUTE[0] : null)) ? ++$id : $id;
	
	// Get sub route with that ID
	return isset($CONTEXT_ROUTE[$sub_id]) ? $CONTEXT_ROUTE[$sub_id] : null;
}

?>