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
        <script src="https://js.pusher.com/3.1/pusher.min.js"></script>
        <!-- Bootstrap core JavaScript-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
        <script src="css/js/bootstrap.min.js"></script>
        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="css/js/ie10-viewport-bug-workaround.js"></script>
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
                        url: "includes/listOfCrosswordAvail.php",
                        data: { searchQ: searchid },
                        cache: false,
                        success: function (data) {
                            // console.log(data);
                            $("#result").html(data).show();
                        }
                    });
                });
            });
            
        </script>

    </head>
    <body>
        <?php if ((login_check($mysqli) == true) && role_check() != 0) : ?>
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-brand" href="./index.php" style="color:white;">REP Crossword Master Page</a>
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
                        <form action="passwordChange.php">
                            <input type="submit" class="btn btn-primary btn-sm" value="Change Password">
                        </form>

                        <!-- Only admins can create puzzle-->
                        <?php if (role_check() == 2) : ?>
                        <br>
                        <form action="phpretrieval/crosswordAddition.php">
                            <input type="submit" class="btn btn-danger btn-sm" value="New Crossword">
                        </form>
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
                        </ol>
                        <ul class="list-unstyled">
                            <li>Format of results shown - [[a,b,c]]</li>
                            <li>a = Crossword ID</li>
                            <li>b = Crossword Description</li>
                            <li>c = puzzle name</li>
                        </ul>
                        <div class="row">
                        Results will be shown here after inputting a command: <div id="result">[['a','b','c']]</div><br>
                            </div>
                        <form id="createSession" action="sessionOn.php" method="post">
                            <fieldset class="form-group">
                                <div class="col-xs-6">
                                    <label for="crosswordSearch">Commands for creation session:</label>
                                    <input type="text" name="crosswordSearch" class="form-control" id="crosswordSearch" placeholder="Input crossword ID">
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
                <div class="col-xs-6">
                    <!-- The available sessions section -->
                    <h3>Available sessions</h3>
                    <table id="sessionsOnline" class="table table-striped">
                        <tr>
                            <td>Session ID</td>
                            <td>Crossword Description</td>
                            <td>Online</td>
                            <td>Teams</td>
                        </tr>
                        <?php
                            sessionCheck($mysqli);
                        ?>
                    </table>
                    <form action="includes/sessionStart.php" id="startSession" method="post">
                        <select name="sessId" form="startSession">
                            <?php
                                sessionCheckD($mysqli);
                            ?>
                        </select>
                        <input type="submit" class="btn btn-primary btn-sm" name="startSession" value="Start Session">
                    </form>
                    
                    <hr><p>&copy; 2016 Product of REP</p>
                </div>
            </div>
        </div>

        <!--**********************************************************************-->
        <!-- For the case of wrong login -->
        <?php elseif ((login_check($mysqli) == true) && role_check() == 1)  :
            header('location: ./user.php');
        else :
            header('location: ./index.php');
        endif; ?>
    </body>
</html>
