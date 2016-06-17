<?php
    include_once '../includes/db_connect.php';
    include_once '../includes/functions.php';
    
    sec_session_start();
    
    $id=$_GET["id"];
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>REP Crossword Master Page</title>
        <link href="../css/bootstrap.css" rel="stylesheet">
        <link href="../css/jumbotron.css" rel="stylesheet">
        <script src="https://js.pusher.com/3.1/pusher.min.js"></script>
        <!-- Bootstrap core JavaScript-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
        <script src="../css/js/bootstrap.min.js"></script>
        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="../css/js/ie10-viewport-bug-workaround.js"></script>
        <style>
            .canvas,.qnsradio{
                float: left;
            }
                .canvas{
                background-color: black;
            }
        </style>
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
                    <div class="col-xs-12 col-md-10">
                        <div class="canvas">
                        <canvas id="myCanvas" style="border:1px solid #d3d3d3;">
                        Your browser does not support the HTML5 canvas tag.</canvas>
                        </div> 
                    </div>
                    <div class="col-xs-12 col-md-2">
                        <div id="instructions">
                            Start by clicking on an answer from the list.<br>
                            You can change the direction to allocate the answers below.
                        </div>
                        <div class="qnsradio">
                            <div class="mylist" style="cursor:pointer;">
                                <ul id="questionList"></ul>
                            </div>
                            <div class="radio">
                                <form name="myform" action="">
                                    <input type="radio" checked="checked" name="direction" value="horizontal">Horizontal<br>
                                    <input type="radio" name="direction" value="vertical">Vertical
                                </form>
                            </div>
                            <div class="submit">
                                <button type="button" class="btn btn-default" onclick="save()">Save</button>
                                <button type="button" class="btn btn-default" onclick="undo()" id="undo">Undo</button>
                            </div>
                            <br>
                            <a href="../master.php">Click here to go back after addition.</a>
                        </div>
                        
                        <script type="text/javascript">
                            var crosswordId = "<?php echo $id ?>";
                        </script>
                        <script type="text/javascript" src="js/script.js">
                        </script>

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
        <?php
            elseif ((login_check($mysqli) == true) && role_check() == 1)  :
                       header('location: ../user.php');
                   else :
                       header('location: ../index.php');
                   endif;
        ?>

    </body>
</html>

