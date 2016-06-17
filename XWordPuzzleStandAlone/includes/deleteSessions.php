<?php
include_once '../../includes/db_connect.php';
include_once '../../includes/functions.php';
	
	$sql0="SELECT sessId FROM sessionstart ORDER BY sessId DESC LIMIT 1";
	$result = $mysqli->query($sql0);	
    $array=mysqli_fetch_all($result,MYSQLI_NUM);
	$id=json_encode($array);
	$id=substr($id,3);
	$num=(int)$id;
	echo $num;
	error_log($array[0],3,'error.txt');
	$sql = "truncate sessionjoin" ;
    $result = $mysqli->query($sql);
	
	header("Location: ../everyone_view.php?id=".$num);
	
?>
