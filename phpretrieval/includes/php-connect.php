<?php
 $host="mysql10.000webhost.com"; // Host name 
$username="a7390942_w31ha0"; // Mysql username 
$password="cultivateta0"; // Mysql password 
$db_name="a7390942_test"; // Database name 
 
 $conn = new mysqli($host, $username, $password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
?>
