<?php
/*
  * PHP_auth_register (https://github.com/chandrakant11/php_auth_register)
*/

  session_start();
  require_once __DIR__ .'/Functions/function.php';

	if(isset($_SESSION['login_key'])) {
		sleep(2);
		setcookie(session_name(), session_id(), time()-1000, '/');
		session_destroy();
		
		if(isset($_COOKIE['userLoginId']) && isset($_COOKIE['userLoginPwd'])):
			setcookie('userLoginId', '', time()-1000, '/');
			setcookie('userLoginPwd', '', time()-1000, '/');
		endif;

		header('location: '.domain_url());
	} else {
		header('location: '.domain_url());
		exit();
	}

?>