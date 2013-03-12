<?php

/*
 *	CodeRush
 *	Page Footer
 */

?>

<div id="footer">
	<!-- PAGE FOOTER HERE -->

	<ul class="lang">
		<?php foreach(listTranslation() as $lang_index => $lang_arr): ?>
		<?php $string_request = stringRequest(false); ?>
			<?php if($lang_index > 0) echo ' - '; ?>
			<li>
				<a<?php if($CONTEXT_LANG == $lang_arr['code']) echo ' class="current"'; ?> href="/<?php echo $lang_arr['code'].$string_request; ?>" data-lang="<?php echo $lang_arr['code']; ?>"><?php echo htmlspecialchars($lang_arr['name']); ?></a>
			</li>
		<?php endforeach; ?>
	</ul>
</div>