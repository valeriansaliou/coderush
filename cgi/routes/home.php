<?php

/*
 *	CodeRush
 *	Home Page
 */

// Required libs
/* require_once('../cgi/libs/mobile_detect.php');

// Redirect to app page if Android device
$mobile_detect = new Mobile_Detect();

if($mobile_detect->isMobile() && !(isset($_COOKIE['mobile']) && ($_COOKIE['mobile'] == 'ignore'))) {
	header('Location: /app', true, 302);
	
	exit;
} */

// Don't allow sub-pages here
if(!$CONTEXT_ROUTE[0] || isset($CONTEXT_ROUTE[1])) {
	header('Location: /home', true, 302);
	
	exit;
}

// Include translation
includeTranslation($CONTEXT_LANG, 'main', 'home');

?>

<!DOCTYPE html>

<html>

<head>
	<?php include('../cgi/includes/page.head.php'); ?>
	
	<title><?php _e("CodeRush"); ?> - <?php _e("Base website"); ?></title>
	
	<link rel="stylesheet" href="/static/int/<?php _revision(); ?>/stylesheets/home.css">
	<script type="text/javascript" src="/static/int/<?php _revision(); ?>/javascripts/home.js"></script>
</head>

<body>
	<div id="home">
		<?php include('../cgi/includes/page.header.php'); ?>
		
		<div id="content">
			<h1><?php _e("Welcome to CodeRush!"); ?></h1>
			<p><?php _e("Start your website from this awesome code base!"); ?></p>
		</div>
		
		<?php include('../cgi/includes/page.footer.php'); ?>
	</div>
	
	<?php include('../cgi/includes/page.noscript.php'); ?>
	
	<?php include('../cgi/includes/page.analytics.php'); ?>
</body>

</html>