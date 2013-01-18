<?php

/*
 *	AirDrive
 *	Translation Functions
 */

// The function to include a translation file
function includeTranslation($locale, $domain, $route) {
	T_setlocale(LC_MESSAGES, $locale);
	T_bindtextdomain($domain, '../i18n/'.$route);
	T_bind_textdomain_codeset($domain, 'UTF-8');
	T_textdomain($domain);
}

// Translates a string
function _e($string) {
	echo T_gettext($string);
}

?>