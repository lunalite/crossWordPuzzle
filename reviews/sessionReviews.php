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
        <title>REP Crossword Performance</title>
        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/jumbotron.css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="../css/js/bootstrap.min.js"></script>
        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="../css/js/ie10-viewport-bug-workaround.js"></script>
        <script>
        $(function() {
          $('#sessionRevs').find("tr").click(function () {
            var url = "sessionReviews.php?sessionId=" + this.id;
            window.location.href = url;
          });
        });

        </script>
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
                <a class="navbar-brand" href="../index.php" style="color:white;">REP Crossword Performance</a>
            </div>
            <!-- Collection of nav links, forms, and other content for toggling -->
            <div id="navbarCollapse" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li><a href="../sessions/sessionView.php" style="color:white;">Sessions</a></li>
                    <li><a href="./reviews.php" style="color:white;">Performance</a></li>
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
                    <div class="col-xs-6 col-md-8 col-md-offset-2">
                        <h3> Review by sessions </h3>

<table class="table table-bordered">
<thead>
<tr>
<th>Session ID</th>
<th>Time Started</th>
<th>Time Ended</th>
<th>Users</th>
</tr>
</thead>
<tbody id = "sessionRevs" style="cursor:pointer;">

<?php 

$mainQuery = "SELECT distinct sessId FROM ".$GLOBALS['sessionStart'];
    $result = $mysqli->query($mainQuery);
    while ($row = mysqli_fetch_row($result)) {
        $sessionId = $row[0];

        $timeQuery = "SELECT sessionStartTime, sessionEndTime FROM ".$GLOBALS['sessionTimeSeries']." WHERE sessId = ".$sessionId;
        $timeQueryResult = $mysqli->query($timeQuery);
        $timeArr = $timeQueryResult->fetch_row();
        $timeStart = $timeArr[0];
        $timeEnd = $timeArr[1];

// For spanning of rows
        $userNumberQuery = "SELECT COUNT(sessId) FROM ".$GLOBALS['sessionStart']." WHERE sessId = ".$sessionId;
        $userNumberResult = $mysqli->query($userNumberQuery);
        $userNumber = mysqli_fetch_row($userNumberResult)[0];
 
        echo '<tr id="'. $sessionId .'">
                <td rowspan="'.$userNumber.'">'.$sessionId.'</td>
                <td rowspan="'.$userNumber.'">'.date("Y-m-d H:i:s",$timeStart).'</td>
                <td rowspan="'.$userNumber.'">'.date("Y-m-d H:i:s",$timeEnd).'</td>';

        $userQuery = "SELECT username FROM ".$GLOBALS['members']." WHERE id in (SELECT userId FROM ".$GLOBALS['sessionStart']." WHERE sessId = 
                     ".$sessionId.")";
        $userResult = $mysqli->query($userQuery);
            while ($userRow = mysqli_fetch_row($userResult)) {
                echo '<td>'.$userRow[0].'</td></tr><tr>';
            }
        echo '</tr>';
    }

?>
</tbody>
</table>
reviews by sessions<br>
** Add number of users getting qn 1 right, qn 2 right, etc.<br>
** Add time taken for users to finish puzzle<br>

<br>
reviews by users<br>
** Add scores of individual users<br>
** Add the questions individual users get correct<br>

                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-xs-6 col-md-8 col-md-offset-2">
                    

                    <hr><p>&copy; 2016 Product of REP</p>
                </div>
            </div>
        </div>

        <!--**********************************************************************-->
        <!-- For the case of wrong login -->
        <?php
            elseif ((login_check($mysqli) == true) && (role_check($mysqli) == 0)) :
                       echo '<script>';
                       echo 'window.location.href="../user.php";';
                       echo '</script>'; 
                   else : 
                       echo '<script>';
                       echo 'window.location.href="../index.php";';
                       echo '</script>';
                   endif;
        ?>
    </body>
</html>
