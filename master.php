<?php

include_once 'includes/register.inc.php';
include_once 'includes/functions.php';

sec_session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
        <!--<script type="text/javascript" src="js/XWordAdd.js"></script> -->
        <script>

            $(function () {
                var xWordListRes = $('#crosswordList');
                var xWordSearch = $('#crosswordSearch');

                xWordSearch.keyup(function () {
                    var searchid = $(this).val();
                    console.log(searchid);

                    $.ajax({
                        type: "POST",
                        datatype: 'json',
                        url: "includes/listOfCrosswordAvail.php",
                        data: { searchQ: searchid },
                        cache: false,
                        success: function (data) {
                            console.log(data);
                            $("#result").html(data).show();
                        }
                    });
                });



            });

        </script>
    </head>
    <body>


        <?php if ((login_check($mysqli) == true) && role_check() == 1) : ?>

        <p>If you are done, please <a href="includes/logout.php">log out</a>.</p>
        <p>You are currently logged <?php echo $logged ?> as <?php echo htmlentities($_SESSION['username'])?>.</p>

        Adding of crosswords:<br>
        <a href="phpretrieval/crosswordAddition.php">Click here</a>
        <br>
        <br>
        Commands:<br>
        list - returns the available crosswords with their descriptions<br>
        submitting the crosswordName itself - creates a session<br>
        <br>
        <form id="createSession" action="sessionOn.php" method="post">
            <input type="text" name="crosswordSearch" id="crosswordSearch">
            <input type="submit" value="Create Session">
        </form>
        <br>
        <div id="result"></div><br>
        <table id="sessionsOnline">
            <tr>Available sessions</tr>
            <tr>
                <td>Session id</td>
                <td>Crossword description</td>
                <td>Online</td>
                <td>Teams joined</td>
            </tr>
            <?php
                sessionCheck($mysqli);
            ?>
        </table>


        <form action="includes/sessionStart.php" id="startSession" method="post">
            <select name="sessId" form="startSession">
            <?php
                sessionCheckD($mysqli);
            ?>
            </select>
            <input type="submit" name="startSession" value="Start Session">
        </form>

        <!--*********************************************-->
        <!-- For the case of not being a super user -->
        <?php else : ?>
            <p>
                <span class="error">You are not authorized to access this page.</span> 
                Please <a href="index.php">login</a> to a superuser.
            </p>
        <?php endif; ?>
    </body>
</html>
