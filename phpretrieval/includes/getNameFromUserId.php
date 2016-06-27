<?php
    include_once '../../includes/db_connect.php';
    include_once '../../includes/functions.php';
    
    sec_session_start();

    $userID = $_GET['id'];

    $sql = "SELECT username FROM ".$GLOBALS['members']." WHERE id = $userID";    
    $result = $mysqli->query($sql);
    $array=mysqli_fetch_all($result,MYSQLI_NUM);
    echo json_encode($array[0][0]);
    
?>
