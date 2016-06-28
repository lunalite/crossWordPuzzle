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
        <title>REP Xword index</title>
        <link href="css/bootstrap.css" rel="stylesheet">
        <link href="css/jumbotron.css" rel="stylesheet">
        <!-- Bootstrap core JavaScript-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
        <script src="css/js/bootstrap.min.js"></script>
        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="css/js/ie10-viewport-bug-workaround.js"></script>
        <!-- Hashing function -->
        <script type="text/JavaScript" src="js/sha512.js"></script>
        <script type="text/JavaScript" src="js/forms.js"></script>
        <script>
            $(function () {
                $('input').keypress(function (e) {
                    console.log(e.which);
                    var key = e.which;
                    if (key == 13)  // the enter key code
                    {
                        $('#submission').click();
                        return false;
                    }
                });
            });
        </script>
    </head>

    <body>
        <!-- PHP code for checking if there's an error logging in by $_GET error -->
        <?php
            // role_check() == 1 for the case of super users OR 2 for the case of admin
            if ((login_check($mysqli) == true) && (role_check() == 1 || role_check() == 2) ) {
                echo '<script>';
                echo 'window.location.href="./master.php";';
                echo '</script>'; 
            }
            
            // role_check() == 0 for the case of normal users
            elseif ((login_check($mysqli) == true) && role_check() == 0) {
                echo '<script>';
                echo 'window.location.href="./user.php";';
                echo '</script>'; 
            }
        ?>

        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-brand" href="./index.php" style="color:white;">REP Crossword Index</a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <div class="navbar-right navbar-form" style="color:white;">

                        <?php if ((login_check($mysqli) == true)) : ?>
                        Logged in as
                        <?php
                            if (role_check() == 0)
                                echo htmlentities('normal_user ');
                            elseif (role_check() == 1)
                                echo htmlentities('super_user ');
                            elseif (role_check() == 2)
                                echo htmlentities('admin ');
                            
                            echo htmlentities($_SESSION['username']);
                        ?>
                        &emsp;
                        <a class="btn btn-success" href="includes/logout.php" role="button">Log out</a>
                    </div>

                    <?php elseif ((login_check($mysqli) == FALSE)) : endif; ?>
                </div><!--/.navbar-collapse -->
            </div>
        </nav>
        <div class="jumbotron">
            <div class="container">
                <div class="col-md-6 col-md-offset-3">
                    <form class="form-signin" action="includes/process_login.php" method="post" name="login_form">
                        <h2 class="form-signin-heading">Please sign in:</h2>
                        <label for="inputEmail" class="sr-only">Email address</label>
                        <input type="text" name="email" class="form-control" placeholder="Email address" required autofocus>
                        <label for="inputPassword" class="sr-only">Password</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="remember-me"> Remember me
                            </label>
                        </div>
                        <input id="submission" class="btn btn-lg btn-primary btn-block" type="button" value="Login" onclick="formhash(this.form, this.form.password);" />
                    </form><br>
                    <?php
                        
                        if (isset($_GET['error'])) {
                            echo '<p class="error">Error Logging In!</p>';
                        }
                    ?>
                    <p>If you don't have a login, please <a href="register.php">register</a></p>
                </div>
            </div>

        </div>
        <div class="container">
            <div class="row">
                <hr><p>&copy; 2016 Product of REP</p>
            </div>
        </div>
    </body>
</html>
