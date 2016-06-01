<?php
    include_once 'php-connect.php';
    include_once 'phpVariables.php';
    
    $title = $_GET["title"];

    $sql = "SELECT crosswordId FROM " . $tableName . " WHERE PuzzleName = '" .$title. "'" ;

    $result = $conn->query($sql);
    $array=mysqli_fetch_all($result,MYSQLI_NUM);
    echo json_encode($array);
    
?>
