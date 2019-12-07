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
  * Can be used by the user to authenticate
  * You must never pass untrusted input to the parameter that takes the user input
  * user email and password sanitize and validate
*/
	$sanitize_validate_post = login_sanitize_validate_filter();

	if(empty($sanitize_validate_post['email'])):
		echo json_encode([
			'error' => 'error', 
			'msg' => '<p class="alert alert-danger">invalid email! not allow bracket, comma, quotes</p>'
		]);
	else:
		$user = new User();
		$status = $user->authentication($sanitize_validate_post, $conn);

		if($status == 'success') {
			echo json_encode([
				'success' => 'success', 
				'msg' => '<p class="alert alert-success">You are Successfully Login</p>',
				'url' => domain_url().'profile.php'
			]);
		} else if($status == 'wrong password') {
			echo json_encode([
				'error' => 'error', 
				'msg' => '<p class="alert alert-danger">Incorrect password!</p>'
			]);
		} else if($status == 'error') {
			echo json_encode([
				'error' => 'error', 
				'msg' => '<p class="alert alert-danger">Server Failed!</p>'
			]);
		} else if($status == 'user exists') {
			echo json_encode([
				'error' => 'error', 
				'msg' => '<p class="alert alert-danger">email not registrar, please signup!</p>'
			]);
		} else if($status == 'token exists') {
			echo json_encode([
				'error' => 'error', 
				'msg' => '<p class="alert alert-danger">refresh this page and retry login!</p>'
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