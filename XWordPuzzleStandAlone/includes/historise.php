<?php
    include_once '../../includes/db_connect.php';
    include_once '../../includes/functions.php';

    sec_session_start();    

    $sessId = $_SESSION['sess_id'];
    $qStack= $_POST['qStack'];
    $time = $_POST['time'];
    $userId = $_SESSION['user_id'];
echo $qStack;
    $historiseQuery = "INSERT INTO ".$GLOBALS['studentHistory']." VALUES ($sessId, $userId, $time, $qStack)";
echo $historiseQuery;
/*
            if ($mysqli->query($sql)){
                echo 'Error with updating time and scores to '.$GLOBALS['sessionStart'];
            } else {
                $sql2 = "INSERT INTO ".$GLOBALS['questionAnswered']." (sessid, userid, qnsid, status) 
                VALUES ($sessId, $user, $qnsid, 1)";
                if ($mysqli->query($sql2)) {

        */            


?>
