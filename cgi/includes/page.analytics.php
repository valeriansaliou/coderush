<?php

/*
 *	CodeRush
 *	Page Analytics
 */

?>

<?php if(!$CONFIG_COMMON['dev']['noprod']) { ?>
	<script type="text/javascript">
		var pkBaseURL = (("https:" == document.location.protocol) ? "https://analytics.frenchtouch.pro/" : "http://analytics.frenchtouch.pro/");
		
		document.write(unescape("%3Cscript src='" + pkBaseURL + "piwik.js' type='text/javascript'%3E%3C/script%3E"));
	</script>
	
	<script type="text/javascript">
		try {
			var piwikTracker = Piwik.getTracker(pkBaseURL + "piwik.php", 1);
			
			piwikTracker.trackPageView();
			piwikTracker.enableLinkTracking();
		} catch(err) {}
	</script>
<? } ?>