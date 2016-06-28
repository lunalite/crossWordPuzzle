<?php
include_once '../../includes/db_connect.php';
include_once '../../includes/functions.php';
	
	sec_session_start();

    $sess_id=$_SESSION['sess_id'];
	$sql0="DELETE FROM ".$GLOBALS['availableSessions']." WHERE sessId = ".$sess_id;
	if ($mysqli->query($sql0) === true) {
        header("Location: ../../index.php");
	}

    else
        echo 'Error in deleting availablesessions.<br>';
        echo 'Click <a href="../../index.php">here</a> to go back.';
	

	
?>
