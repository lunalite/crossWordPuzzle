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
        <!--<canvas id="myCanvas" style="border:1px solid #d3d3d3;">
        Your browser does not support the HTML5 canvas tag.</canvas>
        <div id="hi"></div>
        
        <script type="text/javascript">
            window.onload = startAddingXWord;</script>
        <button onClick="save()">Save</button>
        -->

        Assume that adding of crossword is done.
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
        <div id="result"></div>

        <?php else : ?>
            <p>
                <span class="error">You are not authorized to access this page.</span> 
                Please <a href="index.php">login</a> to a superuser.
            </p>
        <?php endif; ?>
    </body>
</html>
