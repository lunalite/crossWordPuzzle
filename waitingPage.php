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
       
    </head>
    <body>
        <?php if ((login_check($mysqli) == true) && userInSession($mysqli)) : ?>
        <p>You have joined the session.</p>
        <table>
            <tr>
                Teams within session:
            </tr>     
               <?php
                    sessionUserCheck($mysqli);                 
               ?>
        </table>
            
            <?php 
                if (gateCheck($mysqli))
                header('Location: /xwordpuzzlestandalone/main_xword.html');
            ?>

        <?php elseif (!userInSession($mysqli)) : ?>
            <p> You are not in session. Please go <a href='user.php'>back</a> and join a Session</p>

        <?php else : ?>
            <p>
                <span class="error">You are not authorized to access this page.</span> Please <a href="index.php">login</a>.
            </p>
        <?php endif; ?>
    </body>
</html>
