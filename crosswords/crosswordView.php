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
        <title>REP Xword user page</title>
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
                $('#crosswordList').find("tr").click(function () {
                    sessionStorage.setItem("crosswordViewId", this.id);
                    var url = "crosswordView.php?crosswordId=" + this.id;
                    window.location.href = url;
                });
	    });
	</script>
	<?php if (role_check() == 2) : ?>
	<script>
	    $(function() {
                $('#crosswordIList').find("tr").click(function (event) {
                    var qid = this.getAttribute('qid');
                    if (qid == 'form') {
                        return;
                    } else {
                        var cvid = sessionStorage.getItem("crosswordViewId");
                        var url = "crosswordView.php?crosswordId=" + cvid + "&questionId=" + qid;
                        window.location.href = url;
                    }
                });
            });
        </script>
	<?php elseif ((role_check() == 1) && (isset($_GET['questionId'])) ):  ?>
	<script>
	    window.location = "crosswordView.php?";
	</script>

	<?php  endif; ?>
    </head>
    <body>
        <!-- This page can be viewed by both super users and admin -->
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
                    <a class="navbar-brand" href="../index.php" style="color:white;">REP Crossword View Page</a>
                </div>
                <!-- Collection of nav links, forms, and other content for toggling -->
                <div id="navbarCollapse" class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="./crosswords.php" style="color:white;">Crosswords</a></li>
                        <li><a href="../reviews/reviews.php" style="color:white;">Reviews</a></li>
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
                    <div class="col-xs-12 col-md-10 col-md-offset-1">

                        <!-- Show crossword -->
                        <?php 
                            if (!isset($_GET['crosswordId'])){
                                echo "Please click the respective crosswords to view details of it below.";
                            } else {
                                echo "Please click on the respective questions to edit the questions and answers.";
                                $crosswordId = $_GET['crosswordId'];
                                echo "<h3>Crossword ".$_GET['crosswordId']."</h3>";
                                if ($_GET['success']) {
                                echo '<p class="text-danger">
                                Warning: Changing the answers might cause instability in crossword. Please re-assign it ASAP.</p>';
                                }

                                echo "
                                <table id='crosswordIList' class = 'table table-hover'>
                                    <thead> 
                                        <tr>
                                            <td>Question ID</td>
                                            <td width='60%'>Question</td>
                                            <td>Answer</td>
                                            <td>Tile Code</td>
                                        </tr>
                                    </thead>
                                    <tbody style='cursor: pointer;'>";
                                        crosswordList($mysqli, $crosswordId, $_GET['questionId']);
                                    echo "
                                    </tbody>
                                </table>
                                ";

                                if (role_check() == 2) {

                                    // Assign Crossword button echo
                                    echo "
                                        <form style='display: inline;' id='crosswordQAssign' action='../XWordPuzzleStandAlone/master_template.php' method='get'>
                                        <input type='hidden' name='id' form ='crosswordQAssign' id='id' value='".$_GET['crosswordId']."'/>
                                        <input type='submit' class='btn btn-danger btn-sm' value='Assign Crossword'>
                                        </form> &nbsp;&nbsp;";

                                    // Delete Crossword button echo
                                    echo "
                                        <form onsubmit=\"return confirm('Are you sure you want to delete this crossword?');\" style='display: inline;' id='crosswordQDelete' action='./includes/crosswordQDelete.php' method='POST'>
                                        <input type='hidden' name='crosswordId' form ='crosswordQDelete' id='crosswordId' value='".$_GET['crosswordId']."'/>
                                        <input type='submit' class='btn btn-danger btn-sm' value='Delete Crossword'>
                                        </form> &nbsp;&nbsp;";   
                                }

                                     // View Crossword button echo
                                    echo "
                                        <form style='display: inline;' id='crosswordQView' action='viewCrosswordAllocation.php' method='get' target='_blank'>
                                        <input type='hidden' name='id' form ='crosswordQView' id='id' value='".$_GET['crosswordId']."'/>
                                        <input type='submit' class='btn btn-primary btn-sm' value='View Crossword'>
                                        </form>
                                        <br>";
                            }
                        ?>
                        <hr>
                        <h3>All created crosswords</h3>
                        <table id="crosswordList" class="table table-striped table-hover">    
                            <thead>
                            <tr>
                                <td>Crossword ID</td>
                                <td>Puzzle Name</td>
                                <td>Crossword Description</td>
                                <td>Time Added</td>
                            </tr>
                            </thead>
                            <tbody style="cursor: pointer;">
                                <?php
                                    crosswordCheck($mysqli);
                                ?>
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
        <?php elseif ((login_check($mysqli) == true) && role_check() == 1)  :
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
