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
		$email_account_password = 'secret';				// SMTP password

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