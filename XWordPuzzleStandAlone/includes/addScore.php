<?php
    include_once '../../includes/db_connect.php';
    include_once '../../includes/functions.php';

    sec_session_start();    

    // Check if the question has been answered correctly or not
    $sessId = $_SESSION['sess_id'];
    $qnsid = $_GET['qid'];
    $sql = "SELECT status from questionAnswered WHERE sessId = $sessId and qnsId = $qnsid";
    $result = $mysqli->query($sql);

    header('Content-Type: text/XML');
    echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
    echo '<response>';

    if (mysqli_num_rows($result) == 0) {
        // If no other users answered the question correctly, then give marks to the user who got it right    
            $user = $_SESSION['user_id'];
            $sessId = $_SESSION['sess_id'];
            $score = $_GET['score'];
            
            $sql = 'UPDATE sessionstart SET scores = scores + ' . $score . ' WHERE userId = ' . $user .
            ' AND sessId = ' . $sessId;
            
            if (!$mysqli->query($sql)){
                echo 'error';
            }
            else {
                $sql2 = "INSERT INTO questionanswered (sessid, userid, qnsid, status) 
                VALUES ($sessId, $user, $qnsid, 1)";
                if ($mysqli->query($sql2))
                    echo 'Scores updated successfully';
                else 
                    echo 'error';
            }
    }
    else 
        echo 'answered alr';

    echo '</response>';

?>
