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

	// Can be used by the user to change password
	$user = new User();
	$status = $user->changePassword($_POST, $conn);

	if($status == 'success') {
		echo json_encode([
			'success' => 'success', 
			'msg' => '<p class="alert alert-success">Your Password Successfully Changed!</p>',
			'url' => domain_url().'profile.php'
		]);
	} else if($status == 'error') {
		echo json_encode([
			'error' => 'error', 
			'msg' => '<p class="alert alert-danger">Failed to change password!</p>'
		]);
	} else if($status == 'password exists') {
		echo json_encode([
			'error' => 'error', 
			'msg' => '<p class="alert alert-danger">Incurrect old password!</p>'
		]);
	} else if($status == 'token exists') {
		echo json_encode([
			'error' => 'error', 
			'msg' => '<p class="alert alert-danger">refresh this page and retry!</p>'
		]);
	} else if($status == 'missing token') {
		echo json_encode([
			'error' => 'error', 
			'msg' => '<p class="alert alert-danger">missing change password request token!</p>'
		]);
	} else if($status == 'invalid password') {
		echo json_encode([
			'error' => 'error', 
			'msg' => '<p class="alert alert-danger">Least One Letter Uppercase, Number, Special sign(!@#$%&*) and there have to be 8-20 characters</p>'
		]);
	} else if($status == 'mismatch password') {
		echo json_encode([
			'error' => 'error', 
			'msg' => '<p class="alert alert-danger">confirm password mismatch. enter currect password!</p>'
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