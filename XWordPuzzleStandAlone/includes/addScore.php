<?php
    include_once '../../includes/db_connect.php';
    include_once '../../includes/functions.php';
    require('../../includes/Pusher.php');

    sec_session_start();    

    // Check if the question has been answered correctly or not
    $sessId = $_SESSION['sess_id'];
    $qnsid = $_POST['qid'];
    $userId = $_SESSION['user_id'];
    $score = $_POST['score'];
    $time = $_POST['time'];
            
            $sql = 'UPDATE '.$GLOBALS['sessionStart'].' SET scores = scores + '.$score.', Time = '.$time.' WHERE userId = '.$userId.' AND sessId = '.$sessId;

            if (!$mysqli->query($sql)){
                echo 'Error with updating time and scores to '.$GLOBALS['sessionStart'];
            } else {
                $sql2 = "INSERT INTO ".$GLOBALS['questionAnswered']." (sessid, userid, qnsid, status) 
                VALUES ($sessId, $userId, $qnsid, 1)";
                if ($mysqli->query($sql2)) {

                    echo 'Scores updated successfully';
                    
                    //***** Send push to live score *****

                    $sql3 = "SELECT scores FROM ".$GLOBALS['sessionStart']." WHERE sessId = $sessId AND userId = $userId" ;
                    $result3 = $mysqli->query($sql3);
                    $updatedScore = $result3->fetch_assoc()['scores'];

                    $sql4 = "SELECT username FROM ".$GLOBALS['members']." WHERE Id = $userId";                    
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
                } else {
                    echo "Error with inserting query to ".$GLOBALS['questionAnswered'];
                }
            }
    
    //}
    //else 
    //    echo 'answered alr';

//    echo '</response>';

    


?>
