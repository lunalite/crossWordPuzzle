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
        <title>REP Crossword Master Page</title>
        <link href="../css/bootstrap.css" rel="stylesheet">
        <link href="../css/jumbotron.css" rel="stylesheet">
        <!-- Bootstrap core JavaScript-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="../css/js/bootstrap.min.js"></script>
        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="../css/js/ie10-viewport-bug-workaround.js"></script>
        <script>
            $(function () {
                var xWordListRes = $('#crosswordList');
                var xWordSearch = $('#crosswordSearch');
            
                xWordSearch.keyup(function () {
                    var searchid = $(this).val();
                    $.ajax({
                        type: "POST",
                        datatype: 'json',
                        url: "../includes/listOfCrosswordAvail.php",
                        data: { searchQ: searchid },
                        cache: false,
                        success: function (data) {
                            $("#result").html(data).show();
                        }
                    });
                });
            });
        </script>

    </head>
    <body>
        <?php if ((login_check($mysqli) == true) && role_check($mysqli) != 0) : ?>
        <nav role="navigation" class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="../master.php" style="color:white;">REP Crossword Master Page</a>
                </div>
                <!-- Collection of nav links, forms, and other content for toggling -->
                <div id="navbarCollapse" class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="#" style="color:white;">Crosswords</a></li>
                        <li><a href="../reviews/reviews.php" style="color:white;">Reviews</a></li>
                    <li><a href="../users/users.php" style="color:white;">Users</a></li>
                    </ul>
                    <div id="navbar" class="navbar-collapse collapse">
                        <div class="navbar-right navbar-form" style="color:white;">
                       <?php loginNavBarAction($mysqli); ?>
                        <a class="btn btn-success" href="includes/logout.php" role="button">Log out</a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <div class="jumbotron">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <form action="./crosswordView.php">
                            <input type="submit" class="btn btn-primary btn-sm" value="View Crossword">
                        </form>
                        <!-- Only admins can create puzzle-->
                        <?php if (role_check($mysqli) == 2) : ?>
                        <br>
                        <form action="./crosswordAddition.php">
                            <input type="submit" class="btn btn-danger btn-sm" value="New Crossword">
                        </form>
                        <br>
                        <?php endif; ?>
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <hr><p>&copy; 2016 Product of REP</p>
        </div>

        <!--**********************************************************************-->
        <!-- For the case of wrong login -->
        <?php
            elseif ((login_check($mysqli) == true) && (role_check($mysqli) == 1 || 0)) :
                       echo '<script>';
                       echo 'window.location.href="../user.php";';
                       echo '</script>'; 
                   else : 
                       echo '<script>';
                       echo 'window.location.href="../index.php";';
                       echo '</script>';
                   endif;
        ?>
    </body>
</html>
