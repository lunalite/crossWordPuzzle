<?php
    include_once '../../includes/db_connect.php';
    include_once '../../includes/functions.php';

    sec_session_start();    

    $sessId = $_SESSION['sess_id'];
    $qStack= $_POST['qStack'];
    $qStackJson = json_encode($qStack);
    $time = $_POST['time'];
    $userId = $_SESSION['user_id'];
    
    $historiseQuery = "INSERT INTO ".$GLOBALS['studentHistory']." VALUES ($sessId, $userId, $time, $qStackJson)";

    if ($mysqli->query($historiseQuery) === TRUE) {
        echo 'successful in entering data to studentHistory';
    } else {
        echo 'Error with updating time and scores to '.$GLOBALS['sessionStart'];
    }
?>
