<?php

    include_once '../../includes/db_connect.php';
    include_once '../../includes/functions.php';
    
    sec_session_start();

    $arr = json_decode($_POST['groupId'], true);
    $gId = $arr['gId'];
    $groupName = $arr['groupName'];
    $uId = $_POST['userId'];

    $groupUpdateQuery = "UPDATE ".$GLOBALS['members']." SET classGroup = ".$gId." WHERE id = ".$uId;

        if ($mysqli->query($groupUpdateQuery) === TRUE) {            
            echo "<script>";
            echo "history.go(-1);";
            echo "</script>";
//            echo "group updated successfully." . "<br>";
        } else {
            echo "Error: " . $sql . "<br>" . $mysqli->error . "<br><br>";
        }
?>