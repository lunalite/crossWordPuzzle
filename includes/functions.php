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

function role_check() {
    //Set variable permissions for the different roles
    $permissions = $_SESSION['permissions'];

    //$permissions = 0 for normal users
    //$permissions = 1 for super users
    //$permissions = 2 for admin
    return $permissions;
}

// For checking of available sessions into table
function availSessionCheck($mysqli) {
    $result = $mysqli->query("SELECT * FROM ".$GLOBALS['availableSessions']." WHERE online != 0");
    if (mysqli_num_rows($result) == 0) {
        echo '<tr><td>No sessions online.</td></tr>';
    }
    else {
        while ($row=mysqli_fetch_row($result)) {
            if ($row[2] == 1) 
                $online = 'Yes';
            elseif ($row[2] == 2) 
                $online = 'Started';
            $tbp = '<tr>
                    <td>'.$row[0].'</td>
                    <td>'.$row[3].'</td>
                    <td>'.$row[1].'</td>
                    <td>'.$online.'</td>';
            $innerQuery = "SELECT username FROM ".$GLOBALS['members']." WHERE id in 
            (SELECT userId FROM ".$GLOBALS['sessionJoin']." WHERE sessId = " . $row[0] . ")";

            $innerResult = $mysqli->query($innerQuery);
            if (mysqli_num_rows($innerResult) == 0) {
                $tbp = $tbp . '<td> No teams </td></tr>';
            }
            else {
                $tbp = $tbp . '<td>';
                while ($innerRow=mysqli_fetch_row($innerResult)) {
                     $tbp = $tbp . $innerRow[0] . ' ';
                }
                $tbp = $tbp . '</td></tr>';
            }

            echo $tbp;
        }
    }
}


// For available sessions into select box
function sessionCheckD($mysqli) {
    $result = $mysqli->query("SELECT * FROM ".$GLOBALS['availableSessions']." WHERE online != 0");
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
/*
function sessionUserCheck($mysqli) {
    $query = "SELECT * FROM ".$GLOBALS['sessionJoin']." WHERE sessid = 
    (SELECT sessId FROM ".$GLOBALS['sessionJoin']." WHERE userId = " . $_SESSION['user_id'] . ")";
    $result = $mysqli->query($query);
    if (mysqli_num_rows($result) == 0) {
        echo '<tr><td>Some error happened.</td></tr>';
    }
    else {
        while ($row=mysqli_fetch_row($result)) {
            $innerQuery = "SELECT username FROM ".$GLOBALS['members']." WHERE id = " . $row[1];
            $innerResult = $mysqli->query($innerQuery);
            $innerRow=mysqli_fetch_row($innerResult);
            $tbp = '<tr><td>' . $innerRow[0] . '</td></tr>';
            echo $tbp;
        }
    }
}
*/
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

function userCheck($mysqli) {
    $query = "SELECT * FROM ".$GLOBALS['members'];
    $result = $mysqli->query($query);
    while ($row = mysqli_fetch_row($result)) {
        $usertype = '';
        if ($row[5] == 0) 
            $usertype = 'Normal user';
        elseif ($row[5] == 1) 
            $usertype = 'Super user';
        elseif ($row[5] == 2) 
            $usertype = 'Admin';
        echo '<tr id="'. $row[0] .'">
                <td>'.$row[0].'</td>
                <td>'.$row[1].'</td>
                <td>'.$row[2].'</td>
                <td>'.$usertype.'</td>
                </tr>';
    }
}

function crosswordCheck($mysqli) {
    $query = "SELECT * FROM ".$GLOBALS['crosswordMaster'];
    $result = $mysqli->query($query);
    while ($row = mysqli_fetch_row($result)) {

        echo '<tr id="'. $row[0] .'">
                <td>'.$row[0].'</td>
                <td>'.$row[1].'</td>
                <td>'.$row[2].'</td>
                <td>'.$row[3].'</td>
                </tr>';
    }
}

function crosswordList($mysqli, $crosswordId, $questionId) {
    $questionToEdit = "";

    $query = "SELECT * FROM ".$GLOBALS['crosswordPuzzles']." where CrosswordID = ".$crosswordId;
    $result = $mysqli->query($query);
    while ($row = mysqli_fetch_row($result)) {
        echo '<tr qid="'. $row[0] .'">
                <td>'.$row[2].'</td>
                <td>'.$row[3].'</td>
                <td>'.$row[4].'</td>
                <td>'.$row[5].'</td>
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