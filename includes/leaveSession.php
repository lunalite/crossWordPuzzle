<?php
    
    include_once 'db_connect.php';
    include_once 'functions.php';

    sec_session_start(); // Our custom secure way of starting a PHP session.

    $userId = $_SESSION['user_id'];
    $sql = 'DELETE FROM sessionjoin WHERE userId = '.$userId;
    if ($mysqli->query($sql) === true) 
        header('Location: ../user.php');
    else 
        echo 'error';
?>
