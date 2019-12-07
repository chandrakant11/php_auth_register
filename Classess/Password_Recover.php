<?php

/*
  * PHP_auth_register (https://github.com/chandrakant11/php_auth_register)
*/

  // Class that can be used for password recovery task by and requested users
final class Password_Recover extends External_Class {

  /*
	* Password recovery request for the user with the given ID
	*
	* ID of the user whose forgot-password to reset
	* @param array $post: string $new_pwd_token and $email
	* @param $conn: database connection
	* @return string 'missing fields' if all form fields are not obtained - false
	* @return string 'token exists' if signup CSRF token is invalid
	* @return string 'user exists' if a user with the specified email address already exists
	* @return string 'error' if an internal problem occurred (do *not* catch)
	* @return string 'failed' if the password recovery request is not sent to the user's email
	* @return string 'success' if the password recovery request is sent in the user's email
  */
	public function sendRecoveryMail($post, $conn) {
		extract($post);

		if(!in_array(null, $post, false)) {
			if(isset($new_pwd_token) && $new_pwd_token === $_SESSION['new_pwd_token']) {
				if($this->emailExists($email, $conn)) {
					$sql = "SELECT * FROM `Users` WHERE `Email`=?";
					$stmt = $conn->prepare($sql);

					if(is_object($stmt)) {
						$stmt->bind_param('s', $email);
						$stmt->bind_result($Id, $Name, $Email, $Password, $verifyId);
						$stmt->execute();

						if($stmt->fetch()) {
							$user_details = [
								'Name' => $Name,
								'Email' => $Email,
								'Verify_Id' => $verifyId
							];
							$html_mail_template = pwdRecoverMailMsg($user_details);
							$mail_subject = '[PHP OOP Registration] please reset your password';

							if($this->mailEngine($Name, $Email, $html_mail_template, $mail_subject)) {
								count_down($verifyId);
								return 'success';
							}
							return 'failed';
						}
						return 'error';
					}
				}
				return 'user exists';
			}
			return 'token exists';
		}
		return 'missing fields';
	}
}

?>