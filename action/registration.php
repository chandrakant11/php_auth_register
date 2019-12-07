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
  * Can be used by the user to create a new account
  * You must never pass untrusted input to the parameter that takes the user input
  * user name, email password and signup_token sanitize and validate
*/
	$sanitize_validate_post = signup_sanitize_validate_filter();

	if(empty($sanitize_validate_post['fname'])):
	  echo json_encode([
		'error' => 'error', 
		'msg' => '<p class="alert alert-danger">numbers, special characters, bracket, comma and quotes are not allowed in the name!</p>'
	  ]);
	elseif(empty($sanitize_validate_post['email'])):
	  echo json_encode([
		'error' => 'error', 
		'msg' => '<p class="alert alert-danger">invalid email! not allow bracket, comma, quotes</p>'
	  ]);
	else:
		$user = new User();
		$status = $user->registration($sanitize_validate_post, $conn);

		if($status == 'success') {
			echo json_encode([
				'success' => 'success', 
				'msg' => '<p class="alert alert-success">You are Successfully Resister!</p></br></br>
						<p class="alert alert-primary">A verification link has been sent to your mailbox</p>',
				'url' => domain_url()
			]);
		} else if($status == 'error') {
			echo json_encode([
				'error' => 'error', 
				'msg' => '<p class="alert alert-danger">Server Failed!</p>'
			]);
		} else if($status == 'user exists') {
			echo json_encode([
				'error' => 'error', 
				'msg' => '<p class="alert alert-danger">you are already resistered</p>'
			]);
		} else if($status == 'token exists') {
			echo json_encode([
				'error' => 'error', 
				'msg' => '<p class="alert alert-danger">reload this page and retry!</p>'
			]);
		} else if($status == 'invalid password') {
			echo json_encode([
				'error' => 'error', 
				'msg' => '<p class="alert alert-danger">Least One Letter Uppercase, Number, Special sign(!@#$%&*) and there have to be 8-20 characters</p>'
			]);
		} else if($status == 'mismatch password') {
			echo json_encode([
				'error' => 'error', 
				'msg' => '<p class="alert alert-danger">confirm password mismatch! corret enter password</p>'
			]);
		} else if($status == 'missing fields') {
			echo json_encode([
				'error' => 'error', 
				'msg' => '<p class="alert alert-danger">missing fields mandatory!</p>'
			]);
		};
	endif;

  else:
	header('location: '.domain_url());
	exit();
  endif;

?>