<?php
include_once '../../includes/db_connect.php';
include_once '../../includes/functions.php';

	sec_session_start();    

    $sessId = $_SESSION['sess_id'];
	$sql = "SELECT PuzzleName FROM crosswordmasterdb WHERE crosswordID =
    (SELECT crosswordID from availablesessions WHERE sessId = $sessId)" ;
    
    $result = $mysqli->query($sql);	
	$data = [];
	while ($row = $result->fetch_assoc()) {
		$data[] = $row;
	}
    echo json_encode($data);
?>
