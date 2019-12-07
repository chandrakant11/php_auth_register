<?php
/*
  * PHP_auth_register (https://github.com/chandrakant11/php_auth_register)
*/

  /*
	* Generate Verification Hash Code
	* @return 32 bit @md5 algorithm
  */
	function verificationCode() {
		return md5(random_bytes(32));
	}

  /*
	* Debug code and find error
	* only for developer
	* @param array, string, ini, method, boolean
  */
	function debug($arg) {
		echo '<pre>'.print_r($arg).'</pre>';
		exit();
	}

  /*
	* this website domain name
	* global function
	* @return domail for Form Action
  */
	function domain_url() {
		$url = 'http://localhost/PHP_auth_register/';
		return $url;
	}

  /*
	* create a session for forgot password request link
	* forgot password request link count down the minutes after sending in the mail
	* @param 32 bit hash code user verify
	* @return null
  */
	function count_down($verifyCode) {
		session_regenerate_id(true);
		$_SESSION['count_down'] = [
			'statTime' => time()+60*10,
			'id'	   => $verifyCode
		];
		return null;
	}

  /*
	* destroy count_down session and expire the Forgot Password Request link
	* @return null
  */
	function destroy_count_down() {
		if(isset($_SESSION['count_down'])) {
			unset($_SESSION['setting']);
		}
		return null;
	}

  /*
	* user email and password sanitize and validate
	* @return filtered input data
  */
	function login_sanitize_validate_filter() {
		$filters = array (
			"login_token" => FILTER_SANITIZE_STRING,
			"remember_me" => FILTER_SANITIZE_STRING,
			"email" => FILTER_SANITIZE_EMAIL,
			"email" => FILTER_VALIDATE_EMAIL,
			"pwd" 	=> FILTER_SANITIZE_STRING
		);
		return filter_input_array(INPUT_POST, $filters);
	}

  /*
	* user name, email, password and signup_token sanitize and validate
	* @return filtered input data
  */
	function signup_sanitize_validate_filter() {
		$filters = array (
			"signup_token" => FILTER_SANITIZE_STRING,
			"fname" => array ("filter"=>FILTER_CALLBACK,
					"flags"=>FILTER_FORCE_ARRAY,
					"options"=>"ucwords"),
			"fname" => array ("filter"=>FILTER_VALIDATE_REGEXP,
					"options" => array (
					"regexp" => '/^[A-Za-z](?!.*\d)(?!.*[~!@#$%&*?,.<>()\/[\]{}\'`"])/')),
			"email" => FILTER_SANITIZE_EMAIL,
			"email" => FILTER_VALIDATE_EMAIL,
			"pwd" 	=> FILTER_SANITIZE_STRING,
			"c_pwd" => FILTER_SANITIZE_STRING
		);
		return filter_input_array(INPUT_POST, $filters);
	}

  /*
	* user email, token and hex_email sanitize and validate
	* @return filtered input data
  */
	function verify_sanitize_validate_filter() {
		$filters = array (
			"hex_email" => FILTER_SANITIZE_STRING,
			"token"  => FILTER_SANITIZE_STRING,
			"email"  => FILTER_SANITIZE_EMAIL,
			"email"  => FILTER_VALIDATE_EMAIL
		);
		return filter_input_array(INPUT_POST, $filters);
	}

  /*
	* user email and new_pwd_token sanitize and validate
	* @return filtered input data
  */
	function recover_sanitize_validate_filter() {
		$filters = array (
			"new_pwd_token" => FILTER_SANITIZE_STRING,
			"email" => FILTER_SANITIZE_EMAIL,
			"email" => FILTER_VALIDATE_EMAIL
		);
		return filter_input_array(INPUT_POST, $filters);
	}

?>