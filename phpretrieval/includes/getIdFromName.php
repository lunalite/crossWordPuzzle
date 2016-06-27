<?php
    include_once '../../includes/db_connect.php';
    include_once '../../includes/functions.php';
    
    sec_session_start();

    /*$title = $_GET["title"];

    $sql = "SELECT crosswordId FROM " . $GLOBALS['crosswordMaster'] . " WHERE PuzzleName = '" .$title. "'" ;

    $result = $mysqli->query($sql);
    $array=mysqli_fetch_all($result,MYSQLI_NUM);
    echo json_encode($array);*/

    //echo json_encode($_SESSION['sess_id']);
   echo json_encode(111);
    
?>
