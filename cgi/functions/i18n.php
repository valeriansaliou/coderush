<?php

/*
 *	CodeRush
 *	Translation Functions
 */

// Translates a string
function _e($string) {
	global $CONFIG_COMMON;
	
	if($CONFIG_COMMON['i18n']['enabled'])
		echo T_gettext($string);
	else
		echo $string;
}

// Includes a translation file
function includeTranslation($locale, $domain, $route) {
	global $CONFIG_COMMON;
	
	// Translations enabled?
	if($CONFIG_COMMON['i18n']['enabled']) {
		T_setlocale(LC_MESSAGES, $locale);
		T_bindtextdomain($domain, '../i18n/'.$route);
		T_bind_textdomain_codeset($domain, 'UTF-8');
		T_textdomain($domain);
	}
}

// Gets the lang value (browser, cookie or defined)
function langTranslation() {
	global $CONFIG_FRAMEWORK, $CONTEXT_ROUTE;

	// #0. Bypass lang detect
	if(preg_match('/^'.$CONFIG_FRAMEWORK['filters']['lang'].'$/', $CONTEXT_ROUTE[0]))
		return 'en';

	// #1. Get lang from cookie
	if(isset($_COOKIE['lang'])) {
		$check_cookie = $_COOKIE['lang'];

		// Check cookie value
		if(existsTranslation($check_cookie))
			return $check_cookie;
	}

	// #2. Detect lang from HTTP headers
	if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
		// Get the language of the browser
		$nav_langs = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
		$langs_list = array();

		// Extract lang values (keep only the available ones)
		foreach($nav_langs as $current_lang) {
			// Working vars
			$current_code = null;
			$current_priority = 0;

			// Split current lang
			$current_split = explode(';', $current_lang);

			// Extract code
			$current_code = isset($current_split[0]) ? trim($current_split[0]) : null;

			if(!$current_code)
				$current_code = trim($current_lang);

			if(!existsTranslation($current_code))
				$current_code = preg_replace('/^([^-]+)-(.*)$/', '$1', $current_code);

			// Extract priority
			$current_priority = isset($current_split[1]) ? trim($current_split[1]) : null;

			if($current_priority) {
				$current_priority = preg_replace('/q=(.*)/i', '$1', $current_priority);
				$current_priority = is_numeric($current_priority) ? floatval($current_priority) : 0;
			} else {
				$current_priority = 0;
			}
			
			// Locale is not available
			if(!existsTranslation($current_code))
				continue;

			// Push this lang!
			if(!isset($langs_list[$current_code]) || ($current_priority > $langs_list[$current_code]))
				$langs_list[$current_code] = $current_priority;
		}

		// Choose the highest priority
		asort($langs_list, SORT_NUMERIC);

		$highest_lang = null;

		foreach($langs_list as $highest_code => $highest_priority) {
			if($highest_code) {
				$highest_lang = $highest_code;

				break;
			}
		}

		if($highest_lang)
			return $highest_lang;
	}

	// #3. Get lang from URL (last chance!)
	if(isset($CONTEXT_ROUTE[0]) && existsTranslation($CONTEXT_ROUTE[0]))
		return $CONTEXT_ROUTE[0];

	// #4. Default on English
	return 'en';
}

