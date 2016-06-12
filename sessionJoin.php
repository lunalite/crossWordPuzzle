<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

sec_session_start();

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title></title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
        <script>
            // Remove storage whenever user joins new session
            sessionStorage.removeItem('attempts');
            sessionStorage.removeItem('answered');
        </script>
    </head>
    <body>
        <?php if ((login_check($mysqli) == true)) : ?>
        <p>You have joined the session.</p>
        <table>
            <tr>
                Teams within session:
            </tr>     
            <tr class="sessionTeamUpdate">
                
            </tr>
        </table>
            <p>Return to <a href="index.php">login page</a></p>
        <?php else : ?>
            <p>
                <span class="error">You are not authorized to access this page.</span> Please <a href="index.php">login</a>.
            </p>
        <?php endif; ?>
    </body>
</html>
