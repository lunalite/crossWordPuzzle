<?php
    
include_once '../../includes/db_connect.php';
include_once '../../includes/functions.php';


sec_session_start(); // Our custom secure way of starting a PHP session.

if ($_POST) {
    $commandQuery = $_POST['searchQ'];
    $listPattern = "/^[lL][iI][sS][tT]$/";
    
    if (preg_match($listPattern , $commandQuery )) {
	$commandQuery = "";
    } else {
        $commandQuery = " WHERE crosswordId = ".$commandQuery ;
    }

    $result = $mysqli->query("SELECT * FROM 
    ".$GLOBALS['crosswordMaster'].$commandQuery );

    if (mysqli_num_rows($result) == 0) {
        $returnMessage= 'Wrong';
        echo json_encode($returnMessage);
        } else {
        $data = [];
		while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
		}
        echo json_encode($data);
        }   
    }
else
    echo 'null';

?>

