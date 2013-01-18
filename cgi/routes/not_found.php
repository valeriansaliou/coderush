<?php

/*
 *	CodeRush
 *	Not Found Page
 */

// Include translation
includeTranslation($CONTEXT_LANG, 'main', 'not_found');

?>

<!DOCTYPE html>

<html>

<head>
	<?php include('../cgi/includes/page.head.php'); ?>
	
	<title><?php _e("CodeRush"); ?> - <?php _e("Base website"); ?></title>
	
	<link rel="stylesheet" href="/static/int/<?php _revision(); ?>/stylesheets/not_found.css">
	<script type="text/javascript" src="/static/int/<?php _revision(); ?>/javascripts/not_found.js"></script>
</head>

<body>
	<div id="not_found">
		<?php include('../cgi/includes/page.header.php'); ?>
		
		<div id="content">
			<h1><?php _e("CodeRush could not find this."); ?></h1>
			<p><?php _e("Please enter another URL so that I can find the page!"); ?></p>
		</div>
		
		<?php include('../cgi/includes/page.footer.php'); ?>
	</div>
	
	<?php include('../cgi/includes/page.noscript.php'); ?>
	
	<?php include('../cgi/includes/page.analytics.php'); ?>
</body>

</html>