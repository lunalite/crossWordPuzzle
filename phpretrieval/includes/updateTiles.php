<?php
    include_once '../../includes/db_connect.php';
    include_once '../../includes/psl-config.php';
    include_once 'phpVariables.php';
    
    $tileCode = $_GET["tileCode"];
	$crosswordId = $_GET["id"];
	$answer = $_GET["answer"];
	$title=$_GET["title"];

    $sql = "UPDATE " .$crosswordBankName. " SET TileCode ='" .$tileCode. "' WHERE Answer = '" .$answer. "' AND CrosswordID =".$crosswordId;
	$sql2 = "UPDATE " .$tableName. " SET PuzzleName='" .$title. "' WHERE crosswordId = '" .$crosswordId. "'"; 
	echo $sql;
    $result = $mysqli->query($sql);
	$result2 = $mysqli->query($sql2);
	if ($result ===TRUE && $result2 ===TRUE)
		echo "\nSucceed!";
	else
		echo "Error ".$mysqli->error;
?>
