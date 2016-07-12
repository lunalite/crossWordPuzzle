<?php

    include_once '../../includes/db_connect.php';
    include_once '../../includes/functions.php';
    
    sec_session_start();

    $groupName = $_POST['groupName'];
    $checkQuery = "SELECT * FROM ".$GLOBALS['classGroup']." WHERE classGroupName = '".$groupName."'";
    $checkResult = $mysqli->query($checkQuery );

    if(mysqli_num_rows($checkResult) > 0) {
        header("Refresh:3; url=../groups.php");
        echo 'Repeated group name. Please use another. Going back in 3...';
    } else {
        $sql = "INSERT INTO ".$GLOBALS['classGroup']. " (classGroupName) VALUES ('" . $groupName. "')";
        if ($mysqli->query($sql) === TRUE) {
            header("Refresh:3; url=../groups.php");
            echo "New group created successfully. Going back in 3..." . "<br>";
        } else {
            echo "Error: " . $sql . "<br>" . $mysqli->error;
    }
    
}
?>