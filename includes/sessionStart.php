<?php
    include_once 'db_connect.php';
    include_once 'functions.php';
    require('Pusher.php');
    
    sec_session_start();

    //Check for all users who joined the Xword
    //Opens the gate for users to enter        
    $arr = json_decode($_POST['sessId'], true);
    $sessId = $arr['sessId'];
    $online = $arr['online'];

    $_SESSION['sess_id'] = $sessId;
    
    if ($online == 1) {
        $sql = "SELECT userId FROM ".$GLOBALS['sessionJoin']."WHERE sessId = ".$sessId;

        $result = $mysqli->query($sql);
        if (mysqli_num_rows($result) == 0) {
            echo 'No teams joined';
        }
        else {
            while ($row=mysqli_fetch_row($result)) {
                $userId = $row[0];
                $ins = 'INSERT INTO '.$GLOBALS['sessionStart'].' (sessId,userId) VALUES 
                (' . $sessId . ', '. $userId . ')';

                if ($mysqli->query($ins) === TRUE) {
                    header('Refresh: 3; url=../XWordPuzzleStandAlone/master_view.php?id='.$sessId);
                    echo "Gate is open. Redirecting in 3...";
                    } 
                else {
                    echo "Error: " . $ins . "<br>" . $mysqli->error;
                }
            }
        // Open gate for users after finishing the update of database
        $sql2 = 'UPDATE '.$GLOBALS['availableSessions'].' SET online = 2 WHERE sessId = ' . $sessId;
        $mysqli->query($sql2);

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
    
        $data['gateStatus'] = 'open';
        $pusher->trigger('channel_1', 'gateOpen', $data);
        //******* end live gate push *******  
        }
    }
    elseif ($online == 2) {
        header('Refresh: 0; url=../XWordPuzzleStandAlone/master_view.php?id='.$sessId);
    }
?>
