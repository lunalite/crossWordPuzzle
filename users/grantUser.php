<?php
    include_once '../includes/db_connect.php';
    include_once '../includes/functions.php';
    
    sec_session_start();
    
    $userID = $_GET['userId'];
    if (!is_numeric($userID)) {
        echo "Please enter a numeric ID.";
        echo "<a href='javascript:history.go(-1)'>Click here to go back.</a>";
        exit;
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>REP Xword Grant page</title>
        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/jumbotron.css" rel="stylesheet">
        <!-- Bootstrap core JavaScript-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="../css/js/bootstrap.min.js"></script>
        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="../css/js/ie10-viewport-bug-workaround.js"></script>
    </head>
    <body>

<!-- Only for admins-->
<?php if ((login_check($mysqli) == true) && role_check($mysqli) == 2) : ?>
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-brand" href="../master.php" style="color:white;">REP Crossword Grant User Page</a>
                </div>
                <div id="navbarCollapse" class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="../crosswords/crosswords.php" style="color:white;">Crosswords</a></li>
                        <li><a href="../reviews/reviews.php" style="color:white;">Reviews</a></li>
                        <li><a href="./users.php" style="color:white;">Users</a></li>
                    </ul>
                    <div id="navbar" class="navbar-collapse collapse">
                        <div class="navbar-right navbar-form" style="color:white;">

                        <?php loginNavBarAction($mysqli); ?>

                        <a class="btn btn-success" href="includes/logout.php" role="button">Log out</a>
                        </div>

                    </div><!--/.navbar-collapse -->
                </div>
            </div>

        </nav>

        <div class="jumbotron">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-md-8 col-md-offset-2">

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
                            <tbody>
                                <?php
                                    userCheck($mysqli, "user");
                                ?>
                            </tbody>
                        </table>
                        <br>

<?php  if ($_GET['mgmt'] !== "true") : ?>
<form style='display: inline;' action="./passwordChange.php" method="POST">
    <input type="hidden" name="userId" value="<?php echo $_GET['userId'];?>">
    <input type="submit" class="btn btn-danger btn-sm" value="Change password">
</form>  
<form style='display: inline;' action="./grantUser.php?userId=2" method="GET">
    <input type="hidden" name="userId" value="<?php echo $_GET['userId'];?>">
    <input type="hidden" name="mgmt" value="true">
    <input type="submit" class="btn btn-danger btn-sm" value="Manage group">
</form>
<?php else : ?>
<form style='display: inline;' action="includes/groupUserAdd.php" method="POST" id="groupAdd">
    <select name="groupId" form="groupAdd">
       <?php
        groupCheckD($mysqli);
       ?>
    </select>
    <input type="hidden" name="userId" value="<?php echo $_GET['userId'];?>">
    <input type="submit" class="btn btn-danger btn-sm" value="Add to group">
</form> 
<?php endif; ?>
                        <br>
                        <h3>Change permissions:</h3>
                        <form action="../includes/changePermissions.php" id="changePermissions" method="POST">
                            <select name="permId" form="changePermissions">
                                <option value='{"id": <?php echo $userID?> ,"permissions":0}'>Normal user</option>
                                <option value='{"id": <?php echo $userID?> ,"permissions":1}'>Super user</option>
                                <option value='{"id": <?php echo $userID?> ,"permissions":2}'>Admin</option>
                            </select>
                            <input type="submit" class="btn btn-danger btn-sm" name="changePermissions" value="Change Permission">
                        </form>

                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-8 col-md-offset-2">
                <hr><p>&copy; 2016 Product of REP</p>
            </div>
        </div>

        <!--**********************************************************************-->
        <!-- For the case of wrong login -->
        <?php
            elseif ((login_check($mysqli) == true) && role_check($mysqli) == 0)  :
                       echo '<script>';
                       echo 'window.location.href="../user.php"';
                       echo '</script>';
            elseif ((login_check($mysqli) == true) && role_check($mysqli) == 1)  :
                       echo '<script>';
                       echo 'window.location.href="../master.php"';
                       echo '</script>';
                   else :
                       echo '<script>';
                       echo 'window.location.href="../index.php"';
                       echo '</script>';
            
                   endif;
        ?>
    </body>
</html>
