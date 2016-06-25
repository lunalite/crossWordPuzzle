<?php
include_once '../../includes/db_connect.php';
include_once '../../includes/functions.php';
	
	sec_session_start();

	$user_id=$_SESSION['user_id'];
    $sess_id=$_SESSION['sess_id'];
	$sql0="SELECT sessId FROM sessionStart ORDER BY sessId DESC LIMIT 1";
	$result = $mysqli->query($sql0);	
   	$array = $result->fetch_assoc();
	$id=json_encode($array);
	$json=json_decode($id);
	$id=$json->sessId;
	$num=(int)$id;
	$sql = "DELETE FROM sessionJoin WHERE userId = ".$user_id ;
    	$result = $mysqli->query($sql);

	
	header("Location: ../everyone_view.php?id=".$num);
	
?>
