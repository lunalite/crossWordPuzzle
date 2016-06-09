<?php
include_once '../includes/php-connect.php';
include_once '../includes/psl-config.php';
?>

<html>
    <head>

    </head>
    <body>
        Please input the questions and answers:
        <form id="qBank" action="includes/qnInput.php" method="post">
            Input: <br>
            <textarea name="questions" form="qBank" rows="30" cols="50" autofocus></textarea>
            <br>
            <input type="submit" value="Submit"> 
        </form>
    </body>
</html>