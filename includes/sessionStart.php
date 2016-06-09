<?php
    include_once 'db_connect.php';
    include_once 'functions.php';

    sec_session_start();

    //Check for all users who joined the Xword
    //Opens the gate for users to enter
    
    $sessId = $_POST['sessId'];
    
    $sql = 'SELECT userId FROM sessionJoin WHERE sessId = ' . $sessId;

    $result = $mysqli->query($sql);
    if (mysqli_num_rows($result) == 0) {
        echo 'No teams joined';
    }
    else {
        while ($row=mysqli_fetch_row($result)) {
            $userId = $row[0];
            $ins = 'INSERT INTO sessionStart (sessId,userId) VALUES 
            (' . $sessId . ', '. $userId . ')';

            if ($mysqli->query($ins) === TRUE) {
                echo "Gate is open.";
//                header('Location: ../waitingPage.php');
                } 
            else {
                echo "Error: " . $ins . "<br>" . $mysqli->error;
            }
        }
    }
    
    // Open gate for users after finishing the update of database
    $sql2 = 'UPDATE availablesessions SET online = 2 WHERE sessId = ' . $sessId;
    $mysqli->query($sql2);

?>