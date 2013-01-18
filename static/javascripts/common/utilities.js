/*
 *	CodeRush
 *	Common Utilities
 */

// Checks if an element exists in the DOM
function exists(selector) {
	if($(selector).size() > 0)
		return true;
	else
		return false;
}

// Properly explodes a string with a given character
function explodeThis(toEx, toStr, i) {
	// Get the index of our char to explode
	var index = toStr.indexOf(toEx);
	
	// We split if necessary the string
	if(index != -1) {
		if(i == 0)
			toStr = toStr.substr(0, index);
		else
			toStr = toStr.substr(index + 1);
	}
	
	// We return the value
	return toStr;
}

// Generates an unique ID
GEN_ID = 0;

function genID() {
	return ++GEN_ID;
}

// Encodes quotes in a string
function encodeQuotes(str) {
	return (str + '').replace(/"/g, '&quot;');
}

// HTML-encode a string
function encodeHTML(string) {
	// No string?
	if(!string)
		return string;
	
	// Replace HTML-specific chars
	return string.replace(/&/g,"&amp;")
	             .replace(/</g,"&lt;")
	             .replace(/>/g,"&gt;")
	             .replace(/\"/g,"&quot;")
	             .replace(/\n/g,"<br />");
}

// Checks if the Web browser is obsolete
function isObsolete() {
	return ($.browser.msie && (parseInt($.browser.version, 10) <= 8));
}