<?php
    include_once 'db_connect.php';
    include_once 'functions.php';

    sec_session_start();
    
    $arr = json_decode($_POST['sessionJoin'], true);
    $sessId = $arr['sessId'];
    
    $_SESSION['sess_id'] = $sessId;
    $user = $_SESSION['user_id'];

    $query = 'INSERT INTO sessionJoin (userId, sessId) VALUES (' . $user . ', ' . $sessId . ')';
    if ($mysqli->query($query) === TRUE) {
        header('Location: ../user.php');
        echo "New record created successfully";
    } 
    else {
        echo "Error: " . $sql . "<br>" . $mysqli->error;
    }
    
?>
