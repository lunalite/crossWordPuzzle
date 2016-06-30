<?php
    include_once 'includes/db_connect.php';
    include_once 'includes/functions.php';
    
    sec_session_start();
    
    $userID = $_GET['userId'];

    if ((login_check($mysqli) == true) && role_check() == 2) :
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>REP Xword Grant page</title>
        <link href="css/bootstrap.css" rel="stylesheet">
        <link href="css/jumbotron.css" rel="stylesheet">
        <!-- Bootstrap core JavaScript-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
        <script src="css/js/bootstrap.min.js"></script>
        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="css/js/ie10-viewport-bug-workaround.js"></script>
    </head>
    <body>
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-brand" href="./index.php" style="color:white;">REP Crossword Grant User Page</a>
                </div>
                <div id="navbarCollapse" class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="./crosswords/crosswords.php" style="color:white;">Crosswords</a></li>
                        <li><a href="./reviews/reviews.php" style="color:white;">Reviews</a></li>
                                                    <!--
                                                        <li class="dropdown">
                                                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">Messages <b class="caret"></b></a>
                                                            <ul role="menu" class="dropdown-menu">
                                                                <li><a href="#">Inbox</a></li>
                                                                <li><a href="#">Drafts</a></li>
                                                                <li><a href="#">Sent Items</a></li>
                                                                <li class="divider"></li>
                                                                <li><a href="#">Trash</a></li>
                                                            </ul>
                                                        </li> -->
                    </ul>
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

                        <?php elseif ((login_check($mysqli) == FALSE)) : endif; ?>
                    </div><!--/.navbar-collapse -->
                </div>
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
                        <form action="includes/changePermissions.php" id="changePermissions" method="POST">
                            <select name="permId" form="changePermissions">
                                <option value='{"id": <?php echo $userID?> ,"permissions":0}'>Normal user</option>
                                <option value='{"id": <?php echo $userID?> ,"permissions":1}'>Super user</option>
                                <option value='{"id": <?php echo $userID?> ,"permissions":2}'>Admin</option>
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
            </div>
        </div>

        <!--**********************************************************************-->
        <!-- For the case of wrong login -->
        <?php elseif ((login_check($mysqli) == true) && role_check() == 0)  :
            echo '<script>';
            echo 'window.location.href="../user.php"';
            echo '</script>';
            
        else :
            echo '<script>';
            echo 'window.location.href="../index.php"';
            echo '</script>';

        endif; ?>
    </body>
</html>
