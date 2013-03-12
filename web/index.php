<?php

/*
 *	CodeRush
 *	Router Launcher
 */

// Read configuration
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
$CONTEXT_LANG		= 'en';
$CONTEXT_REVISION 	= strval($CONFIG_COMMON['meta']['revision']);

// Route request
if(!$CONTEXT_ROUTE[0])
	include_once('../cgi/routes/home.php');
else if(file_exists('../cgi/routes/'.$CONTEXT_ROUTE[0].'.php'))
	include_once('../cgi/routes/'.$CONTEXT_ROUTE[0].'.php');
else
	include_once('../cgi/routes/not_found.php');

?>