<?php
    
    include_once '../../includes/db_connect.php';
    include_once '../../includes/functions.php';

    sec_session_start(); // Our custom secure way of starting a PHP session.

    $crosswordId = $_POST['crosswordId'];

    $sql = "DELETE FROM ".$GLOBALS['crosswordPuzzles']." WHERE CrosswordID = ".$crosswordId;

        if ($mysqli->query($sql) === TRUE) {
            $sql2 = "DELETE FROM ".$GLOBALS['crosswordMaster']." WHERE CrosswordID = ".$crosswordId;

            if ($mysqli->query($sql2) === TRUE) {
                header('Location: ../crosswordView.php');
                echo "Crossword master db ID and questions deleted successfully. <br>";
            }
            else {echo "Error: " . $sql . "<br>" . $mysqli->error . "<br><br>";}
        }
        else {
            echo "Error: " . $sql . "<br>" . $mysqli->error . "<br><br>";
        }
?>

