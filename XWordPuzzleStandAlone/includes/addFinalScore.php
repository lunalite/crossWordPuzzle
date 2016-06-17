<?php
    include_once '../../includes/db_connect.php';
    include_once '../../includes/functions.php';
    require('../../includes/Pusher.php');

    sec_session_start();    

    // Check if the question has been answered correctly or not
    $sessId =$_SESSION['sess_id'];
	$user =$_SESSION['user_id'];
	$score = $_GET['score'];
    $sql = 'UPDATE sessionstart SET scores = scores + '.$score.' WHERE userId = '.$user.' AND sessId = '.$sessId;
	error_log($sql,3,"check.txt");
	$mysqli->query($sql);

?>
