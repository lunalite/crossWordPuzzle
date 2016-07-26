<?php
  
  require_once 'psl-config.php';
  require_once 'phpVariables.php';
  
  function sec_session_start() {
      $session_name = 'sec_session_id';   // up a custom session name 
      $secure = SECURE;
  
      // This stops JavaScript being able to access the session id.
      $httponly = true;
  
      // Forces sessions to only use cookies.
      if (ini_set('session.use_only_cookies', 1) === FALSE) {
          header("Location: ../error.php?err=Could not initiate a safe session (ini_set)");
          exit();
      }
  
      // Gets current cookies params.
      $cookieParams = session_get_cookie_params();
      session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly);
  
      // Sets the session name to the one set above.
      session_name($session_name);
  
      session_start();            // Start the PHP session 
      session_regenerate_id();    // regenerated the session, delete the old one. 
  }
  
  function login($email, $password, $mysqli) {
      // Using prepared statements means that SQL injection is not possible. 
      if ($stmt = $mysqli->prepare("SELECT id, username, password, salt, permissions 
                    FROM ".$GLOBALS['members']."
                                    WHERE email = ? LIMIT 1")) {
          $stmt->bind_param('s', $email);  // Bind "$email" to parameter.
          $stmt->execute();    // Execute the prepared query.
          $stmt->store_result();
  
          // get variables from result.
          $stmt->bind_result($user_id, $username, $db_password, $salt, $permissions);
          $stmt->fetch();
  
          // hash the password with the unique salt.
          $password = hash('sha512', $password . $salt);
          if ($stmt->num_rows == 1) {
              // If the user exists we check if the account is locked
              // from too many login attempts 
              if (checkbrute($user_id, $mysqli) == true) {
                  // Account is locked 
                  // Send an email to user saying their account is locked 
                  return false;
              } else {
                  // Check if the password in the database matches 
                  // the password the user submitted.
                  if ($db_password == $password) {
                      // Password is correct!
                      // Get the user-agent string of the user.
                      $user_browser = $_SERVER['HTTP_USER_AGENT'];
  
                      // XSS protection as we might print this value
                      $user_id = preg_replace("/[^0-9]+/", "", $user_id);
                      $_SESSION['user_id'] = $user_id;
  
                      // XSS protection as we might print this value
                      $username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username);
  
                      $_SESSION['username'] = $username;
                      $_SESSION['login_string'] = hash('sha512', $password . $user_browser);
                      $_SESSION['permissions'] = $permissions;
  
                      // Login successful. 
                      return true;
                  } else {
                      // Password is not correct 
                      // We record this attempt in the database 
                      $now = time();
                      if (!$mysqli->query("INSERT INTO ".$GLOBALS['loginAttempts']."(user_id, time) 
                                      VALUES ('$user_id', '$now')")) {
                          header("Location: ../error.php?err=Database error: login_attempts");
                          exit();
                      }
  
                      return false;
                  }
              }
          } else {
              // No user exists. 
              return false;
          }
      } else {
          // Could not create a prepared statement
          header("Location: ../error.php?err=Database error: cannot prepare statement");
          exit();
      }
  }
  
  function checkbrute($user_id, $mysqli) {
      // Get timestamp of current time 
      $now = time();
  
      // All login attempts are counted from the past 2 hours. 
      $valid_attempts = $now - (2 * 60 * 60);
  
      if ($stmt = $mysqli->prepare("SELECT time 
                                    FROM ".$GLOBALS['loginAttempts']." 
                                    WHERE user_id = ? AND time > '$valid_attempts'")) {
          $stmt->bind_param('i', $user_id);
  
          // Execute the prepared query. 
          $stmt->execute();
          $stmt->store_result();
  
          // If there have been more than 5 failed logins 
          if ($stmt->num_rows > 5) {
              return true;
          } else {
              return false;
          }
      } else {
          // Could not create a prepared statement
          header("Location: ../error.php?err=Database error: cannot prepare statement");
          exit();
      }
  }
  
  function login_check($mysqli) {
      // Check if all session variables are set 
      if (isset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['login_string'])) {
          $user_id = $_SESSION['user_id'];
          $login_string = $_SESSION['login_string'];
          $username = $_SESSION['username'];
  
          // Get the user-agent string of the user.
          $user_browser = $_SERVER['HTTP_USER_AGENT'];
  
          if ($stmt = $mysqli->prepare("SELECT password 
                        FROM ".$GLOBALS['members']." 
                        WHERE id = ? LIMIT 1")) {
              // Bind "$user_id" to parameter. 
              $stmt->bind_param('i', $user_id);
              $stmt->execute();   // Execute the prepared query.
              $stmt->store_result();
  
              if ($stmt->num_rows == 1) {
                  // If the user exists get variables from result.
                  $stmt->bind_result($password);
                  $stmt->fetch();
                  $login_check = hash('sha512', $password . $user_browser);
  
                  if ($login_check == $login_string) {
                      // Logged In!!!! 
                      return true;
                  } else {
                      // Not logged in 
                      return false;
                  }
              } else {
                  // Not logged in 
                  return false;
              }
          } else {
              // Could not prepare statement
              header("Location: ../error.php?err=Database error: cannot prepare statement");
              exit();
          }
      } else {
          // Not logged in 
          return false;
      }
  }
  
  function role_check($mysqli) {
      //Set variable permissions for the different roles
  if (!$mysqli) {
      $permissions= $_SESSION['permissions'];
  } else {
      $query = "SELECT permissions FROM ".$GLOBALS['members']." WHERE id = ".$_SESSION['user_id'];
      $result = $mysqli->query($query);
      while ($row=mysqli_fetch_row($result)) {
          $permissions = $row[0];
      }
  }
      //$permissions = 0 for normal users
      //$permissions = 1 for super users
      //$permissions = 2 for admin
      return $permissions;
  
  }
  
  function debugPermissionCheck($mysqli) {
      $query = "SELECT debugPermissions FROM ".$GLOBALS['members']." WHERE id = ".$_SESSION['user_id'];
      $result = $mysqli->query($query);
      while ($row=mysqli_fetch_row($result)) {
         return $row[0];
      }
  }
  
  // For checking of available sessions into table
  function availSessionCheck($mysqli) {  
    $groupTb = "";

    if ($_SESSION['permissions'] == 0) {
      $groupCheckQuery = "SELECT classGroup FROM ".$GLOBALS['members']." WHERE id = ".$_SESSION['user_id'];
      $groupCheckResult = $mysqli->query($groupCheckQuery);
      $groupCheckRow = mysqli_fetch_row($groupCheckResult); 
      $catQ = "classGroupOpen = ".$groupCheckRow[0];    

    } else {
        if (isset($_GET['sessId'])) {
            $catQ = "sessId = ". $_GET[sessId];
        } else {
            $catQ = "online != 0";
        }
    }
  
      $query = "SELECT * FROM ".$GLOBALS['availableSessions']." WHERE ".$catQ;
  
      $result = $mysqli->query($query);
  
      if (mysqli_num_rows($result) == 0) {
          echo '<tr><td colspan="6" sessId="-1">No sessions online.</td></tr>';
      }
      else {
          while ($row=mysqli_fetch_row($result)) {
              if ($row[2] == 1) 
                  $online = 'Yes';
              elseif ($row[2] == 2) 
                  $online = 'Started';
              $tableFormat = '<tr id= "'.$row[0].'">';
              $tbp = '<td>'.$row[0].'</td>
                      <td>'.$row[3].'</td>
                      <td></td><td></td>
                      <td>'.$online.'</td>';
  
              // For displaying classGroup the session is opened to
              if ($_SESSION['permissions'] == 1 || $_SESSION['permissions'] == 2) {
                  $innerGroupQuery = "SELECT * FROM ".$GLOBALS['classGroup']." WHERE id = ".$row[4];
                  $innerGroupResult= $mysqli->query($innerGroupQuery );
                  if (mysqli_num_rows($innerGroupResult) == 0) {
                      $groupTb = '<td>Not assigned</td>';
                  } else {
                      while ($innerGroupRow= mysqli_fetch_row($innerGroupResult)) {
                        $groupTb = '<td>'.$innerGroupRow[1].'</td>';
                      }
                  }
              }
  
              $innerQuery = "SELECT username FROM ".$GLOBALS['members']." WHERE id in 
              (SELECT userId FROM ".$GLOBALS['sessionJoin']." WHERE sessId = " . $row[0] . ")";
  
              $innerResult = $mysqli->query($innerQuery);
              if (mysqli_num_rows($innerResult) == 0) {
  
                  $teamTb = '<td> No teams </td></tr>';
              }
              else {
                  $teamTb = '<td>';
                  $notEnd = false;
                  while ($innerRow=mysqli_fetch_row($innerResult)) {
  
                       // For beautifying the data shown
                       if ($notEnd) {
                           $teamTb = $teamTb . ', ';
                       }
                       $teamTb = $teamTb . $innerRow[0];
                       $notEnd = true;
                  }
                  $teamTb = $teamTb.'</td></tr>';
  
              }
  
              echo $tableFormat.$groupTb.$tbp.$teamTb;
          }
      }
  }
  
  
  // For available sessions into select box
  function sessionCheckD($mysqli) {
  
      if ($_SESSION['permissions'] == 0) {
          $groupCheckQuery = "SELECT classGroup FROM ".$GLOBALS['members']." WHERE id = ".$_SESSION['user_id'];
          $groupCheckResult = $mysqli->query($groupCheckQuery);
          $groupCheckRow = mysqli_fetch_row($groupCheckResult); 
          $catQ = " AND classGroupOpen = ".$groupCheckRow[0];
      } else {
          $catQ = "";
      }
  
      if (isset($_GET['sessId'])) {
          $query = "SELECT * FROM ".$GLOBALS['availableSessions']." WHERE sessId = ". $_GET[sessId].$catQ;
      } else {
          $query = "SELECT * FROM ".$GLOBALS['availableSessions']." WHERE online != 0".$catQ;
      }
  
      $result = $mysqli->query($query);
      if (mysqli_num_rows($result) == 0) {
          echo '<option value=\"NoSessOn\">No sessions online</option>';
      }
      else {
          while ($row=mysqli_fetch_row($result)) {
              if ($row[2] == 1)
                  $online = 'online';
              elseif ($row[2] == 2)
                  $online = 'started';
              $tbp = '<option value=\'{"sessId" : "'.$row[0].'", 
                      "online" : "'.$row[2].'"}\'>sessionID '. $row[0].' ('. $online .')</option>';           
              echo $tbp;
          }
      }
  }
  
  // For available groups into select box
  function groupCheckD($mysqli) {
  
      $query = "SELECT * FROM ".$GLOBALS['classGroup'];
  
      $result = $mysqli->query($query);
      if (mysqli_num_rows($result) == 0) {
          echo '<option value=\"NoGroup\">No groups created</option>';
      }
      else {
          while ($row=mysqli_fetch_row($result)) {
              $tbp = '<option value=\'{"gId" : "'.$row[0].'", 
                      "groupName" : "'.$row[1].'"}\'>Group '. $row[1].'</option>';           
              echo $tbp;
          }
      }
  }
  
  function userInSession($mysqli){
      $query = "SELECT * FROM ".$GLOBALS['sessionJoin']." WHERE userId = ".$_SESSION['user_id'];
      $result = $mysqli->query($query);
      if (mysqli_num_rows($result) == 0) 
          return FALSE;
      else {
          $row=mysqli_fetch_row($result);
          $_SESSION['sess_id'] = $row[1]; 
          return TRUE;
          }
  }
  
  function gateCheck($mysqli) {
      $query = "SELECT * FROM ".$GLOBALS['availableSessions']." WHERE sessid = " . $_SESSION['sess_id'];
      $result = $mysqli->query($query);
      if (mysqli_num_rows($result) == 0) {
          echo 'Error';
          return FALSE;
      }
      else {
          while ($row=mysqli_fetch_row($result)) {
              if ($row[2] == 2) {
                  return TRUE;
                  }
              else
                  return FALSE;
          }
      }
  }
  
  function userCheck($mysqli, $showOption) {
  if ($showOption === "all") {
      $query = "SELECT * FROM ".$GLOBALS['members'];
  } elseif ($showOption === "user") {
      $query = "SELECT * FROM ".$GLOBALS['members']." WHERE id = ".$_GET['userId'];
  } elseif( $showOption === "group") {
      $query = "SELECT * FROM ".$GLOBALS['members']." WHERE classGroup = ".$_GET['groupId'];
  }
      $result = $mysqli->query($query);
      while ($row = mysqli_fetch_row($result)) {
          $usertype = '';
          if ($row[5] == 0) {
              $usertype = 'Normal user';
          } elseif ($row[5] == 1) { 
              $usertype = 'Super user';
          } elseif ($row[5] == 2) {
              $usertype = 'Admin';
          }
  
          $groupName = groupReply($mysqli, $row[7], false);
          echo '<tr id="'. $row[0] .'">
                  <td>'.$row[0].'</td>
                  <td>'.$row[1].'</td>
                  <td>'.$row[2].'</td>
                  <td>'.$usertype.'</td>
                  <td>'.$groupName.'</td>
                  </tr>';
      }
  }
  
  function groupCheck($mysqli) {
      $query = "SELECT * FROM ".$GLOBALS['classGroup'];
      $result = $mysqli->query($query);
      while ($row = mysqli_fetch_row($result)) {
          echo '<tr id="'. $row[0] .'">
                  <td>'.$row[0].'</td>
                  <td>'.$row[1].'</td>
                  </tr>';
      }
  }
  
  function historyCheck($mysqli) {
    $query = "SELECT * FROM ".$GLOBALS['studentHistory']." WHERE userId = ".$_GET['userId'];
    $result = $mysqli->query($query);
    while ($row = mysqli_fetch_row($result)) {
  
      $qAA = json_decode(($row[3]), true);
      $count = count($qAA);
  echo $count;
          echo '<tr id="'. $row[0] .'">
                  <td rowspan="'.$count.'">'.$row[0].'</td>
                  <td rowspan="'.$count.'">'.$row[2].'</td>';
          $firstItr = true;
          while ($count > 0) {
  
  $att = 0;
  $ans = "";
  $col = "";
  switch ($qAA[$count-1]['attempts']) {
    case 0: $att = First; break;
    case 1: $att = Second; break;
    case 2: $att = "Over"; break; 
  } 
  
  switch ($qAA[$count-1]['answered']) {
    case 0: $ans = "wrong"; $col="danger"; break;
    case 1: $ans = "right"; $col="success"; break;
  }
  
            if ($firstItr) {
              $subtr = $count+1;
              $firstItr = false;
              echo '<td class="'.$col.'">'.($subtr-$count).'</td>
                    <td class="'.$col.'">'.$ans.'</td>
                    <td class="'.$col.'">'.$att .'</td></tr>';
            } else {
            echo '<tr class="'.$col.'"><td>'.($subtr-$count).'</td>
                  <td>'.$ans .'</td>
                  <td>'.$att .'</td></tr>';
  
            }
                  $count --;
          }
    }
  
  }
  
  function crosswordList($mysqli, $crosswordId, $questionId) {
      $questionToEdit = "";
  
      $query = "SELECT * FROM ".$GLOBALS['crosswordPuzzles']." where CrosswordID = ".$crosswordId;
      $result = $mysqli->query($query);
      while ($row = mysqli_fetch_row($result)) {
        /*
        ** <td>Question ID</td>
        ** <td>Question</td>
        ** <td>Answer</td>
        ** <td>Tile Code</td>
        */
        $tileCode = "";
        if (($row[5] == "Not Assigned yet")) {
          $tileCode = "class='danger'>".$row[5];
        } else {
          $tileCode = ">".$row[5];
        }
          echo '<tr qid="'. $row[0] .'">
                  <td>'.$row[2].'</td>
                  <td>'.$row[3].'</td>
                  <td>'.$row[4].'</td>
                  <td '.$tileCode.'</td>
                  <td></td>
                  </tr>';
          if ($row[0] == $questionId) {
              echo "
                  <tr qid='form'>
                      <form id='crosswordQEdit' action='includes/crosswordQEdit.php' method='post'>
                          <div class='form-group'>
                          <input type='hidden' name='crosswordId' form ='crosswordQEdit' id='crosswordId' value='".$_GET['crosswordId']."'/>
                          <input type='hidden' name='qid' form ='crosswordQEdit' id='qid' value='".$row[2]."'/>
                          <td></td>
                          <td><textarea name='question' form='crosswordQEdit' class='form-control' id='question' autofocus>".$row[3]."</textarea></td>
                          <td><textarea name='answer' form='crosswordQEdit' class='form-control' id='answer' autofocus>".$row[4]."</textarea></td>
                          <td><button type='submit' class='btn btn-default'>Submit</button></td>
                          </div>
                      </form>
                  </tr>  
              ";
          }
      }
  }
  
  function listOfCrosswordAvail($mysqli) {
    
    $queryList = "SELECT * FROM ".$GLOBALS['crosswordMaster'];
    $resultList = $mysqli->query($queryList);
  
    if (mysqli_num_rows($resultList) == 0) {
      echo "<tr><td colspan='4'>No existing crossword listed</td></tr>";
    } else {
      $data = array();
      $queryForCrosswordValidity = "SELECT DISTINCT crosswordID FROM ".$GLOBALS['crosswordPuzzles'].
                                    " WHERE TileCode = 'Not Assigned yet'";
      $resultForCrosswordValidity = $mysqli->query($queryForCrosswordValidity);
      while ($rowForCrosswordValidity = mysqli_fetch_row($resultForCrosswordValidity)) {
        array_push($data, $rowForCrosswordValidity[0]);
      }

      while ($rowList=mysqli_fetch_row($resultList)) {
        $type = "";
        $title = "";
        if (in_array($rowList[0], $data, TRUE)) {
          $type = "danger";
          $title = "Crossword is not fully assigned.";
        } else {
          $type = "success";
          $title = "Crossword is fully assigned.";
        }
        echo "<tr class='".$type."' title = '".$title."' id=".$rowList[0].">
              <td>".$rowList[0]."</td>
              <td>".$rowList[1]."</td>
              <td>".$rowList[2]."</td>
              <td>".$rowList[3]."</td></tr>";
      }
    }
  }

  // Checks for classGroupName given a userID or a groupID
  function groupReply($mysqli, $id, $bool) {
      // $bool true means use uid
      // $bool false means use gid
  
      if ($bool === true) {
        $query = "SELECT classGroupName FROM groupIDToName WHERE uId = ".$id;
      } else {
        $query = "SELECT classGroupName FROM groupIDToName WHERE gId = ".$id;
      }
        $result = $mysqli->query($query);
  
        while ($row=mysqli_fetch_row($result)) {
           return $row[0];
      }
  
  }
  
  function esc_url($url) {
  
      if ('' == $url) {
          return $url;
      }
  
      $url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);
  
      $strip = array('%0d', '%0a', '%0D', '%0A');
      $url = (string) $url;
  
      $count = 1;
      while ($count) {
          $url = str_replace($strip, '', $url, $count);
      }
  
      $url = str_replace(';//', '://', $url);
  
      $url = htmlentities($url);
  
      $url = str_replace('&amp;', '&#038;', $url);
      $url = str_replace("'", '&#039;', $url);
  
      if ($url[0] !== '/') {
          // We're only interested in relative links from $_SERVER['PHP_SELF']
          return '';
      } else {
          return $url;
      }
  }
  
  function loginNavBarAction($mysqli) {
      if ((login_check($mysqli) == true)) {
        $userType = "";
        echo 'Logged in as ';
  
  switch(role_check($mysqli)) {
    case 0:
      $userType = "normalUser";
      break;
    case 1:
      $userType = "superUser";
      break;
    case 2:
      $userType = "admin";
      break;
  }
  
        if ((role_check($mysqli) == 0 || 1) && !debugPermissionCheck($mysqli)) {
          echo $userType.' ';
        } elseif (debugPermissionCheck($mysqli)) {
          echo '
            <span class="dropdown">
            <span data-toggle="dropdown"><b>'.$userType.'</b><span class="caret"></span></span>
              <ul class="dropdown-menu">
                <li><a href="/includes/changePermissions.php?debug=2">admin</a></li>
                <li><a href="/includes/changePermissions.php?debug=1">superUser</a></li>
                <li><a href="/includes/changePermissions.php?debug=0">normalUser</a></li>
              </ul>
            </span>';
        }
      echo htmlentities($_SESSION['username']);
      echo ' of Group ';
      echo groupReply($mysqli, $_SESSION['user_id'], true); 
      }
      echo '&emsp;';
  }
  
  function remove_utf8_bom($text) {
      $bom = pack('H*','EFBBBF');
      $text = preg_replace("/^$bom/", '', $text);
      return $text;
  }
