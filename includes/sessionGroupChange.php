<?php
    include_once 'db_connect.php';
    include_once 'functions.php';

    sec_session_start();

    $arr = json_decode($_POST['classGroup'], true);
    $gId = $arr['gId'];
    $groupName = $arr['groupName'];
    $sessId = $_POST['sessId'];

        $query = "UPDATE ".$GLOBALS['availableSessions']." SET classGroupOpen = ".$gId." WHERE sessId = ".$sessId;

        if ($mysqli->query($query) === TRUE) {
            echo "<script>";
            echo "window.location.href='../master.php'";
            echo "</script>";
        } else {
            echo "Error: " . $sql . "<br>" . $mysqli->error;
        }
?>
