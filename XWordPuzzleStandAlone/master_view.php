<?php
    include_once '../includes/db_connect.php';
    include_once '../includes/functions.php';
    
    sec_session_start();
    $id=$_GET["id"];
    echo '<script>var sessId = '.$id.'</script>';
?>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>REP Crossword Master Score page</title>
        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/jumbotron.css" rel="stylesheet">
        <script src="https://js.pusher.com/3.1/pusher.min.js"></script>
        <!-- Bootstrap core JavaScript-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="../css/js/bootstrap.min.js"></script>
        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="../css/js/ie10-viewport-bug-workaround.js"></script>
        <script>
            $(function () {
            console.log(sessId);
                //********* HERE IS THE LIVE SCORE UPDATE SYSTEM ********
            
                var pusher = new Pusher('bcaaf0a9f48c5ad4601b', {
                    cluster: 'ap1',
                    encrypted: true
                });
            
                var channelT = pusher.subscribe(sessId.toString());
                
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

              $('#endSession').click(function() {
                var answer = prompt("Please input 'end' to confirm.");
                if (answer == "end") {

                  $.ajax({
                  method: "POST",
                  url: "./includes/endSession.php",
                  data: {sessId: sessId},
                  success: function (data) {
                    dataParsed = JSON.parse(data);
                    alert(dataParsed);
                  }
                });

	          //window.location.href= "./includes/deleteCreatedSession.php";
	        }
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
        <!-- Only for admins and super users -->
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
                    <a class="navbar-brand" href="../index.php" style="color:white;">REP Crossword Master Score Page</a>
                </div>
                <!-- Collection of nav links, forms, and other content for toggling -->
                <div id="navbarCollapse" class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="../sessions/sessionView.php" style="color:white;">Sessions</a></li>
                        <li><a href="../reviews/reviews.php" style="color:white;">Performance</a></li>
                        <li><a href="../users/users.php" style="color:white;">Users</a></li>
                    </ul>
                    <div id="navbar" class="navbar-collapse collapse">
                        <div class="navbar-right navbar-form" style="color:white;">
                            <?php loginNavBarAction($mysqli);?>
                            <a class="btn btn-success" href="../includes/logout.php" role="button">Log out</a>
                        </div>
                    </div>
                </div>
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
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <a href="../sessions/sessionView.php">Click here to go back to the view session page</a><br>
                <a style="cursor:pointer" id="endSession">Click here end the session</a><br>
                <a href="answer_sheet.php">Click here to view answers</a>
            </div>
            <div class="container">
                <hr><p>&copy; 2016 Product of REP</p>
            </div>
        </div>

        <!--**********************************************************************-->
        <!-- For the case of wrong login -->
        <?php
            elseif ((login_check($mysqli) == true) && (role_check($mysqli) == 1 || role_check($mysqli) == 0)) :
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
