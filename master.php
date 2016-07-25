<?php
    include_once 'includes/db_connect.php';
    include_once 'includes/functions.php';
    sec_session_start();
?>

<!DOCTYPE html>
<html>
    <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>REP Crossword Master Page</title>
      <link href="css/bootstrap.min.css" rel="stylesheet">
      <link href="css/jumbotron.css" rel="stylesheet">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
      <script src="css/js/bootstrap.min.js"></script>
      <script src="css/js/ie10-viewport-bug-workaround.js"></script>
      <script>
        $(function () {
          var availCrosswordResult = $('#availCrosswordResult');

          $('#crosswordOptions').find("td").click(function () {
            console.log(this);
          });

          availCrosswordResult.find("tr").click(function () {
            var selection = $(this);
            var storedSelection = [];
            var dangerSelections = {};
            var selectionId = selection.attr("id");
            var rowSelection = $("[id=" + selectionId + "]");

            if (sessionStorage.crosswordSelection) {
              storedSelection = JSON.parse(sessionStorage.getItem("crosswordSelection"));
              var index = storedSelection.indexOf(String(selectionId));

              if (index > -1) {
                storedSelection.splice(index, 1);
                var found = false;
                for (var i = 0; i < storedSelection.length; i++) {
                  if (storedSelection[i].id == selectionId) {
                    found = true;
                    rowSelection.addClass("danger");
                    storedSelection.splice(i);
                    break;
                  }
                }
                rowSelection.removeClass("info");
              } else {
                dangerCheck(rowSelection, storedSelection, selectionId);
                storedSelection.push(selection.attr("id"));
                rowSelection.addClass("info");
              }
            } else {
              dangerCheck(rowSelection, storedSelection, selectionId);
              storedSelection.push(selection.attr("id"));
              rowSelection.addClass("info");
            }
            sessionStorage.setItem("crosswordSelection", JSON.stringify(storedSelection));
            console.log(sessionStorage.getItem("crosswordSelection"));

          });

          function dangerCheck(rowSelection, storedSelection, selectionId) {
            if (rowSelection.hasClass("danger")) {
              storedSelection.push({
                id: selectionId
              });
              rowSelection.removeClass("danger");
            }
          }

          window.onbeforeunload = function () {
            sessionStorage.removeItem("crosswordSelection");
            return '';
          };

        });
      </script>
    </head>
    <body>
        <!-- Only for admins and super users -->
        <?php if ((login_check($mysqli) == true) && role_check($mysqli) != 0) : ?>
        <nav role="navigation" class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-brand" href="../master.php" style="color:white;">REP Crossword Master Page</a>
                </div>
                <!-- Collection of nav links, forms, and other content for toggling -->
                <div id="navbarCollapse" class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="./crosswords/crosswords.php" style="color:white;">Sessions</a></li>
                        <li><a href="./reviews/reviews.php" style="color:white;">Performance</a></li>
                        <li><a href="./users/users.php" style="color:white;">Users</a></li>
                    </ul>
                    <div id="navbar" class="navbar-collapse collapse">
                        <div class="navbar-right navbar-form" style="color:white;">
                            <?php loginNavBarAction($mysqli);?>
                            <a class="btn btn-success" href="includes/logout.php" role="button">Log out</a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <div class="jumbotron">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-md-8 col-md-offset-2">
                        <table class="table table-bordered" id="crosswordOptions">
                          <caption class="text-center">My Crosswords</caption>
                            <tr style="cursor: pointer;">
                              <td>Add Crosswords</td>
                              <td>View/Edit/Delete</td>
                              <td>Create Sessions</td>
                              <td>Share/Copy</td>
                            </tr>
                        </table>

                        <table id="crosswordList" class="table table-striped table-hover">
                          <thead>
                            <tr>
                              <th>Crossword ID</th>
                              <th>Crossword Name</th>
                              <th>Crossword Description</th>
                              <th>Time Created</th>
                            </tr>
                          </thead>
                          <tbody style="cursor: pointer;" id="availCrosswordResult">
                            <?php listOfCrosswordAvail($mysqli); ?>
                          </tbody>
                        </table>

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
