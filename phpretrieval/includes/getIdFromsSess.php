<?php
    include_once '../../includes/db_connect.php';
    include_once '../../includes/functions.php';
    include_once 'phpVariables.php';
    
    sec_session_start();

    $sessID = $_SESSION['sess_id'];

    $sql = "SELECT crosswordId FROM availablesessions WHERE sessID = $sessID";    
    $result = $mysqli->query($sql);
    $data = [];
		while ($row = $result->fetch_assoc()) {
			$data[] = $row;
		}
    echo json_encode($data);
    
?>
