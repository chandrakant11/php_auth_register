<?php
/*
  * PHP_auth_register (https://github.com/chandrakant11/php_auth_register)
*/

  session_start();
  require_once __DIR__ .'/../DB Shared/db_link.php';
  require_once __DIR__ .'/../Functions/function.php';
  extract($_SESSION);

	if(isset($login_key) && $login_key['is_login'] && $user_verify_token == $login_key['user_token']):
		header('location: '.domain_url().'profile.php');
		exit();
	endif;

?>
<!DOCTYPE html>
<html lang="in-en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie-edge">
	<title>PHP OOP Registration Install DB</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="../Public/css/install.page.css">
</head>
<body>
  <div class="heading mt-4">
	  <h2>Welcome Database Installation</h2>
	</div>
  <div class="container">
<?php
  /*
	@var DATABASE Table
  */
	$table = array("CREATE TABLE IF NOT EXISTS `users` (
		`Id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`Name` VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL,
		`Email` VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL,
		`Password` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
		`Verification` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL
		) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
	);

  // * Connect Hosing
	$conn = new mysqli(DB_HOST, DB_USER, DB_PWD);
	echo "<h2>Conneting<span>.</span><span>.</span><span>.</span></h2>";

	if(!$conn) {
		die("<b class='text-danger'>Server Connetion Failed:</b>". $conn->connect_error());
		exit();
	} else {
		echo "<h5><b>Server Connection:</b><span class='text-success ml-2'>Good</span></h5>";
		/*
		  * select @db name
		  * confirm @db already installed
		*/
		if($conn->select_db(DB_NAME)) {
			echo "<h5 class='text-warning'>Database Already Installed...</h5>";
		} else {
			/*
			  * confirmation false
			  * create @db
			*/
			$crd_db = $conn->query("CREATE DATABASE ".DB_NAME);

			if($crd_db) {
				echo "<h5 class='text-success'>Database Successfully Installed!!!</h5>";

			/*
			  * Create @Table in @db
			  * @var $table is defined above
			*/
				foreach($table as $array) {
					$conn->select_db(DB_NAME);
					$crd_table = $conn->query($array);
				}

			/*
			  * confirm @table successfully created
			*/
				if($crd_table) {
					echo "<i class='text-success'>Table Successfully Created in <u>".DB_NAME."</u> Database</i>";
				} else {
					echo "</br><b class='text-danger'>Table Creation Failed:</b>". $conn->error;
				}
			} else {
				echo "</br><b class='text-danger'>Database Creation Failed:</b>". $conn->error;
				exit();
			}
		}
		$conn->close();
	}

?>
	<div class="home-link">
	  <p>GoTo PHP OOP Registration <a href="<?= domain_url()?>">Home Page</a></p>
	</div>
  </div>
</body>
</html>