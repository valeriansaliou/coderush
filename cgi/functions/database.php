<?php

/*
 *	CodeRush
 *	Database Functions
 */

// Global storage
$OPENED_DATABASES = array();

// Connects to the given database
function openDatabase($name, $db = null) {
	global $OPENED_DATABASES, $CONFIG_SQL;
	
	// Check that we know this database
	if(isset($CONFIG_SQL[$name])) {
		// Read database values
		$current_db = $CONFIG_SQL[$name];
		
		// Database ID
		$db = $db ? $db : $current_db['db'];
		$db_id = $name.'-'.$db;
		
		// Not already opened?
		if(!isset($OPENED_DATABASES[$db_id])) {
			// Connect to database
			$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION; 
			$pdo_options[PDO::MYSQL_ATTR_INIT_COMMAND] = "SET NAMES utf8 COLLATE utf8_general_ci";
			
			$OPENED_DATABASES[$db_id] = new PDO('mysql:host='.$current_db['host'].';port='.$current_db['port'].';dbname='.($db ? $db : $current_db['db']), $current_db['usr'], $current_db['pwd'], $pdo_options);
		}
	} else {
		return null;
	}
	
	return $OPENED_DATABASES[$db_id];
}

// Disconnect from the given database
function closeDatabase($name) {
	global $OPENED_DATABASES;
	
	if(isset($OPENED_DATABASES[$name]))
		unset($OPENED_DATABASES[$name]);
}

?>