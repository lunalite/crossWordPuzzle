<?php 
    include_once '../includes/db_connect.php';
    include_once '../includes/functions.php';
    sec_session_start();
    if (checkIfSessionEnded($mysqli)) {
      echo 'session has ended';
    }
    echo '<script>var sessId = '.$_SESSION['sess_id'].'</script>';
?>
<html>
    <head>
        <meta charset="utf-8">
        <meta content="width=device-width" name="viewport">
        <meta content="yes" name="apple-mobile-web-app-capable">
        <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/navbar-fixed-side.css" rel="stylesheet" />
        <script src="https://js.pusher.com/3.1/pusher.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
        <link rel="stylesheet" href="themes/alertify.core.css" />
        <link rel="stylesheet" href="themes/alertify.default.css" id="toggleCSS" />
        <title>REP Crossword Main Game</title>
        <style>
            .qna{
            	background-color: #FFE7D8;
            }
            .alertify-log-custom {
                    background: blue;
                }
            @font-face {
            font-family: "Eileen Caps Black";
            src: url(../fonts/pdark.ttf) format("truetype");
            }
            .canvas{
                background-color: black;
            }
            
        </style>
        <script>
          $(function () {

            //********* HERE IS THE LIVE SCORE UPDATE SYSTEM ********
            //Pusher.logToConsole = true;
            console.log(sessId);
            var pusher = new Pusher('bcaaf0a9f48c5ad4601b', {
              cluster: 'ap1',
              encrypted: true
            });

            var channelT = pusher.subscribe(sessId.toString());
            
            channelT.bind('correctAnswer', function (data) {
              console.log("Updating....");
              updatedScore = data.updatedScore;
              userName = data.userName;
              //document.getElementById("score").innerHTML = "Score: " + updatedScore;

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
    </head>

    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-3 col-md-2 col-lg-2 col-sm-push-9 col-md-push-10 col-lg-push-10">
                    <nav class="navbar navbar-default navbar-fixed-side">
                        <div class="container">
                            <div style="display: inline">
                                <form action="../user.php">
                                <span id="title" style="margin:5px">HELLO</span>
                                    <input type="submit" class="btn btn-primary btn-sm" value="Leave puzzle">
                                </form>
                            </div>

                            <div class="collapse navbar-collapse">
                                <h3>Scores</h3>
                            </div>
                            <table id="scoreList" class="table">
                                <thead>
                                <tr>
                                    <td>Rank</td>
                                    <td>User</td>
                                    <td>Score</td>
                                    <td>Time(secs)</td>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        include_once '../includes/db_connect.php';
                                        include_once '../includes/functions.php';
                                        include_once 'includes/printScoreRank.php';
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </nav>
                </div>
                <div class="col-sm-9 col-md-10 col-lg-10 col-sm-pull-3 col-md-pull-0 col-lg-pull-2">
                <div style="position: fixed;width: 82% ;" class = "qna">
			<p  style="height : 5%;" id="currentQuestion">Your question will appear here when you select one of the tiles to answer</p>
			<input  style="width: 50%;" id="userAnswer" class="userAnswer"  onkeydown = "if (event.keyCode == 13)
	                    userAnswers()"></input>
			<input type="submit" class="btn btn-primary btn-sm" name="Answer!" value="Answer!" onclick="userAnswers()">
		</div>
                    <script src="lib/alertify.min.js"></script>
                    <div class="canvas" style="margin:4% 0 0;"><canvas id="myCanvas">
        Your browser does not support the HTML5 canvas tag.</canvas></div>
                  <script src="js/main_script.js?<?php echo time(); ?>"></script>
                </div>
            </div>
        </div>
    </body>
</html>