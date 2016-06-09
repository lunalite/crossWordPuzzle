<?php
    include_once '../../includes/db_connect.php';
    include_once '../../includes/psl-config.php';
    include_once '../../includes/functions.php';
    include_once 'phpVariables.php';
    
    sec_session_start();

    /*$title = $_GET["title"];

    $sql = "SELECT crosswordId FROM " . $tableName . " WHERE PuzzleName = '" .$title. "'" ;

    $result = $mysqli->query($sql);
    $array=mysqli_fetch_all($result,MYSQLI_NUM);
    echo json_encode($array);*/

    echo json_encode($_SESSION['sess_id']);
    
?>
