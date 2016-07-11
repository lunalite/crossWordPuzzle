<?php
    
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

sec_session_start();

if ((login_check($mysqli) == true) && (role_check($mysqli) != 0)) {
    
    $q = $_POST['crosswordSearch'];
    $query = "SELECT crosswordDescription FROM ".$GLOBALS['crosswordMaster']." WHERE crosswordId = ".$q;
        if (mysqli_num_rows($result) == 0) {
            echo 'No such crosswordID';
        }
        else {
            $arr = mysqli_fetch_row($result);
            $des = '\''.$arr[0].'\'';
            
            $sql = "INSERT INTO availablesessions (description, online, crosswordID) VALUES ($des, 1, $q)";

            if ($mysqli->query($sql) === TRUE) {
                header('Refresh: 3; url=master.php');
                echo "Session is created. Redirecting back to previous page in 3 seconds,";
            } else {
                echo "Error updating record: " . $mysqli->error;
            }
        }
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Session adding Success</title>
    </head>
    <body>
        <p>Click <a href="javascript: window.history.go(-1)">here</a> to go back.</p>
    </body>
</html>

