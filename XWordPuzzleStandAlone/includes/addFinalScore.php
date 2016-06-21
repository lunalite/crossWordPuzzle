<?php
    include_once '../../includes/db_connect.php';
    include_once '../../includes/functions.php';

    sec_session_start();    

    // Check if the question has been answered correctly or not
    $sessId =$_SESSION['sess_id'];
    $user =$_SESSION['user_id'];

    $time=$_POST['time'];

    $sql = 'UPDATE sessionStart SET Time = '.$time.' WHERE userId = '.$user.' AND sessId = '.$sessId;
    
    if ($mysqli->query($sql) === true) {
        echo json_encode('Time set true');
    }
    else
        echo json_encode('Error');
     
?>
