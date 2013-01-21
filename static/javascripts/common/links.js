/*
 *	CodeRush
 *	Common Links
 */

// On document ready
$(document).ready(function() {
	// Deactivate blank links
	$('a').click(function() {
		var href = $(this).attr('href') || '';
		
		if(!href || href.match(/^#/))
			return false;
	});
});