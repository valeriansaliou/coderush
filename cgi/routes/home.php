<?php

/*
 *	CodeRush
 *	Hello Page
 */

// Required libs
require_once('../cgi/libs/mobile_detect.php');

// Redirect to app page if Android device
$mobile_detect = new Mobile_Detect();

if($mobile_detect->isAndroidOS() && $mobile_detect->isMobile() && !(isset($_COOKIE['mobile']) && ($_COOKIE['mobile'] == 'ignore'))) {
	header('Location: /app', true, 302);
	
	exit;
}

// Don't allow sub-pages here
if(!$CONTEXT_ROUTE[0] || isset($CONTEXT_ROUTE[1])) {
	header('Location: /hello', true, 302);
	
	exit;
}

// Include translation
includeTranslation($CONTEXT_LANG, 'main', 'hello');

?>

<!DOCTYPE html>

<html>

<head>
	<meta charset="utf-8" />
	
	<title><?php _e("CodeRush"); ?> - <?php _e("Wireless File Storage for Android"); ?></title>
  	<link rel="shortcut icon" href="/favicon.ico" />
  	
  	<link rel="stylesheet" href="/static/int/<?php _revision(); ?>/stylesheets/common.css">
  	<link rel="stylesheet" href="/static/int/<?php _revision(); ?>/stylesheets/page.css">
  	<link rel="stylesheet" href="/static/int/<?php _revision(); ?>/stylesheets/hello.css">
	
	<!--[if lte IE 8]>
		<link rel="stylesheet" href="/static/int/<?php _revision(); ?>/stylesheets/legacy/common.css">
		<link rel="stylesheet" href="/static/int/<?php _revision(); ?>/stylesheets/legacy/hello.css">
	<![endif]-->
	
	<script type="text/javascript" src="/static/int/<?php _revision(); ?>/javascripts/common.js"></script>
	<script type="text/javascript" src="/static/int/<?php _revision(); ?>/javascripts/page.js"></script>
	<script type="text/javascript" src="/static/int/<?php _revision(); ?>/javascripts/hello.js"></script>
	
	<?php include('../cgi/includes/page.head.php'); ?>
</head>

<body class="easing">
	<div id="hello">
		<?php include('../cgi/includes/page.header.php'); ?>
		
		<div id="content">
			<div class="wrapper">
				<div class="device">
					<div class="ambiance">
						<div class="phone">
							<div class="screen"></div>
							<div class="reflection"></div>
						</div>
						
						<div class="beam" data-icon="&#57389;"></div>
						
						<div class="files">
							<span class="music" data-icon="&#57441;"></span>
							<span class="picture" data-icon="&#57456;"></span>
							<span class="document" data-icon="&#57344;"></span>
							<span class="folder" data-icon="&#57445;"></span>
						</div>
					</div>
				</div>
				
				<div class="explain">
					<h1><?php _e("Store, Manage & Share Files."); ?></h1>
					<h2><?php _e("Right On Your Phone Or Tablet."); ?></h2>
					
					<div class="features">
						<p><span class="icon" data-icon="&#57389;"></span><?php _e("Access your files wirelessly from any computer."); ?></p>
						<p><span class="icon" data-icon="&#57398;"></span><?php _e("No need to plug an USB cable to sync your photos."); ?></p>
						<p><span class="icon" data-icon="&#9889;"></span><?php _e("It's fast as hell. Say goodbye to Dropbox & cie."); ?></p>
						<p><span class="icon" data-icon="&#57451;"></span><?php _e("Send big files to your friends. Fast & secure."); ?></p>
					</div>
					
					<a class="get" href="<?php echo $CONFIG_COMMON['android']['store']; ?>">
						<span class="infobubble">
							<span class="bgs">
								<span class="leftcorners"></span>
								<span class="middlerepeat"></span>
								<span class="rightcorners"></span>
							</span>
							
							<span class="paddeds">
								<span class="icon" data-icon="&#9733;"></span>
								<span class="text"><?php _e("It's free!"); ?></span>
							</span>
						</span>
					</a>
				</div>
				
				<div class="clear"></div>
			</div>
		</div>
		
		<?php include('../cgi/includes/page.footer.php'); ?>
	</div>
	
	<?php include('../cgi/includes/page.noscript.php'); ?>
	
	<?php include('../cgi/includes/page.analytics.php'); ?>
</body>

</html>