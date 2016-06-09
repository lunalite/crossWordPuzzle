<?php
include_once '../../includes/db_connect.php';
include_once '../../includes/functions.php';

	$sql = "truncate sessionjoin" ;
    $result = $mysqli->query($sql);
	$sql = "truncate availablesessions" ;
	$result = $mysqli->query($sql);
	
	header("Location: ../gameover.html");
	
?>
