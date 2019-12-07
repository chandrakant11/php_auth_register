<?php
/*
  * PHP_auth_register (https://github.com/chandrakant11/php_auth_register)
*/

  /*
	* Dynamic Generate Email Template for Forgot Password
	* @param $user_details array: $Name, $Email and $Verify_Id
	* @return HTML Email Template
  */
	function pwdRecoverMailMsg($user_details) {
		extract($user_details);

		$html = '';
		$url = domain_url().'recoverPassword.php?email='.$Email.'&id='.$Verify_Id;

		$html .= '<div style="padding:1.25rem; background-color:#f3f3f3;">';
		$html .= '<h2 style="font:2rem sans-serif; padding:1.25rem; width:50%;';
		$html .= ' margin:auto; padding:1rem; text-align:center;">PHP Auth Register</h2>';
		$html .= '<div style="padding:1.25rem; background-color:#fcfcfc; margin:auto; width:50%;">';
		$html .= '<h3 style="color:#666; font:2rem sans-serif; margin:1rem; text-align:center;">';
		$html .= 'Password Reset</h3>';
		$html .= '<h4 style="font-family:sans-serif;">User '.$Name.'</h4>';
		$html .= '<p style="font:1rem Lato; padding-bottom:1rem;">';
		$html .= 'We just received a request to send you password recovery link.';
		$html .= ' You can use the following link to reset password</p>';
		$html .= '<a href="'.$url.'" style="color:#fff; font:1.2rem Helvetica; cursor:pointer;';
		$html .= ' padding:1rem; text-decoration:none; border-radius:0.25rem;';
		$html .= ' background-color:#007bff;">Reset Password</a>';
		$html .= '<p style="font:1rem Lato; padding-top:1.2rem;">';
		$html .= 'If that doesn\'t work, copy and paste the following link in your browser</p>';
		$html .= $url;
		$html .= '<p style="font:1rem Lato; padding-top:1rem;">';
		$html .= 'If you don\'t use this link within 10 minutes, it will expire.</p>';
		$html .= '<hr style="opacity:0.5;"/>';
		$html .= '<p style="font:1rem Lato;"><b>Note: </b>If you think you did not make this request,';
		$html .= ' just ignore this email</p>';
		$html .= '<p style="font:1.2rem Lato; margin:0; padding-top:1rem; padding-bottom:0.25rem;">Thanks</p>';
		$html .= '<p style="font:1.2rem Lato; margin:0;">The PHP OOP Team</p></div></div>';

		return $html;
	}

  /*
	* Dynamic Generate Email Template for Register New User Account Verification
	* @param $user_details array: $Name, $Email, $Token and $Verify_Id
	* @return HTML Email Template
  */
	function accountVerifyEmailTemplate($user_details) {
		extract($user_details);

		$html = '';
		$url  = domain_url().'profile.php?email='.$Email.'&token='.$Token.'&verify='.$Verify_Id;

		$html .= '<div style="padding:1rem; background-color:#f4f4f4;">';
		$html .= '<div style="background-color:#ffa73b;">';
		$html .= '<h1 style="color:#666; font:2.25rem sans-serif; width:50%;';
		$html .= ' margin:auto; padding:1rem; text-align:center;';
		$html .= ' background-color:#fff;">Welcome!</h1></div>';
		$html .= '<div style="width:50%; margin:auto;">';
		$html .= '<p style="font:1rem Lato; padding:1rem 0;">';
		$html .= 'We\'re excited to have you get started. First,';
		$html .= ' you need to confirm your account. Just press the button below.</p>';
		$html .= '<a href="'.$url.'" style="color:#fff; font:1.2rem Helvetica;';
		$html .= 'background-color:#ffa73b; padding:1rem; cursor:pointer;';
		$html .= 'text-decoration:none; border-radius:0.25rem;">Confirm Account</a>';
		$html .= '<p style="font:1rem Lato; padding-top:1.2rem 0;">';
		$html .= 'If that doesn\'t work, copy and paste the following link in your browser</p>';
		$html .= $url;
		$html .= '<hr style="opacity:0.5;"/>';
		$html .= '<p style="font:1.2rem Lato;">Thanks</p>';
		$html .= '<p style="font:1.2rem Lato;">The PHP OOP Team</p></div></div>';

		return $html;
	}

?>