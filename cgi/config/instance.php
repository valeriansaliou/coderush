<?php

/*
 *	CodeRush
 *	Instance Configuration (secured)
 *  
 *  UPDATES ARE EXCLUDED FROM GIT
 */

// Instance configuration container
$CONFIG_INSTANCE = array(
	// Development
	'dev' 		=> array(
		'nocache' 		=> true,
		'noprod' 		=> true
	),

	// Performance
	'compress'	=> array(
		'files'			=> true
	)
);

?>