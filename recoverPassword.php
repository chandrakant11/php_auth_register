<?php
/*
  * PHP_auth_register (https://github.com/chandrakant11/php_auth_register)
*/

  session_start();
  require_once __DIR__ .'/Functions/function.php';
  extract($_SESSION);

	if(isset($login_key) && $login_key['is_login'] && $user_verify_token == $login_key['user_token']):
		header('location: '.domain_url());
		exit();
	elseif(!isset($_GET) || empty($_GET['email']) || empty($_GET['id'])):
		header('location: '.domain_url());
		exit();
	endif;

?>

<!DOCTYPE html>
<html lang="in-en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie-edge">
	<title>Reset Password</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="Public/css/myStyle.css">
</head>
<body>
	<div class="container col-12 col-md-8">
	  <nav class="navbar navbar-expand-sm navbar-dark bg-dark h-25">
		<div class="container-fluid">
		  <a class="navbar-brand" href="<?= domain_url()?>">PHP Auth Register</a>
		  <div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav ml-auto">
			  <li class="nav-item">
			  	<h5 class="text-light">Create New Password</h5>
			  </li>
			</ul>
		  </div>
		</div>
	  </nav>
	</div>
<!-- change password form-->
	<div class="container">
	  <div class="modal-body col-12 col-md-7 mx-auto">
	    <form action="<?= domain_url()?>action/passwordReset.php" method="post" class="form" autocomplete="off">
		  <input type="hidden" name="r_email" value="<?= $_GET['email'];?>"/>
		  <input type="hidden" name="r_id" value="<?= $_GET['id'];?>"/>
	      <div class="form-group">
			<label for="password">New Password:</label>
			<input type="password" name="n_pwd" class="form-control" placeholder="Password"/>
	      </div>
	      <div class="form-group">
			<label for="password">Confirm New Password:</label>
			<input type="password" name="c_pwd" class="form-control" placeholder="Password"/>
	      </div>
	      <div class="btn-group d-flex mt-2">
			<input type="submit" class="btn btn-primary" value="Reset Password"/>
	      </div>
	    </form>
	  </div>
	  <div class="modal-footer justify-content-center">
		
	  </div>
	</div>

<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<script type="text/javascript" src="Public/javascript/myScript.js"></script>
</body>
</html>