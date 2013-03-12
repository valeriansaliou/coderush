/*
 *	CodeRush
 *	Page Lang
 */

// On document ready
$(document).ready(function() {
	// Click on lang picker
	$('#footer ul.lang li a').click(function() {
		// Store lang in a cookie
		$.cookie('lang', ($(this).attr('data-lang') || 'en'), { expires: 365, path: '/' });
	});
});