<?php
/*
  * PHP_auth_register (https://github.com/chandrakant11/php_auth_register)
*/

  // Class that can be used for administrative tasks by and authorized users
final class User extends External_Class {

  /*
	* Password pattern match from the with the given specific password address
	*
	* @param string $password
	* @return bool true if $password matches via Regular Expressions
	* @return bool false if $password does not match via Regular Expressions
  */
	private function validPassword($password) {
		if(preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[!@#$%&*])[0-9A-Za-z!@#$%&*]{8,20}$/', $password)) {
			return true;
		}
		return false;
	}

  /*
	* Old password exists from the user with the given email address
	*
	* @param $email specified user login email
	* @param $verifyId specified user verification id
	* @param $o_pwd specified user current password
	* @param $conn database connection
	* @return bool true if the user's current password is correct
	* @return bool false if the user's current password is incorrect
  */
	private function oldPasswordExists($email, $verifyId, $o_pwd, $conn) {
		$sql = "SELECT * FROM `Users` WHERE `Email`=? AND `Verification`=?";
		$stmt = $conn->prepare($sql);

		if(is_object($stmt)) {
			$stmt->bind_param('ss', $email, $verifyId);
			$stmt->bind_result($Id, $Name, $Email, $Password, $verifyCode);
			$stmt->execute();
			$stmt->store_result();

			if($stmt->num_rows === 1) {
				$stmt->fetch();
				if(password_verify($o_pwd, $Password)) {
					return true;
				}
				return false;
			}
		}
		return false;
	}

  /*
	* Exists specific verification id from the user with the given email address
	*
	* @param $email specified user email
	* @param $verifyId specified user verification id
	* @param $conn database connection
	* @return bool true if verification id exist
	* @return bool false if verification id does not exist
  */
	private function userVerifyIdExists($email, $verifyId, $conn) {
		$sql = "SELECT * FROM `Users` WHERE `Email`=? AND `Verification`=?";
		$stmt = $conn->prepare($sql);

		if(is_object($stmt)) {
			$stmt->bind_param('ss', $email, $verifyId);
			$stmt->execute();
			$stmt->store_result();

			if($stmt->num_rows === 1) {
				return true;
			}
			return false;
		}
	}

  /*
	* Regenerate verification id of the user with the given email address
	*
	* @param $email specified user email
	* @param $conn database connection
	* @return bool true if verification is reGenerated
	* @return bool false if verification is not reGenerated
  */
	private function generateUserVerificationId($email, $conn) {
		$sql = "UPDATE `Users` SET `Verification`=? WHERE `Email`=?";
		$stmt = $conn->prepare($sql);

		if(is_object($stmt)) {
			$verifyCode = verificationCode();

			$stmt->bind_param('ss', $verifyCode, $email);
			$stmt->execute();

			if($stmt->affected_rows) {
				return true;
			}
			return false;
		}
	}

  /*
	* Verified of the user with the given email address
	*
	* @param $email specified user email
	* @param $conn database connection
	* @return bool true if the user account is verified
	* @return bool false if the user account is not verified
  */	
	private function isVerified($email, $conn) {
		$sql = "SELECT `Verification` FROM `Users` WHERE `Email`=?";
		$stmt = $conn->prepare($sql);

		if(is_object($stmt)) {
			$stmt->bind_param('s', $email);
			$stmt->bind_result($verifyCode);
			$stmt->execute();
			$stmt->store_result();

			if($stmt->num_rows === 1) {
				$stmt->fetch();
				if(!empty($verifyCode)) {
					return true;
				}
				return false;
			}
		}
	}

  /*
	* Email to verify a new user account that is currently logged in
	*
	* @return bool true if email successfully sent
	* @return bool false if email not sent
  */
	private function sendVerificationMail() {
		$user_name  = $_SESSION['login_key']['user_name'];
		$user_email = $_SESSION['login_key']['user_email'];
		$user_token = $_SESSION['login_key']['user_token'];
		// string email to hex code
		$encodeEmail = bin2hex($user_email);

		$user_details = [
			'Name' 	 => $user_name,
			'Email'  => $user_email,
			'Token'  => $user_token,
			'Verify_Id' => $encodeEmail
		];

	  /*
	    * dynamically create the email template
	    * accountVerifyEmailTemplate is a function
	    * @param array $user_details
	  */
		$html_mail_template = accountVerifyEmailTemplate($user_details);
		$mail_subject = '[PHP OOP Registration] Verify Your Account';

		// mailEngine External_Class method
		if($this->mailEngine($user_name, $user_email, $html_mail_template, $mail_subject)) {
			return true;
		}
		return false;
	}

  /*
	* Login as the user for which the @param with the specified email has the given value
	*
	* @return string 'user exists' if the user with the specified ID has been not found
	* @return string 'error' if an internal problem occurred (do *not* catch)
	* @return string 'wrong password' if the user has entered the wrong password
	* @return string 'success' int the ID of the user that has been created
  */
	private function authenticUserLogin($conn, $email, $pwd, $remember_me=0) {
		if($this->emailExists($email, $conn)) {
			$sql = "SELECT * FROM `Users` WHERE Email=?";
			$stmt = $conn->prepare($sql);

			if(is_object($stmt)) {
				$stmt->bind_param('s', $email);
				$stmt->bind_result($Id, $Name, $Email, $Password, $verifyCode);
				$stmt->execute();
				$stmt->store_result();

				if($stmt->num_rows === 1) {
					$stmt->fetch();
					if(password_verify($pwd, $Password)) {
						$_SESSION['login_key'] = [
							'is_login' 	  => true,
							'user_token'  => user_token(),
							'user_name'   => $Name,
							'user_email'  => $Email,
							'user_verify' => $verifyCode,
							'user_pwd'	  => $pwd,
							'remember_me' => $remember_me
						];
						return 'success';
					}
					return 'wrong password';
				}
				return 'error';
			}
		}
		return 'user exists';
	}
	
  /*
	* Login as the user with the specified ID
	*
	* @param array $post: string $login_token, ($email & $pwd of the user to sign in) and $remember_me
	* @param $conn: database connection
	* @return string 'missing fields' If all form fields are not obtained - false
	* @return string 'token exists' if login CSRF token is invalid
  */
	public function authentication($post, $conn) {
		extract($post);

		if(!in_array(null, $post, false)) {
			if(isset($login_token) && $login_token == $_SESSION['login_token']) {
				/*
				  * pass login details for the next step
				  * @param $conn: database connection
				  * @param extract $post array
				*/
				return $this->authenticUserLogin($conn, $email, $pwd, $remember_me);
			}
			return 'token exists';
		}
		return 'missing fields';
	}
	
  /*
	* Creates a new user
	*
	* @param array $post: string $signup_token, $name, $email $pwd and $c_pwd
	* @param $conn: database connection
	* @return string 'missing fields' If all form fields are not obtained - false
	* @return string 'invalid password' if the password was invalid
	* @return string 'mismatch password' if password and confirm password not match
	* @return string 'token exists' if signup CSRF token is invalid
	* @return string 'user exists' if a user with the specified email address already exists
	* @return string 'error' if an internal problem occurred (do *not* catch)
	* @return string 'success' if the ID of the user that has been created (if any)
  */
	public function registration($post, $conn) {
		extract($post);
		// generate password_hash
		$hex_pwd = password_hash($pwd, PASSWORD_BCRYPT);

		if(!in_array(null, $post, false)) {
			if($pwd === $c_pwd) {
				if($this->validPassword($pwd)) {
					if(isset($signup_token) && $signup_token === $_SESSION['signup_token']) {
						if(!$this->emailExists($email, $conn)) {
							$sql = "INSERT INTO `Users` (`Name`, `Email`, `Password`) VALUES (?,?,?)";
							$stmt = $conn->prepare($sql);

							if(is_object($stmt)) {
								$stmt->bind_param('sss', $fname, $email, $hex_pwd);
								$stmt->execute();

								if($stmt->affected_rows) {
									// automatically login new user
									$this->authenticUserLogin($conn, $email, $pwd);
									// email to verify user account
									$this->sendVerificationMail();
									return 'success';
								}
								return 'error';
							}
						}
						return 'user exists';
					}
					return 'token exists';
				}
				return 'invalid password';
			}
			return 'mismatch password';
		}
		return 'missing fields';
	}
	
  /*
	* Changes the password for the user with the session ID
	*
	* @param array $post: string $change_pwd_token, $o_pwd, ($n_pwd and $c_pwd the new password to set)
	* @param $conn: database connection
	* @return string 'missing fields' If all form fields are not obtained - false
	* @return string 'mismatch password' if password and confirm password not match
	* @return string 'invalid password' if the password was invalid
	* @return string 'missing token' if not isset changePassword CSRF token
	* @return string 'token exists' if changePassword CSRF token is invalid
	* @return string 'password exists' If the old password of the user is not correct
	* @return string 'error' if an internal problem occurred (do *not* catch)
	* @return string 'success' if login user password changed
  */
	public function changePassword($post, $conn) {
		extract($post);
		/*
		  * session logged user_email & user_login_id
		  * generate password_hash
		*/
		$user_email  = $_SESSION['login_key']['user_email'];
		$user_verify = $_SESSION['login_key']['user_verify'];
		$hex_pwd 	 = password_hash($n_pwd, PASSWORD_BCRYPT);

		if(!in_array(null, $post, false)) {
			if($n_pwd === $c_pwd) {
				if($this->validPassword($n_pwd)) {
					if(!empty($change_pwd_token)) {
						if($change_pwd_token == $_SESSION['cng_pwd_token']) {
							if($this->oldPasswordExists($user_email, $user_verify, $o_pwd, $conn)) {
								$sql = "UPDATE `Users` SET `Password`=? WHERE `Email`=?";
								$stmt = $conn->prepare($sql);

								if(is_object($stmt)) {
									$stmt->bind_param('ss', $hex_pwd, $user_email);
									$stmt->execute();

									if($stmt->affected_rows) {
										// automatically login new user
										$this->authenticUserLogin($conn, $user_email, $n_pwd, 1);
										return 'success';
									}
									return 'error';
								}
							}
							return 'password exists';
						}
						return 'token exists';
					}
					return 'missing token';
				}
				return 'invalid password';
			}
			return 'mismatch password';
		}
		return 'missing fields';
	}

  /*
	* Reset password for the user with the given ID
	*
	* ID of the user whose forgot-password to reset
	* @param array $post: string $n_pwd and $c_pwd the new password to set
	* @param $conn: database connection
	* @return string 'missing fields' if all form fields are not obtained - false
	* @return string 'mismatch password' if password and confirm password not match
	* @return string 'invalid password' if the password was invalid
	* @return string 'user exists' if a user with the specified email address already exists
	* @return string 'user id exists' if the user verified ID is wrong
	* @return string 'request destroy' if the password recovery link has expired
	* @return string 'error' if an internal problem occurred (do *not* catch)
	* @return string 'success' if recovery request user password reset
  */
	public function resetPassword($post, $conn) {
		extract($post);
		extract($_SESSION);
		// generate password_hash
		$hex_pwd = password_hash($n_pwd, PASSWORD_BCRYPT);

		if(!in_array(null, $post, false)) {
			if($n_pwd === $c_pwd) {
				if($this->validPassword($n_pwd)) {
					if($this->emailExists($r_email, $conn)) {
						if($this->userVerifyIdExists($r_email, $r_id, $conn)) {
							if(isset($count_down)) {
								if($count_down['statTime']-time()>0 && $count_down['id']==$r_id) {
									$sql = "UPDATE `Users` SET `Password`=? WHERE `Email`=?";
									$stmt = $conn->prepare($sql);

									if(is_object($stmt)) {
										$stmt->bind_param('ss', $hex_pwd, $r_email);
										$stmt->execute();

										if($stmt->affected_rows) {
											// change automatically user id
											$this->generateUserVerificationId($r_email, $conn);
											// if user password reset. destroy countdown session
											destroy_count_down();
											return 'success';
										}
										return 'error';
									}
								}
								// if the password recovery link has expired. destroy countdown session
								destroy_count_down();
							}
							return 'request destroy';
						}
						// if the request user id is wrong then change automatically user id
						$this->generateUserVerificationId($r_email, $conn);
						return 'user id exists';
					}
					return 'user exists';
				}
				return 'invalid password';
			}
			return 'mismatch password';
		}
		return 'missing fields';
	}

  /*
	* New user account verification
	*
	* @param array $post: string $email, $token and $hex_email
	* @param $conn: database connection
	* @return string 'success' if the user has verified their email address via the confirmation method
	* @return string 'verified' if the user's account is already verified
	* @return string 'user exists' if the user with the specified $email Id has been not found
	* @return string 'user exists' if the user with the specified $token has been not found
	* @return string 'user exists' if the user with the specified $hex_email has been not found
  */
	public function newUserVerify($post, $conn) {
		extract($post);
		
		$user_name  = $_SESSION['login_key']['user_name'];
		$user_email = $_SESSION['login_key']['user_email'];
		$user_token = $_SESSION['login_key']['user_token'];

		if(hex2bin($hex_email) === $user_email) {
			if($token === $user_token) {
				if($this->emailExists($email, $conn)) {
					if(!$this->isVerified($user_email, $conn)) {
						if($this->generateUserVerificationId($user_email, $conn)) {
							return 'success';
						}
					}
					return 'verified';
				}
			}
		}
		return 'user exists';
	}
}

?>