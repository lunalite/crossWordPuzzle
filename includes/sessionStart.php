<?php
    include_once 'db_connect.php';
    include_once 'functions.php';
    require('Pusher.php');

    sec_session_start();

    //Check for all users who joined the Xword
    //Opens the gate for users to enter        
    $arr = json_decode($_POST['sJSession'], true);
    $sessId = $arr['sessId'];
    $online = $arr['online'];
    //echo $arr['sessId'];
    //echo $arr['online'];    
    
    if ($online == 1) {
        $sql = 'SELECT userId FROM sessionJoin WHERE sessId = ' . $sessId;

        $result = $mysqli->query($sql);
        if (mysqli_num_rows($result) == 0) {
            echo 'No teams joined';
        }
        else {
            while ($row=mysqli_fetch_row($result)) {
                $userId = $row[0];
                $ins = 'INSERT INTO sessionStart (sessId,userId) VALUES 
                (' . $sessId . ', '. $userId . ')';

                if ($mysqli->query($ins) === TRUE) {
                    echo "Gate is open.";
				    header('Refresh: 0; url=../XWordPuzzleStandAlone/master_view.php?id='.$sessId);
                    } 
                else {
                    echo "Error: " . $ins . "<br>" . $mysqli->error;
                }
            }
        }
    
        // Open gate for users after finishing the update of database
        $sql2 = 'UPDATE availablesessions SET online = 2 WHERE sessId = ' . $sessId;
        $mysqli->query($sql2);

        //***** Send push to all users to Xword puzzle *****

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
    elseif ($online == 2) {
        header('Refresh: 0; url=../XWordPuzzleStandAlone/master_view.php?id='.$sessId);
    }
?>