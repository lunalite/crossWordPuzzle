<?php
    include_once '../includes/db_connect.php';
    include_once '../includes/functions.php';
    
    sec_session_start();

    $user_id = $_GET['userId'];
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>REP Crossword Review Page</title>
        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/jumbotron.css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="../css/js/bootstrap.min.js"></script>
        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="../css/js/ie10-viewport-bug-workaround.js"></script>
        
        <script>
            $(function () {
                $("#userList").on("click", "tbody tr", function (e) {
                    if (sessionStorage.getItem("sessId") === this.id) {
                        var url = "studentReviews.php?";
                        window.location.href = url;
                        sessionStorage.removeItem("sessId");
                    } else {
                        sessionStorage.setItem("sessId", this.id);
                        var url = "./studentReviews.php?userId=" + this.id;
                        window.location.href = url;
                    }
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
                <a class="navbar-brand" href="../index.php" style="color:white;">REP Crossword Review Page</a>
            </div>
            <!-- Collection of nav links, forms, and other content for toggling -->
            <div id="navbarCollapse" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li><a href="../crosswords/crosswords.php" style="color:white;">Crosswords</a></li>
                    <li><a href="./reviews.php" style="color:white;">Reviews</a></li>
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
                        
                        <!-- Users -->
                        <h3>All users</h3>
                        <table id="userList" class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>User ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>User type</th>
                                <th>Group</th>
                            </tr>
                            </thead>
                            <tbody style="cursor: pointer;">
                                <?php
                                    if (!isset($user_id)) {
                                      userCheck($mysqli, "all");
                                    } else {
                                      userCheck($mysqli, "user");
                                    }
                                ?>
                            </tbody>
                        </table>

<?php if(isset($user_id)) : ?>
                        <!-- Users Score page -->
                        <h3>All recorded sessions</h3>
                        <table id="userList" class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Session ID</th>
                                <th>Time taken</th>
                                <th>question</th>
                                <th>Answered</th>
                                <th>Attempts</th>
                            </tr>
                            </thead>
                            <tbody style="cursor: pointer;">
                                <?php
                                      historyCheck($mysqli);
                                ?>
                            </tbody>
                        </table>
<?php endif; ?>
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
