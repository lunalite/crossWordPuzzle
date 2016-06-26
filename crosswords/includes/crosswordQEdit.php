<?php
    
    include_once '../../includes/db_connect.php';
    include_once '../../includes/functions.php';

    sec_session_start(); // Our custom secure way of starting a PHP session.

    $qid = $_POST['qid'];
    $question = $_POST['question'];
    $answer = $_POST['answer'];
    $crosswordId = $_POST['crosswordId'];

    $sql = "UPDATE ".$GLOBALS['crosswordPuzzles']." SET Question = '".$question."', Answer = '".$answer."'
            WHERE CrosswordID = ".$crosswordId." AND QnsID = ".$qid;

        if ($mysqli->query($sql) === TRUE) {
            header ('location: ../crosswordView.php');
            echo "Crossword questions updated successfully." . "<br>";
        }
        else {
            echo "Error: " . $sql . "<br>" . $mysqli->error . "<br><br>";
        }
?>

