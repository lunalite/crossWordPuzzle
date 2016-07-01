<?php
    include_once '../../includes/db_connect.php';
    include_once '../../includes/functions.php';
    
    sec_session_start();
    
    $tileCode = $_POST["tileCode"];
	$crosswordId = $_POST["id"];
	$answer = $_POST["answer"];
	$title=$_POST["title"];
    $desc=$_POST["description"];

    $sql = "UPDATE " .$GLOBALS['crosswordPuzzles']. " SET TileCode ='" .$tileCode. "' WHERE Answer = '" 
    .$answer. "' AND CrosswordID =".$crosswordId;

	error_log($sql,3,"error.txt");

	$sql2 = "UPDATE " .$GLOBALS['crosswordMaster']. " SET PuzzleName='" .$title. "', crosswordDescription = '" 
    . $desc . "' WHERE crosswordId = '" .$crosswordId. "'"; 
    error_log($sql2,3,"error2.txt");
	echo $sql;
    $result = $mysqli->query($sql);
	$result2 = $mysqli->query($sql2);
	if ($result ===TRUE && $result2 ===TRUE)
		echo "\nSucceed!";
	else
		echo "Error ".$mysqli->error;
?>
