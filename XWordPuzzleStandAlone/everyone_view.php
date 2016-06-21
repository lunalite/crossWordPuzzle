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
        <title>REP Xword Crossword User Score page</title>
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
            
                console.log('test');
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
                    <a class="navbar-brand" href="../index.php" style="color:white;">REP Crossword Score Page</a>
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

                            echo htmlentities($_SESSION['username'])?> 
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
        </div> <!-- /container -->
        <!--*********************************************-->
        <!-- For the case of not being a super user -->
        <?php else : ?>
        <p>
            <span class="error">You are not authorized to access this page.</span>
               Please <a href="../index.php">login</a> to a superuser.
        </p>
        <?php endif; ?>
    </body>
</html>

    <!--
        <script>
            var sessId = "<?php// echo $id ?>";
            var user_list = document.getElementById("userList");
            var score_list = document.getElementById("scoreList");
            var userList=[];
            var scoreList=[];
            function stateChange() {
                setTimeout(function () {
                retrieveScores();
                }, 5000);
            }
            function retrieveScores(){
                while (user_list.firstChild) {
                    user_list.removeChild(user_list.firstChild);
                }
                while (score_list.firstChild) {
                    score_list.removeChild(score_list.firstChild);
                }
                    var url = "../phpretrieval/includes/getAllScoresFromId.php?id="+sessId;
                    jQuery.getJSON(url, function (data) {
                            console.log("Received object "+data);
                            for (x=0;x<data.length;x++){
                                var userId=data[x][1];
                                scoreList.push(data[x][0]);
                                var entry = document.createElement('li');
                                entry.appendChild(document.createTextNode(data[x][0]));
                                score_list.appendChild(entry);
                                console.log("user id "+userId);
                                var url = "../phpretrieval/includes/getNameFromUserId.php?id="+userId;
                                jQuery.getJSON(url, function (data2) {
                                    console.log("received "+data2);
                                    userList.push(data2);
                                    var entry = document.createElement('li');
                                    entry.appendChild(document.createTextNode(data2));
                                    user_list.appendChild(entry);
                                });
                            }
                });
                stateChange();
            }
    
            retrieveScores();
            console.log("in script...");
                     </script>-->