<?php
    include_once 'includes/db_connect.php';
    include_once 'includes/functions.php';
    
    sec_session_start();

    $userID = $_GET['userId'];
     
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>REP Xword user page</title>
        <link href="css/bootstrap.css" rel="stylesheet">
        <link href="css/jumbotron.css" rel="stylesheet">
        <script src="https://js.pusher.com/3.1/pusher.min.js"></script>
        <!-- Bootstrap core JavaScript-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
        <script src="css/js/bootstrap.min.js"></script>
        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="css/js/ie10-viewport-bug-workaround.js"></script>
    </head>
    <body>
        <?php if ((login_check($mysqli) == true) && role_check() == 2) : ?>
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-brand" href="./index.php" style="color:white;">REP Crossword Grant User Page</a>
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
                            
                            echo htmlentities($_SESSION['username'])
                        ?>
                        &emsp;
                        <a class="btn btn-success" href="includes/logout.php" role="button">Log out</a>
                    </div>

                    <?php elseif ((login_check($mysqli) == FALSE)) : ?>
                    <form class="navbar-form navbar-right">
                        <div class="form-group">
                            <input type="text" placeholder="Email" class="form-control">
                        </div>
                        <div class="form-group">
                            <input type="password" placeholder="Password" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-success">Sign in</button>
                    </form>
                    <?php endif; ?>
                </div><!--/.navbar-collapse -->
            </div>
        </nav>
        
        <div class="jumbotron">
            <div class="container">
                <div class="row">
                    <div class="col-xs-6">
                        <?php echo 'UserID: ' . $userID; ?>
                        <br>
                        //Click here to change password.
                        <br>
                        <h3>Change permissions:</h3>
                        <form action="includes/changePermissions.php" id="changePermissions" method="GET">
                            <select name="permId" form="changePermissions">
                                <option value='{"id":<?php echo $userID?>,"permissions":0}'>Normal user</option>
                                <option value='{"id":<?php echo $userID?>,"permissions":1}'>Super user</option>
                                <option value='{"id":<?php echo $userID?>,"permissions":2}'>Admin</option>
                            </select>
                            <input type="submit" class="btn btn-primary btn-sm" name="changePermissions" value="Change Permission">
                        </form>
                            
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
        <hr><p>&copy; 2016 Product of REP</p>
        </div></div>

        <!--**********************************************************************-->
        <!-- For the case of wrong login -->
        <?php
        elseif ((login_check($mysqli) == true) && role_check() == 0)  :
            header('location: ./user.php');
        elseif ((login_check($mysqli) == true) && role_check() == 1)  :
            header('location: ./master.php');
        else : 
            header('location: ./index.php');
        endif;
        ?>
    </body>
</html>
