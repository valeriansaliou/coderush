<?php

/*
 *	CodeRush
 *	Not Found Page
 */

// 404 header
header('Status: 404 Not Found', true, 404);

// Include translation
includeTranslation($CONTEXT_LANG, 'main', 'not_found');

?>

<!DOCTYPE html>

<html>

<head>
	<?php include('../cgi/includes/page.head.php'); ?>
	
	<title><?php _e("CodeRush"); ?> - <?php _e("Not Found"); ?></title>
	
	<link rel="stylesheet" href="<?php _statics(); ?>/int/<?php _revision(); ?>/stylesheets/not_found.css">
	<script type="text/javascript" src="<?php _statics(); ?>/int/<?php _revision(); ?>/javascripts/not_found.js"></script>
</head>

<body>
	<div id="not_found">
		<?php include('../cgi/includes/page.header.php'); ?>
		
		<div id="content">
			<h1><span class="icon" data-icon="&#10008;"></span><?php _e("CodeRush could not find this."); ?></h1>
			<p><?php _e("Please enter another URL so that I can find the page!"); ?></p>
		</div>
		
		<?php include('../cgi/includes/page.footer.php'); ?>
	</div>
	
	<?php include('../cgi/includes/page.noscript.php'); ?>
	
	<?php include('../cgi/includes/page.analytics.php'); ?>
</body>

</html>