<?php
    
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

    $q = $_POST['crosswordSearch'];
	echo $q;
    $result = $mysqli->query("SELECT crosswordDescription FROM crosswordmasterdb WHERE crosswordId = $q ");
	$result2 = $mysqli->query("SELECT PuzzleName FROM crosswordmasterdb WHERE crosswordId = $q ");
        if (mysqli_num_rows($result) == 0) {
            $test = 'Error..';
            echo $test;
        }
        else {
            $arr = mysqli_fetch_row($result);
            $des = '\''.$arr[0].'\'';
			$arr2 = mysqli_fetch_row($result2);
            $title = '\''.$arr2[0].'\'';
            
            $sql = "INSERT INTO availablesessions VALUES ($q, $des, 1,$title)";
            
            if ($mysqli->query($sql) === TRUE) {
                echo "Record updated successfully";
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
        <p>You can now go back to the <a href="master.php">master page</a></p>
    </body>
</html>

