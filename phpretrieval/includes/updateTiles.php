<?php
    include_once '../../includes/db_connect.php';
    include_once '../../includes/psl-config.php';
    include_once 'phpVariables.php';
    
    $tileCode = $_POST["tileCode"];
	$crosswordId = $_POST["id"];
	$answer = $_POST["answer"];
	$title=$_POST["title"];
    $desc=$_POST["description"];

    $sql = "UPDATE " .$crosswordBankName. " SET TileCode ='" .$tileCode. "' WHERE Answer = '" 
    .$answer. "' AND CrosswordID =".$crosswordId;

	$sql2 = "UPDATE " .$tableName. " SET PuzzleName='" .$title. "', crosswordDescription = '" 
    . $desc . "' WHERE crosswordId = '" .$crosswordId. "'"; 
	echo $sql;
    $result = $mysqli->query($sql);
	$result2 = $mysqli->query($sql2);
	if ($result ===TRUE && $result2 ===TRUE)
		echo "\nSucceed!";
	else
		echo "Error ".$mysqli->error;
?>
