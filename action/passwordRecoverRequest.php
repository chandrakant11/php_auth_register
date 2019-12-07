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
  * Can be used by the user to request password reset
  * You must never pass untrusted input to the parameter that takes the user input
  * user email and new_pwd_token sanitize and validate
*/
	$sanitize_validate_post = recover_sanitize_validate_filter();

	$mail = new Password_Recover();
	$status = $mail->sendRecoveryMail($sanitize_validate_post, $conn);

	if($status == 'success') {
		echo json_encode([
			'success' => 'success', 
			'msg' => '<p class="alert alert-success">We send you an email with password reset link!</p>',
			'url' => domain_url()
		]);
	} else if($status == 'error') {
		echo json_encode([
			'error' => 'error', 
			'msg' => '<p class="alert alert-danger">Failed password reset link!</p>'
		]);
	} else if($status == 'failed') {
		echo json_encode([
			'error' => 'error', 
			'msg' => '<p class="alert alert-danger">server down!</p>'
		]);
	} else if($status == 'user exists') {
		echo json_encode([
			'error' => 'error', 
			'msg' => '<p class="alert alert-danger">wrong email, please enter correct email!</p>'
		]);
	} else if($status == 'token exists') {
		echo json_encode([
			'error' => 'error', 
			'msg' => '<p class="alert alert-danger">refresh this page and retry for request!</p>'
		]);
	} else if($status == 'missing token') {
		echo json_encode([
			'error' => 'error', 
			'msg' => '<p class="alert alert-danger">goto <a href="'.domain_url().'">offical website</a></p>'
		]);
	} else if($status == 'missing fields') {
		echo json_encode([
			'error' => 'error', 
			'msg' => '<p class="alert alert-danger">missing fields mandatory!</p>'
		]);
	};

  else:
	header('location: '.domain_url());
	exit();
  endif;

?>