<?php
    include_once '../../includes/db_connect.php';
    include_once '../../includes/psl-config.php';
    include_once 'phpVariables.php';
    
    $crosswordId = $_GET["crosswordId"];

    $sql = "SELECT * FROM " . $crosswordBankName . " WHERE crosswordId = " . $crosswordId ;

    $result = $mysqli->query($sql);
    $data = [];
		while ($row = $result->fetch_assoc()) {
			$data[] = $row;
		}
    echo json_encode($data);
    
?>
