<?php
    include_once 'php-connect.php';
    include_once 'phpVariables.php';
    
    $crosswordId = $_GET["crosswordId"];

    $sql = "SELECT * FROM " . $crosswordBankName . " WHERE crosswordId = " . $crosswordId ;

    $result = $conn->query($sql);
    $array=mysqli_fetch_all($result,MYSQLI_NUM);
    echo json_encode($array);
    
?>
