<?php
    include_once '../../includes/db_connect.php';
    include_once '../../includes/functions.php';
    require('../../includes/Pusher.php');

    sec_session_start();    

    // Check if the question has been answered correctly or not
    $sessId = $_SESSION['sess_id'];
    $qnsid = $_GET['qid'];
    //$sql = "SELECT status from questionAnswered WHERE sessId = $sessId and qnsId = $qnsid";
    //$result = $mysqli->query($sql);
    

    header('Content-Type: text/XML');
    echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
    echo '<response>';
    //$status = $result->fetch_assoc()['status'] ;
    
    //if ($status != 1 ) {
        // If no other users answered the question correctly, then give marks to the user who got it right    
            $user = $_SESSION['user_id'];
            $sessId = $_SESSION['sess_id'];
            $score = $_GET['score'];
            
            $sql = 'UPDATE '.$GLOBALS['sessionStart'].' SET scores = scores + '.$score.' WHERE userId = '.$user.' AND sessId = '.$sessId;

            if (!$mysqli->query($sql)){
                echo 'error1';
            }
            else {

                $sql2 = "INSERT INTO ".$GLOBALS['questionAnswered']." (sessid, userid, qnsid, status) 
                VALUES ($sessId, $user, $qnsid, 1)";
                if ($mysqli->query($sql2)) {

                    echo 'Scores updated successfully';
                    
                    //***** Send push to live score *****

                    $sql3 = "SELECT scores FROM ".$GLOBALS['sessionStart']." WHERE sessId = $sessId AND userId = $user" ;
                    $result3 = $mysqli->query($sql3);
                    $updatedScore = $result3->fetch_assoc()['scores'];

                    $sql4 = "SELECT username FROM ".$GLOBALS['members']." WHERE Id = $user";                    
                    $result4 = $mysqli->query($sql4);
                    $username = $result4->fetch_assoc()['username'];

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

                    $data['updatedScore'] = $updatedScore;
                    $data['userName'] = $username;
                    $pusher->trigger('channel_1', 'correctAnswer', $data);
                    //******* end live score push *******
                }
                else 
                    echo 'error2';
            }
    
    //}
    //else 
    //    echo 'answered alr';

    echo '</response>';

    


?>
