<?php

/*
 *	CodeRush
 *	API Dispatcher Functions
 */

// Parses the API URL
function parseURLAPI() {
	// INFO: /api/NAME/ACTION(/META)?
	
	global $CONTEXT_ROUTE;
	
	// Result array
	$result = array(
		'name'		=> null,
		'action'	=> null,
		'meta'		=> null
	);
	
	// Populate array
	foreach($CONTEXT_ROUTE as $current_key => $current_value) {
		if(!$current_value)
			$current_value = null;
		
		switch($current_key) {
			// Root
			case 0:
				break;
			
			// Name
			case 1:
				$result['name'] = $current_value;
				
				break;
			
			// Action
			case 2:
				$result['action'] = $current_value;
				
				break;
			
			// Meta
			default:
				if($result['meta'])
					$result['meta'] .= '/'.$current_value;
				else
					$result['meta'] = $current_value;
		}
	}
	
	return $result;
}

?>