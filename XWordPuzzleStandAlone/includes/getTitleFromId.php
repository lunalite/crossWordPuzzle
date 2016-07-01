<?php
include_once '../../includes/db_connect.php';
include_once '../../includes/functions.php';

	sec_session_start();    

        $ID = $_GET['id'];
	$sql = "SELECT PuzzleName,crosswordDescription FROM ".$GLOBALS['crosswordMaster']." WHERE crosswordID = $ID" ;
    
    $result = $mysqli->query($sql);	
	$data = [];
	while ($row = $result->fetch_assoc()) {
		$data[] = $row;
	}
    echo json_encode($data);
?>
