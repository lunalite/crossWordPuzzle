<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

sec_session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Xword: user page</title>
        <link rel="stylesheet" href="styles/main.css" />
    </head>
    <body>
        <?php if ((login_check($mysqli) == true) && role_check() == 0) : ?>
        <p>If you are done, please <a href="includes/logout.php">log out</a>.</p>
        <p>You are currently logged <?php echo $logged ?> as <?php echo htmlentities($_SESSION['username'])?>.</p>

        <!-- Option to start Xword game when gate is opened -->
        


        <table id="sessionsOnline">
            <tr>Available sessions</tr>
            <tr>
                <td>Session id</td>
                <td>Crossword description</td>
                <td>Online</td>
            </tr>
            <?php
                sessionCheck($mysqli);
            ?>
        </table>

        <?php if (!userInSession($mysqli)) : ?>

        <form action="includes/sessionJoin.php" id="sessionJoin" method="post">
            <select name="sessId" form="sessionJoin">
            <?php
                sessionCheckD($mysqli);
            ?>
            </select>
            <input type="submit" name="joinSession" value="Join Session">
        </form>

        <?php elseif (userInSession($mysqli)) : ?>
        <p>You are already in a session. Please wait...</p>
                    <?php if(gateCheck($mysqli)) {
                        header('Location: /xwordpuzzlestandalone/main_xword.html');}
                    ?>
        <?php endif; ?>

        <!--**********************************************************************-->
        <!-- For the case of wrong login -->
        <?php elseif ((login_check($mysqli) == true) && role_check() == 1)  : ?>
            <p><span class="error">You are a super user. Login to a normal user.</span></p>
        <?php else : ?>
            <p><span class="error">You are not authorized to access this page.
            </span> Please <a href="index.php">login</a>.</p>
        <?php endif; ?>
    </body>
</html>
