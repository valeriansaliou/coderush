<?php

/*
 *	AirDrive
 *	Pod Page
 */

// Include translation
includeTranslation($CONTEXT_LANG, 'main', 'uno');

// Pod functions
require_once('../cgi/functions/pod.php');

// Get drive data
$drive_name = isset($CONTEXT_ROUTE[0]) ? $CONTEXT_ROUTE[0] : null;
$network_drive = driveNetwork($drive_name);
$exists_drive = ($network_drive['status'] != null) ? true : false;

// Drive offline
$drive_offline = ($network_drive['status'] == 'offline') ? ' enabled' : null;

// Drive not found?
if(!$exists_drive) {
	// 404 header
	header('Status: 404 Not Found', true, 404);
} else {
	// Drive target
	$drive_target = null;
	
	foreach($CONTEXT_ROUTE as $c_id => $c_dir) {
		if($c_id >= 1) {
			if(!$c_dir)
				break;
			
			$drive_target .= '/'.$c_dir;
		}
	}
	
	// No drive target?
	if(!$drive_target)
		$drive_target = '/';
}

?>

<!DOCTYPE html>

<html>

<head>
	<meta charset="utf-8" />
	
	<title><?php _e("AirDrive"); ?> - <?php if($exists_drive && $drive_offline) printf(T_("Drive Offline (%s)"), htmlspecialchars($drive_name)); else if($exists_drive) printf(T_("Accessing Drive (%s)..."), htmlspecialchars($drive_name)); else _e("Drive Not Found"); ?></title>
  	<link rel="shortcut icon" href="/favicon.ico" />
  	
  	<link rel="stylesheet" href="/static/int/<?php _revision(); ?>/stylesheets/common.css">
  	<link rel="stylesheet" href="/static/int/<?php _revision(); ?>/stylesheets/page.css">
  	<link rel="stylesheet" href="/static/int/<?php _revision(); ?>/stylesheets/pod.css">
	
	<!--[if lte IE 8]>
		<link rel="stylesheet" href="/static/int/<?php _revision(); ?>/stylesheets/legacy/common.css">
		<link rel="stylesheet" href="/static/int/<?php _revision(); ?>/stylesheets/legacy/pod.css">
	<![endif]-->
	
	<script type="text/javascript" src="/static/int/<?php _revision(); ?>/javascripts/common.js"></script>
	<script type="text/javascript" src="/static/int/<?php _revision(); ?>/javascripts/page.js"></script>
	<script type="text/javascript" src="/static/int/<?php _revision(); ?>/javascripts/pod.js"></script>
	
	<?php include('../cgi/includes/page.head.php'); ?>
</head>

<body class="easing">
	<div id="pod">
		<?php include('../cgi/includes/page.header.php'); ?>
		
		<div id="content">
			<div class="wrapper">
				<?php if($exists_drive) { ?>
					<?php if(!$drive_offline) { ?>
						<div class="accessing" data-local="<?php echo htmlEntities($network_drive['local'], ENT_QUOTES); ?>" data-internet="<?php echo htmlEntities($network_drive['internet'], ENT_QUOTES); ?>" data-target="<?php echo htmlEntities($drive_target, ENT_QUOTES); ?>" data-drive="<?php echo htmlEntities($drive_name, ENT_QUOTES); ?>">
							<div class="titles">
								<h1><?php _e("Accessing Drive..."); ?></h1>
								<h2><?php _e("Can take up to 15 seconds."); ?></h2>
							</div>
							
							<div class="waiter">
								<div class="wait">
									<span class="icon" data-icon="&#57398;"></span>
								</div>
							</div>
							
							<div class="progress">
								<div class="lane"></div>
								
								<div class="name label medium">
									<span class="bg">
										<span class="leftcorner"></span>
										<span class="middle"></span>
										<span class="rightcorner"></span>
									</span>
									
									<span class="inside">
										<span class="icon" data-icon="&#9729;"></span>
										<span class="text"><?php echo htmlspecialchars($drive_name); ?></span>
									</span>
								</div>
							</div>
						</div>
					<?php } ?>
					
					<div class="offline<?php echo $drive_offline; ?>">
						<div class="titles">
							<h1><?php _e("Drive Offline"); ?></h1>
							<h2><?php _e("Not On A WiFi Network."); ?></h2>
						</div>
						
						<div class="waiter">
							<div class="wait">
								<span class="icon" data-icon="&#9940;"></span>
							</div>
						</div>
						
						<div class="progress">
							<div class="lane"></div>
							
							<div class="name label medium red">
								<span class="bg">
									<span class="leftcorner"></span>
									<span class="middle"></span>
									<span class="rightcorner"></span>
								</span>
								
								<span class="inside">
									<span class="icon" data-icon="&#9926;"></span>
									<span class="text"><?php echo htmlspecialchars($drive_name); ?></span>
								</span>
							</div>
						</div>
					</div>
				<?php } else { ?>
					<div class="notfound">
						<div class="titles">
							<h1><?php _e("Drive Not Found"); ?></h1>
							<h2><?php _e("Retry Another One. Please."); ?></h2>
						</div>
						
						<div class="alerter">
							<div class="alert">
								<span class="icon" data-icon="&#10008;"></span>
							</div>
						</div>
					</div>
				<?php } ?>
				
				<div class="clear"></div>
			</div>
		</div>
		
		<?php include('../cgi/includes/page.footer.php'); ?>
	</div>
	
	<?php include('../cgi/includes/page.noscript.php'); ?>
	
	<?php include('../cgi/includes/page.analytics.php'); ?>
</body>

</html>