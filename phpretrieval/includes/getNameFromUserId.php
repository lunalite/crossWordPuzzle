<?php
    include_once '../../includes/db_connect.php';
    include_once '../../includes/functions.php';
    include_once 'phpVariables.php';

    $userID = $_GET['id'];

    $sql = "SELECT username FROM members WHERE id = $userID";    
    $result = $mysqli->query($sql);
    $array=mysqli_fetch_all($result,MYSQLI_NUM);
    echo json_encode($array[0][0]);
    
?>
