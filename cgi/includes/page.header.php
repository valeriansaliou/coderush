<?php

/*
 *	AirDrive
 *	Page Header
 */

?>

<noscript>
	<span class="marginizer"></span>
</noscript>

<div id="header">
	<div class="wrapper">
		<?php if($CONTEXT_ROUTE[0] == 'hello') { ?>
			<div class="logo">
				<span class="thunder"></span>
			</div>
			
			<div class="search">
				<input type="text" placeholder="<?php _e("Find a drive"); ?>" />
				
				<a class="magnifier" href="/api/search/drive"></a>
			</div>
		<? } else { ?>
			<a class="logo" href="/hello">
				<span class="thunder"></span>
			</a>
			
			<a class="get button medium" href="<?php echo $CONFIG_COMMON['android']['store']; ?>" target="_blank">
				<span class="bg">
					<span class="leftcorner"></span>
					<span class="middle"></span>
					<span class="rightcorner"></span>
				</span>
				
				<span class="inside">
					<span class="icon" data-icon="&#57410;"></span>
					<span class="divider"></span>
					<span class="text"><?php _e("Get AirDrive"); ?></span>
				</span>
			</a>
		<?php } ?>
		
		<div class="clear"></div>
	</div>
</div>