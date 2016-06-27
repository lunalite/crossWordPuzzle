<?php
    include_once '../../includes/db_connect.php';
    include_once '../../includes/functions.php';
    
    sec_session_start();

    $crosswordId = $_GET["crosswordId"];

    $sql = "SELECT * FROM " . $GLOBALS['crosswordPuzzles'] . " WHERE crosswordId = " . $crosswordId ;

    $result = $mysqli->query($sql);
    $data = [];
		while ($row = $result->fetch_assoc()) {
			$data[] = $row;
		}
    echo json_encode($data);
    
?>
