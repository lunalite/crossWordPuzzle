<?php
    
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

    $q = $_POST['crosswordSearch'];
    $result = $mysqli->query("SELECT crosswordDescription FROM crosswordmasterdb WHERE crosswordId = $q ");
	// $result2 = $mysqli->query("SELECT PuzzleName FROM crosswordmasterdb WHERE crosswordId = $q ");
        if (mysqli_num_rows($result) == 0) {
            echo 'No such crosswordID';
        }
        else {
            $arr = mysqli_fetch_row($result);
            $des = '\''.$arr[0].'\'';
			//$arr2 = mysqli_fetch_row($result2);
            //$title = '\''.$arr2[0].'\'';
            
            $sql = "INSERT INTO availablesessions (description, online, crosswordID) VALUES ($des, 1, $q)";

            if ($mysqli->query($sql) === TRUE) {
                header('Refresh: 3; url=master.php');
                echo "Session is created. Redirecting back to previous page in 3 seconds,";
            } else {
                echo "Error updating record: " . $mysqli->error;
            }
        }
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Session adding Success</title>
        <link rel="stylesheet" href="styles/main.css" />
    </head>
    <body>
        <p>Click <a href="master.php">here</a> to go back.</p>
    </body>
</html>

