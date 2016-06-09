<?php
include_once '../../includes/db_connect.php';
include_once '../../includes/functions.php';
	sec_session_start();    
    $sessId = $_SESSION['sess_id'];
	$sql = "SELECT PuzzleName FROM availablesessions WHERE sessId = $sessId " ;

    $result = $mysqli->query($sql);
    $array=mysqli_fetch_all($result,MYSQLI_NUM);
    echo json_encode($array);
        
echo '</response>';
?>
