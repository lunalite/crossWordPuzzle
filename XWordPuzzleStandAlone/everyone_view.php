<?php
    include_once '../includes/db_connect.php';
    include_once '../includes/functions.php';
    
    sec_session_start();
    
    $id=$_GET["id"];

?>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>REP Crossword User Score page</title>
        <link href="../css/bootstrap.css" rel="stylesheet">
        <link href="../css/jumbotron.css" rel="stylesheet">
        <script src="https://js.pusher.com/3.1/pusher.min.js"></script>
        <!-- Bootstrap core JavaScript-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="../css/js/bootstrap.min.js"></script>
        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="../css/js/ie10-viewport-bug-workaround.js"></script>
        <script>
            $(function () {
                //********* HERE IS THE LIVE SCORE UPDATE SYSTEM ********
            
                var pusher = new Pusher('bcaaf0a9f48c5ad4601b', {
                    cluster: 'ap1',
                    encrypted: true
                });
            
                var channelT = pusher.subscribe('channel_1');
                channelT.bind('correctAnswer', function (data) {
            
                    updatedScore = data.updatedScore;
                    userName = data.userName;
            
                    $.ajax({
                        type: "POST",
                        datatype: 'json',
                        url: "./includes/printScoreRank.php",
                        cache: false,
                        success: function (data) {
                            $("#scoreList").find("tbody").html(data);
                            $('#scoreList').find('#' + userName).css("background-color", "FFF000");
                        }
                    });
                });
            });
        </script>
        <style>
            h1{
            }
            .ulist,.slist{
                float: left;
            }
        </style>
    </head>
    <body>

        <?php if ((login_check($mysqli) == true)) : ?>
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="../index.php" style="color:white;">REP Crossword User Score Page</a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <div class="navbar-right navbar-form" style="color:white;">
                        <?php loginNavBarAction($mysqli);?>
                        <a class="btn btn-success" href="../includes/logout.php" role="button">Log out</a>
                    </div>
                </div><!--/.navbar-collapse -->
            </div>
        </nav>

        <div class="jumbotron">
            <div class="container">
                <div class="row">
                    <div class="col-xs-6 table-responsive">
                        <h4>Score List</h4>
                        <table id="scoreList" class="table table-striped">
                            <thead>
                            <tr>
                                <td>Rank</td>
                                <td>User</td>
                                <td>Score</td>
                                <td>Time</td>
                            </tr>
                        </thead>
                            <tbody>
                                <?php
                                    
                                    $_SESSION['sess_id']=$id;
                                    include_once './includes/printScoreRank.php'; 
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-xs-6 table-responsive">

                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <a href="../index.php">Click here to go back</a>
            </div>
            <hr><p>&copy; 2016 Product of REP</p>
        </div>

        <!--*********************************************-->
        <?php endif; ?>
    </body>
</html>
