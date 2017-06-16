<?php
    include_once '../../includes/db_connect.php';
    include_once '../../includes/functions.php';
    require('../../includes/Pusher.php');

    sec_session_start();

    // set variables
    $sessId = $_POST['sessId'];
    $newEndTime = time();

    $sqlCheckIfOnline = "SELECT online FROM ".$GLOBALS['availableSessions']." WHERE sessId = ".$sessId;
    $checkOnlineresult = $mysqli->query($sqlCheckIfOnline);

    if(mysqli_fetch_row($checkOnlineresult)[0] === "2") {

        $sql2 = "UPDATE ".$GLOBALS['availableSessions']." SET online = 3 WHERE sessId = " . $sessId;
        $mysqli->query($sql2);

        $sqlTime = "UPDATE ".$GLOBALS['sessionTimeSeries']." SET sessionEndTime = '".$newEndTime."' WHERE sessId = ".$sessId;
        $mysqli->query($sqlTime);

        $options = array(
            'cluster' => 'ap1',
            'encrypted' => true
            );

        $pusher = new Pusher(
            'bcaaf0a9f48c5ad4601b',
            '1a369dc87032c00dfb10',
            '216167',
            $options
        );
    
        $data['gameStatus'] = "ended";
        $pusher->trigger((string)$sessId, 'gameEnd', $data);
        //******* end live gate push *******  
        
        echo json_encode("Game has ended.");

    } else {
        echo json_encode("Error.");
    }

?>
