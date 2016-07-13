<?php
    
    include_once './db_connect.php';
    include_once './functions.php';

    sec_session_start(); // Our custom secure way of starting a PHP session.

if (isset($_GET['debug']) && debugPermissionCheck($mysqli)) {

    $perm = $_GET['debug'];
    $id = $_SESSION["user_id"];

    $sql = "UPDATE ".$GLOBALS['members']." SET permissions = '" . $perm . "' WHERE id = " . $id;

        if ($mysqli->query($sql) === TRUE) {
            header("Location: ../index.php");
            echo "Permission set successfully." . "<br>";
        } else {
            echo "Error: " . $sql . "<br>" . $mysqli->error . "<br><br>";
        }

} else {
    $permId = json_decode($_POST['permId'], true);

    $id = $permId["id"];
    $perm = $permId["permissions"];
    $debugSettings = "";

// Check if setting to admin and thus, change the debug permissions
if ($perm === 2) {
    $debugStmt = ", debugPermissions = 1";
} else {
    $debugStmt  = ", debugPermissions = 0";
}

    $sql = "UPDATE ".$GLOBALS['members']." SET permissions = '".$perm."'".$debugStmt." WHERE id = " . $id;

        if ($mysqli->query($sql) === TRUE) {
            header ('location: ../users/users.php');
            echo "Permission set successfully." . "<br>";
        }
        else {
            echo "Error: " . $sql . "<br>" . $mysqli->error . "<br><br>";
        }
    }

// Changing the session variable
$_SESSION['permissions'] = $perm;
?>
