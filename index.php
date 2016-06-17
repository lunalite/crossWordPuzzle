<?php

include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

sec_session_start();

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>REP Xword</title>

        <!-- Bootstrap -->
        <link href="css/bootstrap.css" rel="stylesheet">
    
        <!-- Hashing function -->
        <script type="text/JavaScript" src="js/sha512.js"></script> 
        <script type="text/JavaScript" src="js/forms.js"></script> 
    </head>
    
    <body>
        <!-- PHP code for checking if there's an error logging in by $_GET error -->
        <?php
        if (isset($_GET['error'])) {
            echo '<p class="error">Error Logging In!</p>';
        }

        // role_check() == 1 for the case of super users OR 2 for the case of admin
        elseif ((login_check($mysqli) == true) && (role_check() == 1 || role_check() == 2) ) {
            header('Location: master.php');
        }

        // role_check() == 0 for the case of normal users
        elseif ((login_check($mysqli) == true) && role_check() == 0) {
            header('Location: user.php');
        }
        ?> 


        <div class="container">
            <form class="form-signin" action="includes/process_login.php" method="post" name="login_form">
                <h2 class="form-signin-heading">Please sign in</h2>
                <label for="inputEmail" class="sr-only">Email address</label>
                <input type="text" name="email" class="form-control" placeholder="Email address" required autofocus>
                <label for="inputPassword" class="sr-only">Password</label>
                <input type="password" id="password" name ="password" class="form-control" placeholder="Password" required>
                <div class="checkbox">
                    <label>
                    <input type="checkbox" value="remember-me"> Remember me
                    </label>
                </div>

                <input class="btn btn-lg btn-primary btn-block" type="button" 
                   value="Login" 
                   onclick="formhash(this.form, this.form.password);" /> 
            </form>
            <br>
            <p>If you don't have a login, please <a href="register.php">register</a></p>
            <p>If you are done, please <a href="includes/logout.php">log out</a>.</p>
        </div> <!-- /container -->

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="js/bootstrap.js"></script>

    </body>
</html>