<?php
/*
  * PHP_auth_register (https://github.com/chandrakant11/php_auth_register)
*/

  session_start();
  session_regenerate_id(true);
  require_once __DIR__ .'/Functions/function.php';
  require_once __DIR__ .'/Functions/token.function.php';
  extract($_SESSION);

	// Check if $_SESSION and $_COOKIE already set
	if(isset($login_key) && $login_key['is_login'] && $user_verify_token == $login_key['user_token']):
		header('location: '.domain_url().'profile.php');
		exit();
	elseif(isset($_COOKIE['userLoginId'], $_COOKIE['userLoginPwd'])):
		if(!empty($_COOKIE['userLoginId']) && !empty($_COOKIE['userLoginPwd'])):
			$url = domain_url().'/action/authentication.php';
			$data = array(
				'login_token' => login_token(),
				'remember_me' => 1,
				'email' => $_COOKIE['userLoginId'],
				'pwd' 	=> $_COOKIE['userLoginPwd']
			);
			session_write_close();
			// Initialize a CURL session
			$curl = curl_init();
	
			curl_setopt_array($curl, array(
					//grab URL and pass it to the variable
						CURLOPT_URL => $url,
					// Enable the post response
						CURLOPT_POST => true,
					// The data to transfer with the response
						CURLOPT_POSTFIELDS => http_build_query($data),
					// Set the result output to be a string
						CURLOPT_RETURNTRANSFER => true,
						CURLOPT_COOKIESESSION => true,
						CURLOPT_COOKIE => session_name().'='.session_id()
					));
			// Execution and return result
			$response = curl_exec($curl);
			$response = json_decode($response);
			curl_close($curl);
			header('location: '.$response->url);
		endif;
	endif;

?>

<!DOCTYPE html>
<html lang="in-en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie-edge">
	<title>PHP Auth Register</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="Public/css/myStyle.css">
</head>
<body>
  <div class="container">
	<nav class="navbar navbar-expand-sm navbar-dark bg-dark h-25">
	  <div class="container-fluid">
		<a class="navbar-brand" href="<?= domain_url()?>">PHP Auth Register</a>
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
		  <ul class="navbar-nav ml-auto">
			<li class="nav-item">
			  <a class="nav-link" title="login" href="#login-modal" data-toggle="modal">Log In</a>
			</li>
			<li class="nav-item">
			  <a class="nav-link" title="signup" href="#signup-modal" data-toggle="modal">Sign Up</a>
			</li>
		  </ul>
		</div>
	  </div>
	</nav>
	<div class="jumbotron">
	  <h1>Hello, Visitor!</h1>
	  <p>We Welcome you to our awesome website where you can learn about AJAX login, signup, change password and forgot password by email</p>
	  <button class="btn btn-primary btn-lg" role="button" title="Sign Up" data-target="#signup-modal" data-toggle="modal">Join Our Team Today</button>
	</div>
  </div>

<!-- login modal -->
  <div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledbye="mySmallModalLable" aria-hidden="true">
	<div class="modal-dialog" role="document">
	  <div class="modal-content px-3 pt-3">
		<div class="modal-header">
		  <h3 class="modal-title">Login</h3>
		  <button type="button" data-dismiss="modal" class="close" aria-label="close"><span title="Close" aria-hidden="true">&times;</span></button>
		</div>
		<div class="modal-body">
		  <form action="<?= domain_url()?>action/authentication.php" method="post" class="form" autocomplete="on">
			<input type="hidden" name="login_token" value="<?= login_token();?>"/>
			<div class="form-group">
			  <label for="lemail">Email:</label>
			  <input type="email" name="email" class="form-control" id="lemail" placeholder="Email"/>
			</div>
			<div class="form-group">
			  <label for="lpwd">Password:</label>
			  <input type="password" name="pwd" class="form-control" id="lpwd" placeholder="Password"/>
			</div>
			<div class="custom-control custom-checkbox d-inline-block">
			  <input type="hidden" name="remember_me" value="0"/>
			  <input type="checkbox" class="custom-control-input" name="remember_me" value="1" id="rmb"/>
			  <label for="rmb" class="custom-control-label">Remember Me</label>
			</div>
			<a href="#reset-modal" id="forgot-link" class="float-right" data-toggle="modal">
			  <i>forgot password</i>
			</a>
			<div class="btn-group d-flex mt-4">
			  <button type="submit" class="btn btn-primary">Login here</button>
			</div>
		  </form>
		</div>
		<div class="modal-footer justify-content-center"></div>
	  </div>
	</div>
  </div>

<!-- reset password modal -->
  <div class="modal fade" id="reset-modal" tabindex="-1" role="dialog" aria-labelledbye="mySmallModalLable" aria-hidden="true">
	<div class="modal-dialog" role="document">
	  <div class="modal-content px-3 pt-3">
		<div class="modal-header">
		  <h3 class="modal-title">Password Recover</h3>
		  <button type="button" data-dismiss="modal" class="close" aria-label="close"><span title="Close" aria-hidden="true">&times;</span></button>
		</div>
		<div class="modal-body">
		  <form action="<?= domain_url()?>action/passwordRecoverRequest.php" method="post" class="form" autocomplete="on">
			<input type="hidden" name="new_pwd_token" value="<?= new_pwd_token()?>"/>
			<div class="form-group">
			  <label for="remail">Register Email:</label>
			  <input type="email" name="email" class="form-control" id="remail" placeholder="Email"/>
			</div>
			<div class="btn-group d-flex mt-2">
			  <button type="submit" class="btn btn-success">Generate Password Recover Link</button>
			</div>
		  </form>
		</div>
		<div class="modal-footer justify-content-center"></div>
	  </div>
	</div>
  </div>

<!-- signup modal -->
  <div class="modal fade" id="signup-modal" tabindex="-1" role="dialog" aria-labelledbye="mySmallModalLable" aria-hidden="true">
	<div class="modal-dialog" role="document">
	  <div class="modal-content px-3 pt-3">
		<div class="modal-header">
		  <h3 class="modal-title">New User Registration</h3>
		  <button type="button" data-dismiss="modal" class="close" aria-label="close"><span title="Close" aria-hidden="true">&times;</span></button>
		</div>
		<div class="modal-body">
		  <form action="<?= domain_url()?>action/registration.php" method="post" class="form" autocomplete="on">
			<input type="hidden" name="signup_token" value="<?= signup_token();?>"/>
			<div class="form-group">
			  <label for="name">Your Name:</label>
			  <input type="text" name="fname" class="form-control" id="name" placeholder="Name"/>
			</div>
			<div class="form-group">
			  <label for="email">Email:</label>
			  <input type="email" name="email" class="form-control" id="email" placeholder="Email"/>
			</div>
			<div class="form-group">
			  <label for="pwd">Password:</label>
			  <input type="password" name="pwd" class="form-control" id="pwd" placeholder="Password"/>
			</div>
			<div class="form-group">
			  <label for="cpwd">Confirm Password:</label>
			  <input type="password" name="c_pwd" class="form-control" id="cpwd" placeholder="Password"/>
			</div>
			<div class="btn-group d-flex mt-2">
			  <button type="submit" class="btn btn-success">Sign Up</button>
			</div>
		  </form>
		</div>
		<div class="modal-footer justify-content-center"></div>
	  </div>
	</div>
  </div>

<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<script type="text/javascript" src="Public/javascript/myScript.js"></script>
</body>
</html>