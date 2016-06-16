<?php
    include_once '../../includes/db_connect.php';
    include_once '../../includes/functions.php';
    include_once 'phpVariables.php';
    
    sec_session_start();

    $sessID = $_SESSION['sess_id'];

    $sql = "SELECT crosswordId FROM availablesessions WHERE sessID = $sessID";    
    $result = $mysqli->query($sql);
    $array=mysqli_fetch_all($result,MYSQLI_NUM);
    echo json_encode($array[0][0]);
	//echo json_encode(0);
    
?>
