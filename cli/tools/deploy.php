<?php

/*
 *	CodeRush
 *	Deploy Tool
 */

// Change current working dir
chdir(dirname(__FILE__));

// Include functions
require_once('../functions/common.php');
require_once('../functions/deploy.php');

// Don't allow non-CLI requests
if(caller() != 'cli')
	exit('Command-line service. Please call me from your shell.');

// Initialize
$error = 0;

// Launch
print('[deploy] Deploying app...'."\n");

// Increment revision number
$revision_deploy = revisionDeploy();

if($revision_deploy != 0) {
	// Empty cache
	$cache_static_deploy = cacheStaticDeploy();

	if($cache_static_deploy == 0) {
		$error = 2;

		print("\n");
		print('[deploy] NOTICE - Could not purge static cache.'."\n");
		print("\n");
	}
} else {
	$error = 1;

	print("\n");
	print('[deploy] FATAL - Could not update revision number.'."\n");
	print("\n");
}

// Exit
switch($error) {
	// Fatal error
	case 1:
		print('[deploy] Fatal error. Not deployed.'."\n");

		break;

	// Notice error
	case 2:
		print('[deploy] System error. Partially deployed.'."\n");
		
		break;

	default:
		print('[deploy] Successfully deployed.'."\n");
}

print('[deploy] Done.'."\n");

exit;

?>