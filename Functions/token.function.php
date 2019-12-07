<?php
/*
  * PHP_auth_register (https://github.com/chandrakant11/php_auth_register)

  ** Generate Security CSRF Tokens
*/

  /*
	* for login html page(index.php) token
	* @return 32 bit @bin2hex algorithm
  */
	function login_token() {
        $token = bin2hex(random_bytes(32));
        $_SESSION['login_token'] = $token;
        return $token;
    }

  /*
	* for signup html page(index.php) token
	* @return 32 bit @bin2hex algorithm
  */
	function signup_token() {
        $token = bin2hex(random_bytes(32));
        $_SESSION['signup_token'] = $token;
        return $token;
    }

  /*
	* for password change html page(profile.php) token
	* @return 32 bit @bin2hex algorithm
  */
	function cng_pwd_token() {
        $token = bin2hex(random_bytes(32));
        $_SESSION['cng_pwd_token'] = $token;
        return $token;
    }

  /*
	* for forgot password html page(index.php) token
	* @return 32 bit @bin2hex algorithm
  */
	function new_pwd_token() {
        $token = bin2hex(random_bytes(32));
        $_SESSION['new_pwd_token'] = $token;
        return $token;
    }

  /*
	** Generate Security Session Hijecking Token
	* @return 32 bit @bin2hex algorithm
  */
	function user_token() {
        $token = bin2hex(random_bytes(32));
        $_SESSION['user_verify_token'] = $token;
        return $token;
    }

?>