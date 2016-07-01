<?php
    
    include_once 'db_connect.php';
    include_once 'psl-config.php';
    include_once 'functions.php';

    sec_session_start(); // Our custom secure way of starting a PHP session.       

    if ($_POST['p']) {
        $userId = $_SESSION['userPW2BC'];
        if (isset($userId)) {
          $userId = $_SESSION['user_id']
        } else {
          $userId = $_SESSION['userPW2BC'];
        }

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
        $sql = "UPDATE members SET password = '" . $password . "', salt = '" . $random_salt .
         "' WHERE id = " . $userId;

        if ($mysqli->query($sql) === TRUE) {
            echo "Password changed successfully" . "<br>";
        }
        else {
            echo "Error: " . $sql . "<br>" . $mysqli->error . "<br><br>";
        }
    }

?>