<?php

    include_once '../../includes/db_connect.php';
    include_once '../../includes/functions.php';
    
    sec_session_start();

if ($_POST['groupNameData']) {
    $groupNameData = $_POST['groupNameData'];
    
    $checkQuery = "SELECT * FROM ".$GLOBALS['classGroup']." WHERE classGroupName = '".$groupNameData."'";
    $checkResult = $mysqli->query($checkQuery );

    if(mysqli_num_rows($checkResult) > 0) {
        echo json_encode(true);
    } else {
        echo json_encode(false);
    }
}
?>