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
        <title>REP Xword Group Creation page</title>
        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/jumbotron.css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="../css/js/bootstrap.min.js"></script>
        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="../css/js/ie10-viewport-bug-workaround.js"></script>
        <script>
            $(function () {
                $('#userList').find("tr").click(function () {
                    var url = "./grantUser.php?userId=" + this.id;
                    window.location.href = url;
                });

var groupNameJ = $('#groupName');
var formControlFeedback = $('.form-control-feedback');
var hasFeedback = $('.has-feedback');

groupNameJ.keyup(function() {
var groupNameV = groupNameJ.val();
    $.ajax({
      method: "POST",
      url: "includes/groupNameVerification.php",
      data: { groupNameData:groupNameV },
      success: function( data ) {
        console.log( "Data Saved: " + data );
        setTimeout(formValidation(data), 30000);
      }
    });
});

function formValidation(data) {

console.log($.type(data));
    removeGlyphClass();

  if (data.indexOf("true") >= 0 ) {
    formControlFeedback.addClass('glyphicon-remove');
    hasFeedback.addClass('has-error');
  } else if (data.indexOf("false") >= 0) {
    formControlFeedback.addClass('glyphicon-ok');
    hasFeedback.addClass('has-success');
  } 
}

function removeGlyphClass() {
    formControlFeedback.removeClass('glyphicon-ok glyphicon-remove');
    hasFeedback.removeClass('has-success has-error');
}
            });
            
        </script>
    </head>
    <body>
        <?php if ((login_check($mysqli) == true) && role_check($mysqli) == 2) : ?> 
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                  <a class="navbar-brand" href="../master.php" style="color:white;">REP Crossword Group Creation Page</a>
                </div>
                <div id="navbarCollapse" class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="../crosswords/crosswords.php" style="color:white;">Crosswords</a></li>
                        <li><a href="../reviews/reviews.php" style="color:white;">Reviews</a></li>
                        <li><a href="./users.php" style="color:white;">Users</a></li>
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

                        <?php loginNavBarAction($mysqli); ?>

                        <a class="btn btn-success" href="../includes/logout.php" role="button">Log out</a>
                        </div>

                    </div><!--/.navbar-collapse -->
                </div>
            </div>
        </nav>

<!-- Only for admins -->

        <div class="jumbotron">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-md-8 col-md-offset-2">
                        <form id="groupCreation" action="./includes/groupCreation.php" method="post">
                        <div class="col-xs-12 col-md-6">
                            <div class="form-group has-feedback">
                                <label for="groupName">Group Name</label>
                                <input type="text" name="groupName" form="groupCreation" class="form-control" id="groupName" style="display: inline;" autofocus></input>
                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                            </div>
                            <button type="submit" class="btn btn-default">Submit</button>
                        </div>
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
