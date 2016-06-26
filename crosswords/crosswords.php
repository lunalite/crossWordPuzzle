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
        <script src="https://js.pusher.com/3.1/pusher.min.js"></script>
        <!-- Bootstrap core JavaScript-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
        <script src="../css/js/bootstrap.min.js"></script>
        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="../css/js/ie10-viewport-bug-workaround.js"></script>
        <script>

            
            
            $(function () {
                var xWordListRes = $('#crosswordList');
                var xWordSearch = $('#crosswordSearch');
            
                xWordSearch.keyup(function () {
                    var searchid = $(this).val();
                    // console.log(searchid);
            
                    $.ajax({
                        type: "POST",
                        datatype: 'json',
                        url: "../includes/listOfCrosswordAvail.php",
                        data: { searchQ: searchid },
                        cache: false,
                        success: function (data) {
                             //console.log(data);
                            $("#result").html(data).show();
                        }
                    });
                });
            });
            
        </script>

    </head>
    <body>
        <?php if ((login_check($mysqli) == true) && role_check() != 0) : ?>


        <nav role="navigation" class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="../index.php" style="color:white;">REP Crossword Master Page</a>
                </div>
                <!-- Collection of nav links, forms, and other content for toggling -->
                <div id="navbarCollapse" class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="./crosswords.php" style="color:white;">Crosswords</a></li>
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
                                
                                echo htmlentities($_SESSION['username']);
                                endif;
                            ?>
                        &emsp;
                            <a class="btn btn-success" href="../includes/logout.php" role="button">Log out</a>
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
                        <?php if (role_check() == 2) : ?>
                        <br>
                        <form action="../phpretrieval/crosswordAddition.php">
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
            elseif ((login_check($mysqli) == true) && (role_check() == 1 || role_check() == 0)) :
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
