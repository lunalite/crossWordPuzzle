<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

sec_session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Secure Login: Protected Page</title>
        <link rel="stylesheet" href="styles/main.css" />
    </head>
    <body>
        <?php if ((login_check($mysqli) == true) && role_check() == 0) : ?>
        <p>Welcome <?php echo htmlentities($_SESSION['username']); ?>!</p>
        <form action="sessionJoin.php">
            <input type="submit" name="joinSession" value="Join Session">
        </form>
            <p>Return to <a href="index.php">login page</a></p>
        <?php elseif ((login_check($mysqli) == true) && role_check() == 1)  : ?>
            <p>
                <span class="error">You are a super user. Login to a normal user.</span>
            </p>
        <?php else : ?>
            <p>
                <span class="error">You are not authorized to access this page.</span> Please <a href="index.php">login</a>.
            </p>
        <?php endif; ?>
    </body>
</html>
