<?php
    include_once './includes/db_connect.php';
    include_once './includes/functions.php';
    
    sec_session_start();
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
        <script src="css/js/bootstrap.min.js"></script>
        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="css/js/ie10-viewport-bug-workaround.js"></script>
        <script>
          $(function () {
            $('#leaveSession').click(function () {
              sessionStorage.removeItem('startTime');
              sessionStorage.removeItem('answered');
              sessionStorage.removeItem('attempts');
              sessionStorage.removeItem('noOfQuestions');
            });
          });
      </script>
    </head>

    <body>
        <?php if ((login_check($mysqli) == true)) : ?>
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-brand" href="./index.php" style="color:white;">REP Crossword User Page</a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <div class="navbar-right navbar-form" style="color:white;">

                        <?php loginNavBarAction($mysqli); ?>
    
                        <a class="btn btn-success" href="includes/logout.php" role="button">Log out</a>
                    </div>

                </div><!--/.navbar-collapse -->
            </div>
        </nav>

        <div class="jumbotron">
            <div class="container">
                <div class="row">
                    <div class="col-xs-6">
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

                        <?php if (!userInSession($mysqli)) : ?>

                        <form action="includes/sessionJoin.php" id="sessionJoin" method="post">
                            <div class="form-group">
                            <div class="row"><div class="col-xs-12 col-md-8 col-md-offset-2">
                            <select class="form-control" name="sessionJoin" form="sessionJoin">
                                <?php
                                    sessionCheckD($mysqli);
                                ?>
                            </select>
                                </div></div></div>
                            <input type="submit" class="btn btn-primary btn-sm" name="joinSession" value="Join Session">
                        </form>

                        <?php elseif(gateCheck($mysqli)) : ?>

                        <form action="./XWordPuzzleStandAlone/main_xword.php">
                            <input type="submit" class="btn btn-primary btn-sm" value="Resume puzzle">
                        </form>

                        <?php else : ?>
                        You have joined a session. Please wait for the gate to open...  <br>
                        Click <a href="./includes/leaveSession.php" id="leaveSession">here</a> to leave the session.
                        <?php endif; ?>

                        <script>
                        //********* HERE IS THE LIVE GATE PUSH SYSTEM ********
                        var pusher = new Pusher('bcaaf0a9f48c5ad4601b', {
                            cluster: 'ap1',
                            encrypted: true
                        });
            
                        var channelT = pusher.subscribe('channel_1');
                        channelT.bind('gateOpen', function (data) {
                            if (data.gateStatus == 'open') {
                                alert('Gate is ' + data.gateStatus);
                                window.location.href="XWordPuzzleStandAlone/main_xword.php";
                            }
                        });
                        </script>

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
        
        <?php else : 
            echo '<script>';
            echo 'window.location.href="./index.php"';
            echo '</script>';
            endif;
        ?>
    </body>
</html>
