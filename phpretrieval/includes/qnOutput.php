<?php
    include_once '../../includes/db_connect.php';
    include_once '../../includes/psl-config.php';
    include_once 'phpVariables.php';
    
    $crosswordId = $_GET["crosswordId"];

    $sql = "SELECT * FROM " . $crosswordBankName . " WHERE crosswordId = " . $crosswordId ;

    $result = $mysqli->query($sql);
    $array=mysqli_fetch_all($result,MYSQLI_NUM);
    echo json_encode($array);
    
?>
