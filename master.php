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
          var crosswordOptions = $('#crosswordOptions');
          var dangerSelections = [];
          var storedSelections = [];

          crosswordOptions.find("td").hover(
          function() {
            $(this).addClass("active");
          }, 
          function() {
            $(this).removeClass("active");
          });

          crosswordOptions.find("td").click(function () {
            if(this.id === "addCrosswords") {
              window.location.href="./crosswords/crosswordAddition.php";

            } else if (this.id === "CRUD") {
              if (storedSelections.length == 0) {
                alert('Please select a crossword!');
              } else {
                var url = "./crosswords/crosswordView.php?crosswordId="+storedSelections[0];
                window.location.href=url;
              }

            } else if (this.id === "sessionCreate") {
              if (storedSelections.length == 0) {
                alert('Please select a crossword!');
              } else {
                $('#groupOptions').slideToggle("fast")
              }

            } else if (this.id === "copyShare") {
              console.log('ccce');
            }
          });                

          availCrosswordResult.find("tr").click(function () {
            var selection = $(this);
            var selectionId = selection.attr("id");
            var rowSelection = $("[id=" + selectionId + "]");

            if (storedSelections.length == 0) {
              storedSelections.push(selection.attr("id"));
              rowSelection.addClass("info");
              rowSelection.prop("title", "Selected crossword.");
              dangerRemoval(rowSelection, selectionId);

            } else {
              var index = storedSelections.indexOf(String(selectionId));
              if (index > -1) {
                storedSelections.splice(index, 1);
                rowSelection.removeClass("info");
                rowSelection.prop("title", "Crossword is fully assigned.");
                dangerAdd(selectionId);

              } else {
                var removeSelection = storedSelections.pop();
                $("[id=" + removeSelection + "]").removeClass("info");
                dangerAdd(removeSelection);
                dangerRemoval(rowSelection, selectionId);
                storedSelections.push(selection.attr("id"));
                rowSelection.addClass("info");
                rowSelection.prop("title", "Selected crossword.");
              }
            }
          });

          function dangerRemoval(rowSelection, selectionId) {
            if (rowSelection.hasClass("danger")) {
              dangerSelections.push(selectionId);
              rowSelection.removeClass("danger");
            }
          }
          
          function dangerAdd(removeSelection) {
            var dangerKey = dangerSelections[0];
              if(dangerKey == removeSelection) {
                $("[id=" + removeSelection + "]").addClass("danger");
                dangerSelections.pop();
                $("[id=" + removeSelection + "]").prop("title", "Crossword is not fully assigned.");
              }
            }

          $('#sessionForm').submit(function() {
            $('input#crosswordOption').val(storedSelections [0]);
            return true;
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
                    <a class="navbar-brand" href="../master.php" style="color:white;">REP Crossword Master Page</a>
                </div>
                <!-- Collection of nav links, forms, and other content for toggling -->
                <div id="navbarCollapse" class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="./sessions/sessionView.php" style="color:white;">Sessions</a></li>
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
                        <table class="table table-bordered table-responsive" id="crosswordOptions">
                          <caption style="font-size:30px;" class="text-center">My Crosswords</caption>
                            <tr style="cursor: pointer;">
                              <td id="addCrosswords">Add Crosswords</td>
                              <td id="CRUD">View/Edit/Delete</td>
                              <td id="sessionCreate">Create Sessions</td>
                              <td id="copyShare">Share/Copy</td>
                            </tr>
                        </table>
                        <div id="groupOptions" style="display:none">
                        <form id="sessionForm" action="./sessions/includes/sessionCreate.php" method="POST">
                            <select name="groupOptions" form="sessionForm" id="sessionOptions">
                              <?php groupCheckD($mysqli); ?>
                            </select>
                            <input type="hidden" name="crosswordOption" id="crosswordOption">
                            <input type="submit" class="btn btn-primary btn-sm" value="Create Session" onClick="createSession()">
                        </form>
                        </div>


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
