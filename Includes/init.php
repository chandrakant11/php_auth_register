<?php
/*
  * PHP_auth_register (https://github.com/chandrakant11/php_auth_register)
*/

  session_start();
	
  /*
	* Reference https://github.com/PHPMailer/PHPMailer
	* Load Composer's autoloader
  */
	require_once __DIR__ .'/path/vendor/autoload.php';

  /*
	* include function files
  */
	require_once __DIR__ .'/../Functions/function.php';
	require_once __DIR__ .'/../Functions/token.function.php';
	require_once __DIR__ .'/../Functions/email.template.function.php';

  /*
	* autoload class files
  */
	spl_autoload_register(function($class){
		require_once __DIR__ .'/../Classess/'.$class.'.php';
	});

  /*
	* include database connection file
  */
	require_once __DIR__ .'/db.connection.php';

?>