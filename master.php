<?php
    include_once 'includes/db_connect.php';
    include_once 'includes/functions.php';
    
    sec_session_start();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>REP Crossword Master Page</title>
        <link href="css/bootstrap.css" rel="stylesheet">
        <link href="css/jumbotron.css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
        <script src="css/js/bootstrap.min.js"></script>
        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="css/js/ie10-viewport-bug-workaround.js"></script>
        <script>        
            
            $(function () {
                var xWordListRes = $('#crosswordList');
                var xWordSearch = $('#crosswordSearch');
                var xWordResult = $("#result");

                xWordSearch.keyup(function () {
                    var searchid = $(this).val();
            
                    $.ajax({
                        type: "POST",
                        datatype: 'json',
                        url: "includes/listOfCrosswordAvail.php",
                        data: { searchQ: searchid },
                        cache: false,
                        success: function (data) {
                            var parsedData = JSON.parse(data);
                            xWordResult.find('tr').remove();

                            if (parsedData === 'Wrong') {
                                xWordResult.append("<tr><td>No such ID OR Wrong Command</td></tr>");
                            } else {
			        for (var i = 0; i < parsedData.length; i ++) { 
				    xWordResult.append("<tr><td>"+parsedData[i].crosswordId+
						"<td>"+parsedData[i].crosswordDescription+"</td>"+
						"<td>"+parsedData[i].PuzzleName+"</td>"+
						"</tr>");
			        }
			    } 
                        }
                    });
                });
                
                xWordSearch.trigger("keyup");
            });
            
            
        </script>

    </head>
    <body>
    <!-- Only for admins and super users -->
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
                <a class="navbar-brand" href="./index.php" style="color:white;">REP Crossword Master Page</a>
            </div>
            <!-- Collection of nav links, forms, and other content for toggling -->
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
                            
                            echo htmlentities($_SESSION['username']);
                            endif;
                        ?>
                        &emsp;
                        <a class="btn btn-success" href="includes/logout.php" role="button">Log out</a>
                    </div>
                </div>
                </div>
            </div>
        </nav>


        <div class="jumbotron">
            <div class="container">
                <div class="row">
                <div class="col-xs-6 col-md-8 col-md-offset-2">
                        <form action="passwordChange.php">
                            <input type="submit" class="btn btn-primary btn-sm" value="Change Password">
                        </form>

                        <!-- Only admins can create puzzle-->
                        <?php if (role_check() == 2) : ?>
                        <br>
                        <form action="./grant.php">
                            <input type="submit" class="btn btn-danger btn-sm" value="Grant priviledges">
                        </form>
                        <?php endif; ?>

                        <!-- Command section -->
                        <h3>Commands</h3>
                        <ol>
                            <li>Input 'list' into commands below - returns the available crosswords with their descriptions.</li>
                            <li>Input the crossword ID you have chosen - creates a session.</li>
                            <li>Hint: To view crosswords, click on crossword on the navigation bar on top.</li>
                        </ol>
                        <div class="row">
                        Results: 
			<table class="table table-hover">
			<thead>
			<tr>
			<th>Crossword ID</th>
			<th>Description</th>
			<th>Puzzle name</th>
			</tr>
			</thead>
			<tbody id="result">
			</tbody>
			</table>
			<br>
                        </div>
                        <form id="createSession" action="sessionOn.php" method="post">
                            <fieldset class="form-group">
                                <div class="col-xs-12 col-md-6">
                                    <label for="crosswordSearch">Commands for creating session:</label>
                                    <input type="text" name="crosswordSearch" class="form-control" id="crosswordSearch" placeholder="Input crossword ID" value="list">
                                </div>
                            </fieldset>
                            <input type="submit" class="btn btn-primary btn-sm" value="Create Session">
                        </form>
                        <br>

                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-xs-6 col-md-8 col-md-offset-2">

                    <!-- The available sessions section -->
                    <h3>Available sessions</h3>
                    <table id="sessionsOnline" class="table table-striped">
                            <tr>
                                <th>Session ID</th>
                                <th>Crossword ID</th>
                                <th>Crossword Description</th>
                                <th>Online</th>
                                <th>Teams</th>
                        </tr>
                        <?php
                            availSessionCheck($mysqli);
                        ?>
                    </table>
                    <form action="includes/sessionStart.php" id="startSession" method="post">
                        <select name="sessId" form="startSession">
                            <?php
                                sessionCheckD($mysqli);
                            ?>
                        </select>
                        <input type="submit" class="btn btn-primary btn-sm" name="startSession" value="Start Session">
                        <input type="submit" class="btn btn-primary btn-sm" name="deleteSession" value="Delete Session">
                    </form>

                    <hr><p>&copy; 2016 Product of REP</p>
                </div>
            </div>
        </div>

        <!--**********************************************************************-->
        <!-- For the case of wrong login -->
        <?php
            elseif ((login_check($mysqli) == true) && (role_check() == 1 || role_check() == 0)) :
                       echo '<script>';
                       echo 'window.location.href="./user.php";';
                       echo '</script>'; 
                   else : 
                       echo '<script>';
                       echo 'window.location.href="./index.php";';
                       echo '</script>';
                   endif;
        ?>
    </body>
</html>
