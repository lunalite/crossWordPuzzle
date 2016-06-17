<?php
    include_once '../includes/db_connect.php';
    include_once '../includes/functions.php';
    
    sec_session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>REP Xword user page</title>
        <link href="../css/bootstrap.css" rel="stylesheet">
        <link href="../css/jumbotron.css" rel="stylesheet">
        <script src="https://js.pusher.com/3.1/pusher.min.js"></script>
        <!-- Bootstrap core JavaScript-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
        <script src="../css/js/bootstrap.min.js"></script>
        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="../css/js/ie10-viewport-bug-workaround.js"></script>
    </head>
    <body>
        <?php if ((login_check($mysqli) == true) && role_check() == 2) : ?>
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-brand" href="../index.php" style="color:white;">REP Crossword Master Page</a>
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

                        <h3>Please input the questions and answers:</h3>
                        Format it as follows: <br>
                            Q)%XXX_XXX.%(N%WORDS)%ANSWER <br>
                        X : represents words/letters<br>
                            % : represents a space <br><br>
                            Example: <br>
                            1) Owners and other decision makers use this statement to evaluate how well a company has performed. _ (2 words) Income statement[Press enter]<br>
                            2) _ are profits accumulated within a company since the date of its incorporation that are available for dividend distribution. (2 words) Retained earnings<br>
                            <br>
                            Do note that the <b>bracket placements</b> and <b>spaces</b> are important. <br>
                            Also, after every questions, do take note to press enter before the next question is typed.<br>
                            

                        <form id="qBank" action="includes/qnInput.php" method="post">
                            <div class="form-group">
                                <label for="questions">Input</label>
                                <textarea name="questions" form="qBank" class="form-control" rows="3" id="questions" autofocus></textarea>
                            </div>
                            <button type="submit" class="btn btn-default">Submit</button>
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
        <?php elseif ((login_check($mysqli) == true) && role_check() == 1)  :
            header('location: ../user.php');
        else :
            header('location: ../index.php');
        endif; ?>
    </body>
</html>
