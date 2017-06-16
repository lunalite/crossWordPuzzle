<?php
    include_once '../../includes/db_connect.php';
    include_once '../../includes/functions.php';
    require('../../includes/Pusher.php');
    
    sec_session_start();

    // set variables
    $sessId = $_POST['sessId'];
    $online = $_POST['online'];
    $_SESSION['sess_id'] = $sessId;
    $startTime = time();
    $endTime= strtotime($_POST['time']);  

    if ($online === "Yes") {
      $sql = "SELECT userId FROM ".$GLOBALS['sessionJoin']." WHERE sessId = ".$sessId;        
      $result = $mysqli->query($sql);
      if (mysqli_num_rows($result) == 0) {
          echo json_encode('No teams joined');

      } else {
        while ($row=mysqli_fetch_row($result)) {
          $userId = $row[0];
          $ins = 'INSERT INTO '.$GLOBALS['sessionStart'].' (sessId,userId) VALUES 
                    (' . $sessId . ', '. $userId . ')';
          if ($mysqli->query($ins) === TRUE) {
              echo json_encode("Gate is open. Directing you to the score page...");
              } 
          else {
              echo json_encode("Error: " . $ins . "<br>" . $mysqli->error);
          }
        }
        // Open gate for users after finishing the update of database
        $sql2 = 'UPDATE '.$GLOBALS['availableSessions'].' SET online = 2 WHERE sessId = ' . $sessId;
        $mysqli->query($sql2);


        $sqlTime = "INSERT INTO ".$GLOBALS['sessionTimeSeries']." (sessId, sessionStartTime, sessionEndTime) VALUES (".$sessId.",".$startTime.",".$endTime.")";
        $mysqli->query($sqlTime);

        $options = array(
            'cluster' => 'ap1',
            'encrypted' => true
            );

        $pusher = new Pusher(
            'bcaaf0a9f48c5ad4601b',
            '1a369dc87032c00dfb10',
            '216167',
            $options
        );
    
        $data['gateStatus'] = 'open';
        $pusher->trigger((string)$sessId, 'gateOpen', $data);
        //******* end live gate push *******  
        }

      } elseif ($online === "Started") {
          echo json_encode("Session started. Directing you to the score page without changing the end date and time...");
      }

?>
