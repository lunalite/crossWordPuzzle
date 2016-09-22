<?php
    include_once '../../includes/db_connect.php';
    include_once '../../includes/functions.php';
    
    sec_session_start();

    // set variables
    $sessId = $_POST['sessId'];
    $online = $_POST['online'];
    $_SESSION['sess_id'] = $sessId;

    if ($online === "Yes") {
      $delQuery = "DELETE FROM ".$GLOBALS['availableSessions']." WHERE sessId = ".$sessId;

      if ($mysqli->query($delQuery) === TRUE) {
        echo json_encode('session Deleted.');
      } else {
        echo json_encode('session not deleted. Error.');
      }

    } elseif ($online === "Started") {
      echo json_encode("Deleting a started session will cause instability.");
    } elseif( $online === "Ended") {
      $delQuery = "DELETE FROM ".$GLOBALS['availableSessions']." WHERE sessId = ".$sessId;

      if ($mysqli->query($delQuery) === TRUE) {
        echo json_encode('session Deleted.');
      } else {
        echo json_encode('session not deleted. Error.');
      }
    }

?>
