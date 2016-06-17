<?php
    
include_once 'db_connect.php';
include_once 'functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.


if ($_POST) {
    $q = $_POST['searchQ'];

    if ($q == 'list') {
        $result = $mysqli->query("SELECT crosswordId, crosswordDescription, PuzzleName FROM 
        crosswordmasterdb");
    }
    else {
        $result = $mysqli->query("SELECT crosswordId, crosswordDescription, PuzzleName FROM 
        crosswordmasterdb WHERE crosswordId = $q");
        }

    if (mysqli_num_rows($result) == 0) {
            $test = 'No such id OR wrong command';
            echo json_encode($test);
        }
    else {
        $array=mysqli_fetch_all($result,MYSQLI_NUM);
        echo json_encode($array);
    }
    
}
else
    echo 'null';

?>