<?php
include_once 'includes/php-connect.php';
include_once '../includes/psl-config.php';
?>

<html>
    <head>

    </head>
    <body>
        Please input the questions and answers: <br>
        Format it as follows: <br>
        <p>
            QN) XXX _ XXX. (N WORDS) ANSWER <br>
            1) XXX _ XXX. (2 WORDS) OPERATING ACTIVITIES<br>
            2) _ XXXXXX. (1 WORD) INVESTING<br>
        </p>
        Do note that the bracket placements are important. <br>
        <form id="qBank" action="includes/qnInput.php" method="post">
            Input: <br>
            <textarea name="questions" form="qBank" rows="30" cols="50" autofocus></textarea>
            <br>
            <input type="submit" value="Submit"> 
        </form>
    </body>
</html>