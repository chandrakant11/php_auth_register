<?php
/*
  * PHP_auth_register (https://github.com/chandrakant11/php_auth_register)
*/

  session_start();
  require_once __DIR__ .'/Functions/function.php';
  require_once __DIR__ .'/Functions/token.function.php';
  extract($_SESSION);

	if(!isset($login_key) || !$login_key['is_login'] || $user_verify_token != $login_key['user_token']):
		header('location: '.domain_url());
		exit();
	elseif(!empty($login_key['remember_me']) && $login_key['remember_me'] != 0):
		setcookie('userLoginId', $login_key['user_email'], time()+60*60*24, '/', false, false);
		setcookie('userLoginPwd', $login_key['user_pwd'], time()+60*60*24, '/', false, false);
	elseif(isset($_GET['email'], $_GET['token'], $_GET['verify'])):
		session_write_close();
		$url = domain_url().'/action/verifyNewUser.php';
		$data = array(
			'email' => $_GET['email'],
			'token' => $_GET['token'],
			'hex_email' => $_GET['verify']
		);
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
		curl_close($curl);
	endif;

?>

<!DOCTYPE html>
<html lang="in-en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie-edge">
	<title><?= $_SESSION['login_key']['user_name'];?> Profile</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="Public/css/myStyle.css">
</head>
<body>
	<div class="container">
	  <nav class="navbar navbar-expand-sm navbar-dark bg-dark h-25">
		<div class="container-fluid">
		  <a class="navbar-brand" href="<?= domain_url()?>">PHP OOP Registration</a>
		  <div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav ml-auto">
			  <li class="nav-item">
			  	<a class="nav-link" title="change password" href="#pwd-modal" data-toggle="modal">Change Password</a>
			  </li>
			  <li class="nav-item">
			  	<a class="nav-link" title="logout" href="<?= domain_url()?>logout.php">Logout</a>
			  </li>
			</ul>
		  </div>
		</div>
	  </nav>
	  <div class="jumbotron">
		<div class="col-12 col-md-6 mx-auto">
		  <?php
			if(isset($response)):
				$response = json_decode($response);
				// stdClass Object
				echo $response->msg;
			endif;
		  ?>
		</div>
		<h2>Welcome</h2>
		<h5><?= $_SESSION['login_key']['user_name'];?></h5>
	  </div>
	</div>

	<!-- change password modal -->
	<div class="modal fade" id="pwd-modal" tabindex="-1" role="dialog" aria-labelledbye="mySmallModalLable" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content px-3 pt-3">
		  <div class="modal-header">
			<h3 class="modal-title">Change Password</h3>
			<button type="button" data-dismiss="modal" class="close" aria-label="close"><span title="Close" aria-hidden="true">&times;</span></button>
		  </div>
		  <div class="modal-body">
			<form action="<?= domain_url()?>action/changePassword.php" method="post" class="form" autocomplete="on">
			  <input type="hidden" name="change_pwd_token" value="<?= cng_pwd_token()?>"/>
			  <div class="form-group">
				<label for="opwd">Current Password:</label>
				<input type="password" name="o_pwd" class="form-control" id="opwd" placeholder="Password"/>
			  </div>
			  <div class="form-group">
				<label for="npwd">New Password:</label>
				<input type="password" name="n_pwd" class="form-control" id="npwd" placeholder="New Password"/>
			  </div>
			  <div class="form-group">
				<label for="cpwd">Confirm New Password:</label>
				<input type="password" name="c_pwd" class="form-control" id="cpwd" placeholder="Confirm Password"/>
			  </div>
			  <div class="btn-group d-flex mt-2">
				<button type="submit" class="btn btn-primary">Change Password</button>
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