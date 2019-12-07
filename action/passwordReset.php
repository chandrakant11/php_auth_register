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

	// Can be used by the user to password reset
	$user = new User();
	$status = $user->resetPassword($_POST, $conn);

	if($status == 'success') {
		echo json_encode([
			'success' => 'success', 
			'msg' => '<p class="alert alert-success">Your Password Successfully Recover! Please Login</p>',
			'url' => domain_url().'profile.php'
		]);
	} else if($status == 'error') {
		echo json_encode([
			'error' => 'error', 
			'msg' => '<p class="alert alert-danger">Failed to change password!, Retry</p>'
		]);
	} else if($status == 'request destroy') {
		echo json_encode([
			'error' => 'error', 
			'msg' => '<p class="alert alert-danger">recover password request link expire!</p>'
		]);
	} else if($status == 'user id exists') {
		echo json_encode([
			'error' => 'error', 
			'msg' => '<p class="alert alert-danger">invalid password reset id!</p>'
		]);
	} else if($status == 'user exists') {
		echo json_encode([
			'error' => 'error', 
			'msg' => '<p class="alert alert-danger">Incurrect password reset email!</p>'
		]);
	} else if($status == 'invalid password') {
		echo json_encode([
			'error' => 'error', 
			'msg' => '<p class="alert alert-danger">Least One Letter Uppercase, Number, Special sign(!@#$%&*) and there have to be 8-20 characters</p>'
		]);
	} else if($status == 'mismatch password') {
		echo json_encode([
			'error' => 'error', 
			'msg' => '<p class="alert alert-danger">confirm password mismatch, enter currect password!</p>'
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