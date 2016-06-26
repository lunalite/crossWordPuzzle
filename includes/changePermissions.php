<?php
    
    include_once 'db_connect.php';
    include_once 'functions.php';

    sec_session_start(); // Our custom secure way of starting a PHP session.

    $permId = json_decode($_GET['permId'], true);
    
    $id = $permId["id"];
    $perm = $permId["permissions"];

    $sql = "UPDATE ".$GLOBALS['members']." SET permissions = '" . $perm . "' WHERE id = " . $id;

        if ($mysqli->query($sql) === TRUE) {
            header ('location: ../grant.php');
            echo "Permission set successfully." . "<br>";
        }
        else {
            echo "Error: " . $sql . "<br>" . $mysqli->error . "<br><br>";
        }
    
?>
