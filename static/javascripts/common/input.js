/*
 *	CodeRush
 *	Common Input
 */

// On document ready
$(document).ready(function() {
	// Placeholders
	$('input, textarea').placeholder();
	
	// Blank links
	$('a[href=""], a[href="#"]').click(function() {
		return false;
	});
});