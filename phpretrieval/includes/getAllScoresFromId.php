<?php
include_once '../../includes/db_connect.php';
include_once '../../includes/functions.php';

	sec_session_start();    
    $sessId = $_GET['id'];
	error_log($sessId, 3, "error.log");
	$sql = "SELECT scores,userId FROM sessionstart WHERE sessId = $sessId" ;
	//error_log($sql, 3, "error.log");
    $result = $mysqli->query($sql);
    $array=mysqli_fetch_all($result,MYSQLI_NUM);
	//error_log($array, 3, "error.log");
    echo json_encode($array);
?>
