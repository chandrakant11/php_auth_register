<?php
/*
  * PHP_auth_register (https://github.com/chandrakant11/php_auth_register)
*/

  require_once __DIR__ .'/../Includes/init.php';
  
  if(isset($login_key) && $login_key['is_login'] && $user_verify_token == $login_key['user_token']):
	header('location: '.domain_url().'profile.php');
	exit();
  endif;

  if(isset($_POST) && !empty($_POST)):

/*
  * Can be used by the user to verify account
  * You must never pass untrusted input to the parameter that takes the user input
  * user email, token and hex_email sanitize and validate
*/
  $sanitize_validate_post = verify_sanitize_validate_filter();

	if(!in_array(null, $sanitize_validate_post, false)):

		$user = new User();
		$status = $user->newUserVerify($sanitize_validate_post, $conn);
		
		if($status == 'success') {
			echo json_encode([
				'msg' => '<p class="alert alert-success">Your account is successfully verified!</p>'
			]);
		} else if($status == 'verified') {
			echo json_encode([
				'msg' => '<p class="alert alert-danger">Your account is already verified!</p>'
			]);
		} else if($status == 'user exists') {
			echo json_encode([
				'msg' => '<p class="alert alert-danger">Your account verification failed! Please Retry</p>'
			]);
		};
	else:
		echo json_encode([
			'msg' => '<p class="alert alert-danger">Invalid Email Id!</p>'
		]);
	endif;
  
  else:
	header('location: '.domain_url());
	exit();
  endif;

?>