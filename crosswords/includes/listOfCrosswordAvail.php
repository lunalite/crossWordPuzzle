<?php
  
  include_once '../../includes/db_connect.php';
  include_once '../../includes/functions.php';
  
  sec_session_start();
  
  if ($_POST) {
    $commandQuery = $_POST['searchQ'];
    $listPattern = "/^[lL][iI][sS][tT]$/";
  
    if (preg_match($listPattern , $commandQuery )) {
      $commandQuery = "";
    } else {
      $commandQuery = " WHERE crosswordId = ".$commandQuery ;
    }
  
    $result = $mysqli->query("SELECT * FROM 
    ".$GLOBALS['crosswordMaster'].$commandQuery );
  
    if (mysqli_num_rows($result) == 0) {
      $returnMessage= 'Wrong';
      echo json_encode($returnMessage);
    } else {
      $data = [];
      while ($row = $result->fetch_assoc()) {
        $data[] = $row;
      }

    $queryForCrosswordValidity = "SELECT DISTINCT crosswordID FROM ".$GLOBALS['crosswordPuzzles'].
                                    " WHERE TileCode = 'Not Assigned yet'";
    $resultForCrosswordValidity = $mysqli->query($queryForCrosswordValidity);
    while ($rowForCrosswordValidity = $resultForCrosswordValidity->fetch_assoc()) {
      foreach($data as $key => $value) {
        if($data[$key]['crosswordId'] == $rowForCrosswordValidity['crosswordID']) {
          $data[$key]['notAssignedYet'] = TRUE;
        }
      }    
    }
    echo json_encode($data);
    }   

  } else {
      echo 'null';
  }
  
?>

