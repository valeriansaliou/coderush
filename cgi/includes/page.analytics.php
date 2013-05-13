<?php

/*
 *	CodeRush
 *	Page Analytics
 */

?>

<?php if(!$CONFIG_INSTANCE['dev']['noprod'] && $CONFIG_COMMON['analytics']['enabled']) { ?>
	<script type="text/javascript">
		var pkBaseURL = (("https:" == document.location.protocol) ? "https://<?php echo $CONFIG_COMMON['analytics']['server']; ?>/" : "http://<?php echo $CONFIG_COMMON['analytics']['server']; ?>/");
		
		document.write(unescape("%3Cscript src='" + pkBaseURL + "piwik.js' type='text/javascript'%3E%3C/script%3E"));
	</script>
	
	<script type="text/javascript">
		try {
			var piwikTracker = Piwik.getTracker(pkBaseURL + "piwik.php", <?php echo $CONFIG_COMMON['analytics']['id']; ?>);
			
			piwikTracker.trackPageView();
			piwikTracker.enableLinkTracking();
		} catch(err) {}
	</script>
<?php } ?>