// Converts an ISO locale code to localized name
function localesTranslation($code) {
	// No code?
	if(!$code)
		return null;
	
	$code = strtolower($code);
	
	$known = array(
		'aa' => 'Afaraf',
		'ab' => 'Аҧсуа',
		'ae' => 'Avesta',
		'af' => 'Afrikaans',
		'ak' => 'Akan',
		'am' => 'አማርኛ',
		'an' => 'Aragonés',
		'ar' => 'العربية',
		'as' => 'অসমীয়া',
		'av' => 'авар мацӀ',
		'ay' => 'Aymar aru',
		'az' => 'Azərbaycan dili',
		'ba' => 'башҡорт теле',
		'be' => 'Беларуская',
		'bg' => 'български',
		'bh' => 'भोजपुरी',
		'bi' => 'Bislama',
		'bm' => 'Bamanankan',
		'bn' => 'বাংলা',
		'bo' => 'བོད་ཡིག',
		'br' => 'Brezhoneg',
		'bs' => 'Bosanski jezik',
		'ca' => 'Català',
		'ce' => 'нохчийн мотт',
		'ch' => 'Chamoru',
		'co' => 'Corsu',
		'cr' => 'ᓀᐦᐃᔭᐍᐏᐣ',
		'cs' => 'Česky',
		'cu' => 'Словѣньскъ',
		'cv' => 'чӑваш чӗлхи',
		'cy' => 'Cymraeg',
		'da' => 'Dansk',
		'de' => 'Deutsch',
		'dv' => 'ދިވެހި',
		'dz' => 'རྫོང་ཁ',
		'ee' => 'Ɛʋɛgbɛ',
		'el' => 'Ελληνικά',
		'en' => 'English',
		'eo' => 'Esperanto',
		'es' => 'Español',
		'et' => 'Eesti keel',
		'eu' => 'Euskara',
		'fa' => 'فارسی',
		'ff' => 'Fulfulde',
		'fi' => 'Suomen kieli',
		'fj' => 'Vosa Vakaviti',
		'fo' => 'Føroyskt',
		'fr' => 'Français',
		'fy' => 'Frysk',
		'ga' => 'Gaeilge',
		'gd' => 'Gàidhlig',
		'gl' => 'Galego',
		'gn' => 'Avañe\'ẽ',
		'gu' => 'ગુજરાતી',
		'gv' => 'Ghaelg',
		'ha' => 'هَوُسَ',
		'he' => 'עברית',
		'hi' => 'हिन्दी',
		'ho' => 'Hiri Motu',
		'hr' => 'Hrvatski',
		'ht' => 'Kreyòl ayisyen',
		'hu' => 'Magyar',
		'hy' => 'Հայերեն',
		'hz' => 'Otjiherero',
		'ia' => 'Interlingua',
		'id' => 'Bahasa',
		'ie' => 'Interlingue',
		'ig' => 'Igbo',
		'ii' => 'ꆇꉙ',
		'ik' => 'Iñupiaq',
		'io' => 'Ido',
		'is' => 'Íslenska',
		'it' => 'Italiano',
		'iu' => 'ᐃᓄᒃᑎᑐᑦ',
		'ja' => '日本語',
		'jv' => 'Basa Jawa',
		'ka' => 'ქართული',
		'kg' => 'KiKongo',
		'ki' => 'Gĩkũyũ',
		'kj' => 'Kuanyama',
		'kk' => 'Қазақ тілі',
		'kl' => 'Kalaallisut',
		'km' => 'ភាសាខ្មែរ',
		'kn' => 'ಕನ್ನಡ',
		'ko' => '한 국어',
		'kr' => 'Kanuri',
		'ks' => 'कश्मीरी',
		'ku' => 'Kurdî',
		'kv' => 'коми кыв',
		'kw' => 'Kernewek',
		'ky' => 'кыргыз тили',
		'la' => 'Latine',
		'lb' => 'Lëtzebuergesch',
		'lg' => 'Luganda',
		'li' => 'Limburgs',
		'ln' => 'Lingála',
		'lo' => 'ພາສາລາວ',
		'lt' => 'Lietuvių kalba',
		'lu' => 'cilubà',
		'lv' => 'Latviešu valoda',
		'mg' => 'Fiteny malagasy',
		'mh' => 'Kajin M̧ajeļ',
		'mi' => 'Te reo Māori',
		'mk' => 'македонски јазик',
		'ml' => 'മലയാളം',
		'mn' => 'Монгол',
		'mo' => 'лимба молдовеняскэ',
		'mr' => 'मराठी',
		'ms' => 'Bahasa Melayu',
		'mt' => 'Malti',
		'my' => 'ဗမာစာ',
		'na' => 'Ekakairũ Naoero',
		'nb' => 'Norsk bokmål',
		'nd' => 'isiNdebele',
		'ne' => 'नेपाली',
		'ng' => 'Owambo',
		'nl' => 'Nederlands',
		'nn' => 'Norsk nynorsk',
		'no' => 'Norsk',
		'nr' => 'Ndébélé',
		'nv' => 'Diné bizaad',
		'ny' => 'ChiCheŵa',
		'oc' => 'Occitan',
		'oj' => 'ᐊᓂᔑᓈᐯᒧᐎᓐ',
		'om' => 'Afaan Oromoo',
		'or' => 'ଓଡ଼ିଆ',
		'os' => 'Ирон æвзаг',
		'pa' => 'ਪੰਜਾਬੀ',
		'pi' => 'पािऴ',
		'pl' => 'Polski',
		'ps' => 'پښتو',
		'pt' => 'Português',
		'pt-br' => 'Brasileiro',
		'qu' => 'Runa Simi',
		'rm' => 'Rumantsch grischun',
		'rn' => 'kiRundi',
		'ro' => 'Română',
		'ru' => 'Русский',
		'rw' => 'Kinyarwanda',
		'sa' => 'संस्कृतम्',
		'sc' => 'sardu',
		'sd' => 'सिन्धी',
		'se' => 'Davvisámegiella',
		'sg' => 'Yângâ tî sängö',
		'sh' => 'Српскохрватски',
		'si' => 'සිංහල',
		'sk' => 'Slovenčina',
		'sl' => 'Slovenščina',
		'sm' => 'Gagana fa\'a Samoa',
		'sn' => 'chiShona',
		'so' => 'Soomaaliga',
		'sq' => 'Shqip',
		'sr' => 'српски језик',
		'ss' => 'SiSwati',
		'st' => 'seSotho',
		'su' => 'Basa Sunda',
		'sv' => 'Svenska',
		'sw' => 'Kiswahili',
		'ta' => 'தமிழ்',
		'te' => 'తెలుగు',
		'tg' => 'тоҷикӣ',
		'th' => 'ไทย',
		'ti' => 'ትግርኛ',
		'tk' => 'Türkmen',
		'tl' => 'Tagalog',
		'tn' => 'seTswana',
		'to' => 'faka Tonga',
		'tr' => 'Türkçe',
		'ts' => 'xiTsonga',
		'tt' => 'татарча',
		'tw' => 'Twi',
		'ty' => 'Reo Mā`ohi',
		'ug' => 'Uyƣurqə',
		'uk' => 'українська',
		'ur' => 'اردو',
		'uz' => 'O\'zbek',
		've' => 'tshiVenḓa',
		'vi' => 'Tiếng Việt',
		'vo' => 'Volapük',
		'wa' => 'Walon',
		'wo' => 'Wollof',
		'xh' => 'isiXhosa',
		'yi' => 'ייִדיש',
		'yo' => 'Yorùbá',
		'za' => 'Saɯ cueŋƅ',
		'zh' => '中文',
		'zh-cn'	 => '中文简体',
		'zh-tw'	 => '中文繁體',
		'zu' => 'isiZulu'
	);
	
	if(isset($known[$code]))
		return $known[$code];
	
	return null;
}

// Checks if a translation exists
function existsTranslation($code) {
	return $code ? is_dir('../i18n/'.$code) : false;
}

// Lists all available translations
function listTranslation() {
	$available_lang = array();

	// List translation folder
	$scan_dir = scandir('../i18n');
	
	foreach($scan_dir as $current_lang) {
		// Check if lang is valid
		$current_name = localesTranslation($current_lang);
		
		if(!$current_name)
			continue;
		
		// Create lang sub-array
		$current_arr = array(
			'code'	=> $current_lang,
			'name'	=> $current_name
		);

		array_push($available_lang, $current_arr);
	}

	return $available_lang;
}

?>