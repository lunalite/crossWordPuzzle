<?php
    include_once 'db_connect.php';
    include_once 'functions.php';

    sec_session_start();
    
    // check for user id, then add him to db
    $sessId = $_POST['sessId'];
    $_SESSION['sess_id'] = $sessId;
    $user = $_SESSION['user_id'];

    $query = 'INSERT INTO sessionJoin (userId, sessId) VALUES (' . $user . ', ' . $sessId . ')';

    if ($mysqli->query($query) === TRUE) {
        echo "New record created successfully";
        header('Location: ../waitingPage.php');
    } 
    else {
        echo "Error: " . $sql . "<br>" . $mysqli->error;
    }

?>
