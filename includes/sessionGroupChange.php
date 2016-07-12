<?php
    include_once 'db_connect.php';
    include_once 'functions.php';

    sec_session_start();
    echo 'hi';
    $arr = json_decode($_POST['classGroup'], true);
    $gId = $arr['gId'];
    $groupName = $arr['groupName'];
    $sessId = $_POST['sessId'];
echo $sessId;
        $query = "UPDATE ".$GLOBALS['availableSessions']." SET classGroupOpen = ".$gId." WHERE sessId = ".$sessId;
echo $query;
        if ($mysqli->query($query) === TRUE) {
            echo "<script>";
            echo "history.go(-1);";
            echo "</script>";
        } 
        else {
            echo "Error: " . $sql . "<br>" . $mysqli->error;
        }
    }*/
?>
