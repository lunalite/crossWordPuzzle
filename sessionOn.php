<?php
  include_once 'includes/db_connect.php';
  include_once 'includes/functions.php';
  sec_session_start();
  if ((login_check($mysqli) == true) && (role_check($mysqli) != 0)) {
  
    $q     = $_POST['crosswordSearch'];
    $query = "SELECT crosswordDescription FROM " . $GLOBALS['crosswordMaster'] . " WHERE crosswordId = " . $q;
    $result = $mysqli->query($query);
    if (mysqli_num_rows($result) == 0) {
      echo 'No such crosswordID';
    } else {
      $arr = mysqli_fetch_row($result);
      $des = '\'' . $arr[0] . '\'';
  
      $sql = "INSERT INTO ".$GLOBALS['availableSessions']." (description, online, crosswordID) VALUES ($des, 1, $q)";
  
      if ($mysqli->query($sql) === TRUE) {
        header('Refresh: 3; url=master.php');
        echo "Session is created. Redirecting back to previous page in 3 seconds,";
      } else {
        echo "Error updating record: ";
      }
    }
  }
?>

<html>
  <head>
    <meta charset="UTF-8">
    <title>Session adding Page</title>
  </head>
  <body>
    <p>Click <a href="./master.php">here</a> to go back.</p>
  </body>
</html>

