<html>
    <head>
        <meta charset="utf-8">
        <meta content="width=device-width" name="viewport">
        <meta content="yes" name="apple-mobile-web-app-capable">
        <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
        <link href="../css/bootstrap.css" rel="stylesheet">
        <link href="../css/navbar-fixed-side.css" rel="stylesheet" />
        <script src="https://js.pusher.com/3.1/pusher.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
        <link rel="stylesheet" href="themes/alertify.core.css" />
        <link rel="stylesheet" href="themes/alertify.default.css" id="toggleCSS" />
        <style>
            .alertify-log-custom {
                    background: blue;
                }
            @font-face {
            font-family: "Eileen Caps Black";
            src: url(../fonts/pdark.ttf) format("truetype");
            }
            
            }
        </style>
        <script>
            $(function () {
            
                console.log('test');
                //********* HERE IS THE LIVE SCORE UPDATE SYSTEM ********
                Pusher.logToConsole = true;
            
                var pusher = new Pusher('bcaaf0a9f48c5ad4601b', {
                    cluster: 'ap1',
                    encrypted: true
                });
            
                var channelT = pusher.subscribe('channel_1');
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
                <div class="col-sm-3 col-lg-2 col-sm-push-9 col-lg-push-10">
                    <nav class="navbar navbar-default navbar-fixed-side">
                        <div class="container">

                            <div class="navbar-header">
                                <a id="title" class="navbar-brand">HELLO</a>
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
                <div class="col-sm-9 col-lg-10 col-sm-pull-3 col-lg-pull-2">

                    <!-- your page content -->
                    <script src="lib/alertify.min.js"></script>
                    <canvas id="myCanvas">
        Your browser does not support the HTML5 canvas tag.</canvas>
                </div>
            </div>
        </div>
        <script type="text/javascript" src="js/main_script.js"></script>
    </body>
</html>