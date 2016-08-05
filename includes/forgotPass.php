<?php
    
    include_once './db_connect.php';
    include_once './functions.php';
    sec_session_start();

if (isset($_POST["ForgotPassword"])) {
	
	// Harvest submitted e-mail address
	if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
		$email = $_POST["email"];
		
	}else{
		echo "email is not valid";
		exit;
	}

	// Check to see if a user exists with this e-mail
        $queryMessage = "SELECT * FROM ".$GLOBALS['members']." WHERE email = '$email'";
        $result = $mysqli->query($queryMessage);
        $userExists = mysqli_fetch_assoc($result);

	if ($userExists["email"])
	{
		// Create a unique salt. This will never leave PHP unencrypted.
		$salt = $userExists["salt"];

		// Create the unique user password reset key
		$password = hash('sha512', $salt.$userExists["email"]);

		// Create a url which we will direct them to reset their password
		$pwrurl = "repxword.com/resetPassword.php?q=".$password;
		
		// Mail them their key
		$mailbody = "Dear user,\n\nIf this e-mail does not apply to you please ignore it. It appears that you have requested a password reset at our website www.repxword.com\n\nTo reset your password, please click the link below. If you cannot click it, please paste it into your web browser's address bar.\n\n" . $pwrurl . "\n\nThanks,\nThe Administration";
		mail($userExists["email"], "REP Crossword - Password Reset", $mailbody);
		echo "Your password recovery key has been sent to your e-mail address.";
		
	}
	else
		echo "No user with that e-mail address exists.";
}
?>