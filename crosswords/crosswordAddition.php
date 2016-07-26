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
        <title>REP Crossword Addition page</title>
        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/jumbotron.css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="../css/js/bootstrap.min.js"></script>
        <script src="../css/js/ie10-viewport-bug-workaround.js"></script>
        <script>
            $(function () {
                var xWordInputList = $('#question2BI').find('tbody');
                var xWordInput = $('#questions');

                xWordInput.keyup(function () {
                    xWordInputList.empty();
                    var qnaInput = $(this).val();
                    var split1 = qnaInput.split(/@/);
                    var answers = [];
                    var questions = [];

                    for (i = 1; i < split1.length; i++) {
                        answers.push(split1[i].split(/^.+\(\d\s*\w+\)\s+/));
                        answers[i - 1].shift();
                        var test = (split1[i]).replace(' ' + answers[i - 1], "");
                        questions.push(test);
                    }
                    for (i = 0; i < questions.length; i++) {
                        xWordInputList.append('<tr><td>' + questions[i] + '</td><td>' + answers[i] + '</td></tr>');
                    }
                });
            });
        </script>
    </head>

    <body>
        <!-- This page can only be viewed by admins-->
        <?php if ((login_check($mysqli) == true) && role_check($mysqli) == 2) : ?>
        <nav role="navigation" class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-brand" href="../master.php" style="color:white;">REP Crossword Addition Page</a>
                </div>
                <!-- Collection of nav links, forms, and other content for toggling -->
                <div id="navbarCollapse" class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="./crosswords.php" style="color:white;">Sessions</a></li>
                        <li><a href="../reviews/reviews.php" style="color:white;">Performance</a></li>
                    <li><a href="../users/users.php" style="color:white;">Users</a></li>
                    </ul>
                    <div id="navbar" class="navbar-collapse collapse">
                        <div class="navbar-right navbar-form" style="color:white;">
                          <?php loginNavBarAction($mysqli); ?>
                        <a class="btn btn-success" href="../includes/logout.php" role="button">Log out</a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <div class="jumbotron">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">

                        <h3>Please input the questions and answers:</h3>
                        Format it as follows: <br>
                            @%XXX_XXX.%(N%WORDS)%ANSWER <br>
                        X : represents words/letters<br>
                            % : represents a space <br><br>
                            Example: <br>
                            @ Owners and other decision makers use this statement to evaluate how well a company has performed. _ (2 words) Income statement<br>
			                @ _ are profits accumulated within a company since the date of its incorporation that are available for dividend distribution. (2 words) Retained earnings<br>
                            <br>
                            Do note that the <b>@ placement</b> marks the start of question. <br>
                            And <b> (X words) </b> with brackets marks the end of question and start of answer. <br>
                            The questions and answers to be stored will be shown on this page, so do check before pressing submit. <br>
                        <br>
                        <form id="qBank" action="./includes/qnInput.php" method="post">
                            <div class="form-group">
                                <label for="questions">Input</label>
                                <textarea name="questions" form="qBank" class="form-control" rows="3" id="questions" autofocus></textarea>
                            </div>
                            <button type="submit" class="btn btn-default">Submit</button>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-striped" id="question2BI">
                            <thead>
                                <tr>
                                    <td class="col-md-5">Question</td>
                                    <td class="col-md-1">Answer</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Sample question _. (2 words)</td>
                                    <td>sample answer</td>
                                </tr>
                            </tbody>
                        </table>
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
        <?php elseif ((login_check($mysqli) == true) && role_check($mysqli) == 1)  :
            echo '<script>';
            echo 'window.location.href="../user.php"';
            echo '</script>';
            
        else :
            echo '<script>';
            echo 'window.location.href="../index.php"';
            echo '</script>';

        endif; ?>
    </body>
</html>
