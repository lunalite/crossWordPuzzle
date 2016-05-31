<?php
    $servername = "localhost";
    $username = "root";
    $password = "eKcGZr59zAa2BEWU";
    $dbname = "xword";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
        //echo "Connected successfully." . "<br>";
    
?>