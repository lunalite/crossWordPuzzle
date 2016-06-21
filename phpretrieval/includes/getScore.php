<?php
include_once '../../includes/db_connect.php';
include_once '../../includes/functions.php';

	sec_session_start();    
    
    $user = $_SESSION['user_id'];
    $sessId = $_SESSION['sess_id'];
	//error_log($user,$sessId, 3, "error.log");

	$sql = "SELECT scores FROM sessionstart WHERE sessId = $sessId AND userId = $user" ;
	//error_log($sql, 3, "test.log");
    $result = $mysqli->query($sql);
    $array=mysqli_fetch_all($result,MYSQLI_NUM);
	//error_log($array, 3, "error.log");
    echo json_encode($array);
?>
