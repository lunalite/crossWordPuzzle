<?php
    include_once '../../includes/db_connect.php';
    include_once '../../includes/functions.php';
    
    sec_session_start();

    $sql = "SELECT sessionEndTime FROM sessionTimeSeries WHERE sessId = '" .$_SESSION['sess_id']. "'" ;

    $result = $mysqli->query($sql);
    $data = [];
    while ($row = $result->fetch_assoc()) {
	$data[] = $row;
    }
    $endTime = (int)$data[0]["sessionEndTime"];
    echo json_encode($endTime);

?>
