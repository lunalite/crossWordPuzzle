<?php

include_once 'includes/register.inc.php';
include_once 'includes/functions.php';

sec_session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <script type="text/javascript" src="js/XWordAdd.js"></script> 
    </head>
    <body>
        <?php if ((login_check($mysqli) == true) && role_check() == 1) : ?>
        <canvas id="myCanvas" style="border:1px solid #d3d3d3;">
        Your browser does not support the HTML5 canvas tag.</canvas>
        <div id="hi"></div>
        <script type="text/javascript">

            window.onload = startAddingXWord;</script>

        <button onClick="save()">Save</button>

        <?php else : ?>
            <p>
                <span class="error">You are not authorized to access this page.</span> 
                Please <a href="index.php">login</a> to a superuser.
            </p>
        <?php endif; ?>
    </body>
</html>
