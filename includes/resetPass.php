<?php

    include_once './db_connect.php';
    include_once './functions.php';
    sec_session_start();
    
// Was the form submitted?
//if (isset($_POST["ResetPasswordForm"])) {

	// Gather the post data
	$email = $_POST["email"];
	$hash = $_POST["q"];

	// Use the same salt from the forgot_password.php file
	$salt = "498#2D83B631%3800EBD!801600D*7E3CC13";

	// Generate the reset key
	$resetkey = hash('sha512', $salt.$email);

	// Does the new reset key match the old one?
	if ($resetkey == $hash) {

        $password = filter_input(INPUT_POST, 'p', FILTER_SANITIZE_STRING);
        if (strlen($password) != 128) {
            // The hashed pwd should be 128 characters long.
            // If it's not, something really odd has happened
            $error_msg .= '<p class="error">Invalid password configuration.</p>';
        }

        // Create a random salt
        $random_salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE));
        // Create salted password 
        $password = hash('sha512', $password . $random_salt);
        
        // Update the password for user
        $sql = "UPDATE ".$GLOBALS['members']." SET password = '" . $password . "', salt = '" . $random_salt .
         "' WHERE email = '" . $email."'";
echo $sql;

if ($mysqli->query($sql) === TRUE) {
            echo "Password changed successfully. Going back in 3 seconds..." . "<br>";
            echo "<script>setTimeout(function(){location.href='../index.php'} , 3000);</script>";
        }
        else {
            echo "Error: " . $sql . "<br>" . $mysqli->error . "<br><br>";
        }

			// Update the user's password
//$queryMessage = "UPDATE ".$GLOBALS['members']." SET password = :password WHERE email = :email";
//				$query = $mysqli->prepare();
//				$query->bindParam(':password', $password);
//				$query->bindParam(':email', $email);
//				$query->execute();
//			echo "Your password has been successfully reset.";
		
	}
	else
		echo "Your password reset key is invalid.";
//}

?>
