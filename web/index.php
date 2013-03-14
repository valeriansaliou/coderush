<?php

/*
 *	CodeRush
 *	Router Launcher
 */

// Read configuration
require_once('../cgi/config/framework.php');
require_once('../cgi/config/common.php');
require_once('../cgi/config/hosts.php');
require_once('../cgi/config/sql.php');

// Required libs
require_once('../cgi/libs/gettext.php');

// Required functions
require_once('../cgi/functions/common.php');
require_once('../cgi/functions/i18n.php');
require_once('../cgi/functions/router.php');
require_once('../cgi/functions/database.php');

// Current context
$CONTEXT_ROUTE 		= routeRequest();
$CONTEXT_LANG		= langTranslation();
$CONTEXT_REVISION 	= strval($CONFIG_COMMON['meta']['revision']);

// Common HTTP headers
header('X-Powered-By: CodeRush Framework');

// Translations
includeTranslation($CONTEXT_LANG, 'main');

// Manage localization
if($CONFIG_COMMON['i18n']['url'] && ($CONTEXT_LANG != $CONTEXT_ROUTE[0]) && !preg_match('/^'.$CONFIG_FRAMEWORK['filters']['i18n'].'$/', $CONTEXT_ROUTE[0])) {
	// Redirect to localized URL (must have lang parameter in URLs)
	header('Status: 301 Moved Permanently', true, 301);
	header('Location: /'.$CONTEXT_LANG.stringRequest(false));
	
	exit;
} else if(!$CONFIG_COMMON['i18n']['url'] && existsTranslation($CONTEXT_ROUTE[0])) {
	// Redirect to non-localized URL (no lang parameter in URLs)
	header('Status: 301 Moved Permanently', true, 301);
	header('Location: '.stringRequest(false));
	
	exit;
}

// Route request
if(!partRequest(0))
	include_once('../cgi/routes/home.php');
else if(file_exists('../cgi/routes/'.partRequest(0).'.php'))
	include_once('../cgi/routes/'.partRequest(0).'.php');
else
	include_once('../cgi/routes/not_found.php');

?>