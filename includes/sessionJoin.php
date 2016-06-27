<?php
    include_once 'db_connect.php';
    include_once 'functions.php';

    sec_session_start();
    
    $arr = json_decode($_POST['sessionJoin'], true);
    $sessId = $arr['sessId'];
    $online = $arr['online'];

    $_SESSION['sess_id'] = $sessId;

    $user = $_SESSION['user_id'];
    if ($online == 2) {
        $sql = "SELECT userId FROM ".$GLOBALS['sessionStart']."WHERE sessId = ".$sessId;
        $result = $mysqli->query($sql);
        if (mysqli_num_rows($result) == 0) {
            header('refresh: 3; url=../user.php');
            echo 'Did not join before session started. Going back in 3...';
        }
    }
    elseif ($online == 1) {
        $query = 'INSERT INTO sessionJoin (userId, sessId) VALUES (' . $user . ', ' . $sessId . ')';
        if ($mysqli->query($query) === TRUE) {
            header('Location: ../user.php');
            echo "New record created successfully";
        } 
        else {
            echo "Error: " . $sql . "<br>" . $mysqli->error;
        }
    }
?>
