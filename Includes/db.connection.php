<?php
/*
  * PHP_auth_register (https://github.com/chandrakant11/php_auth_register)
*/

	require_once __DIR__ .'/../DB Shared/db_link.php';
	
	try {
		$conn = new mysqli(DB_HOST, DB_USER, DB_PWD, DB_NAME);
	}
	
	catch(Exception $e) {
		die("Database Connection Failed:". $e->getMessage());
	}

?>