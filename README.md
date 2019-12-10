# php_auth_register

**This is an authentic register project created in PHP**

php_auth_register is designed keeping in mind the beginning programmer in the PHP programming language. It is Simple, lightweight and secure.

## What will you learn?

  * From the register of new users to authentication processes
  * Verifying new user account via email
  * Changing password and recover a forgotten password by email
  * PHP sanitize and validate filtering user input details
  * Password Security, Complexity, Length
  * CSRF Protection
  * Session Hijacking Protection
  * Cookie Storage

## Requirements

  * PHP 5.6.0+
  * MySQL 5.5.3+
  * Bootstrap 4+
  * jQuery 3.4+

## Getting Started

  ### Download
  
  You can download this project in your system as direct or zip file, by clicking on the 'clone or download' button.

  ### Create DataBase:

	http://localhost/php_auth_register/Install/index.php

  index.php will dynamically create a table by setting up the database in PHPMyAdmin

  ### PHPMailer Library:

  The email will be sent to the user via PHPMailer. If you do not know about PHPMailer, visit then [PHPMailer_GitHub](https://github.com/PHPMailer/PHPMailer).

  Load Composer's autoloader
  Includes/init.php in line no. 12
	<?php

	  /*
	    * PHP_auth_register (https://github.com/chandrakant11/php_auth_register)
	  */
		session_start();

	  /*
	    * Reference https://github.com/PHPMailer/PHPMailer
	    * Load Composer's autoloader
	  */
	  	require_once __DIR__ .'/path/vendor/autoload.php';

	  /*
	    * include function files
	  */
	  	require_once __DIR__ .'/../Functions/function.php';
	  	require_once __DIR__ .'/../Functions/token.function.php';
	  	require_once __DIR__ .'/../Functions/email.template.function.php';

	  /*
	    * autoload class files
	  */
	  	spl_autoload_register(function($class){
	  		require_once __DIR__ .'/../Classess/'.$class.'.php';
	  	});

	  /*
	    * include database connection file
	  */
	  	require_once __DIR__ .'/db.connection.php';	
	?>

  ### Set SMTP username and password
  
  Classess/External_Class.php

  Set the SMTP username and SMTP password in the @variable defined in the mailEngine method of External_Class. It is free to change the @variable $from_mail_sender_name as needed.
  
  You need to set $ mail-> SMTPDebug, $ mail-> Host & $ mail-> SMTPSecure in PHPMailer Server settings. The remainder will be set via variable and method parameters.
  
	<?php
	  /*
	  * PHP_auth_register (https://github.com/chandrakant11/php_auth_register)
	  */
	  
	  /*
	  * Class that can be inherited by child classes
	  */
	  
	  use PHPMailer\PHPMailer\PHPMailer;
	  use PHPMailer\PHPMailer\SMTP;
	  use PHPMailer\PHPMailer\Exception;
	  
	  Abstract class External_Class {
	  
	  /*
	  	* User exists with the given email address
	  	*
	  	* @param array $post: string $email
	  	* @param $conn: database connection
	  	* @return bool true if $password matches via Regular Expressions
	  	* @return bool false if $password does not match via Regular Expressions
	  */
	  	final protected function emailExists($email, $conn) {
	  	  $sql = "SELECT * FROM `Users` WHERE `Email`=?";
	  	  $stmt = $conn->prepare($sql);
		  
	  	  if(is_object($stmt)) {
	  	  	$stmt->bind_param('s', $email);
	  	  	$stmt->execute();
	  	  	$stmt->store_result();

	  	  	if($stmt->num_rows) {
	  	  		return true;
	  	  	}
	  	  	return false;
	  	  }
	  	}
	  
	  /*
	  	* mailEngine send to mail specific User id
	  	*
	  	* @param string $user_name User Name
	  	* @param string $user_email User Email
	  	* @param string $html_mail_template Email Template
	  	* @param string $subject Email Subject
	  	* @return bool true if email successfully sent
	  	* @return bool false if email not sent
	  */
	  	final protected function mailEngine($user_name, $user_email, $html_mail_template, $subject) {
	  
	  	  $from_mail_sender_name = 'chandrakant team';	// 'Mailer'
	  	  $form_email_account_id = 'user@example.com';	// SMTP username
	  	  $email_account_password = 'secret';			// SMTP password
	  
	  	/*
	  	* Reference https://github.com/PHPMailer/PHPMailer
	  	* sending mail via PHPMailer
	  	*/
	  	  $mail = new PHPMailer(true);
	  	  try {
	  	  	//Server settings
	  	  	$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
	  	  	$mail->isSMTP();                                            // Send using SMTP
	  	  	$mail->Host       = 'smtp1.example.com';                    // Set the SMTP server to send through
	  	  	$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
	  	  	$mail->Username   = $form_email_account_id;					// SMTP username
	  	  	$mail->Password   = $email_account_password;				// SMTP password
	  	  	$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
	  	  	$mail->Port       = 587;                                    // TCP port to connect to
	  	  
	  	  	//Recipients
	  	  	$mail->setFrom($form_email_account_id, $from_mail_sender_name); // 'from@example.com'
	  	  	$mail->addAddress($user['Email'], $user['Name']);	// Add a recipient 'joe@example.net', 'Joe User'
	  	  
	  	  	// Content
	  	  	$mail->isHTML(true);						// Set email format to HTML
	  	  	$mail->Subject = $subject;					// 'Here is the subject'
	  	  	$mail->Body    = $html_mail_template;		// 'This is the HTML message body <b>in bold!</b>'
	  	  	$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
	  	  
	  	  	$mail->send();
	  	  	return true;
	  	  } catch (Exception $e) {
	  	  	return false;
	  	  }
	  	}
	  }
	
	?>
	
  This project has been successfully tested. You'll find plenty more to play with the project.
	
## Contributing

Your contribution will be appreciated. Please create an issue for this so that your speciality, problem or question can be discussed.
