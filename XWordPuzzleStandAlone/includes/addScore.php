<?php
include_once '../../includes/db_connect.php';
include_once '../../includes/functions.php';

sec_session_start();    

header('Content-Type: text/XML');
echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';

echo '<response>';
    $user = $_SESSION['user_id'];
    $sessId = $_SESSION['sess_id'];
    $score = $_GET['score'];
   
    $sql = 'UPDATE sessionstart SET scores = scores + ' . $score . ' WHERE userId = ' . $user .
    ' AND sessId = ' . $sessId;
    
    if (!$mysqli->query($sql)){
        echo 'error';
    }
    else    
        echo 'Scores updated successfully';
        
echo '</response>';
?>
