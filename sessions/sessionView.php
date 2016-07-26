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
    <title>REP Crossword Session View page</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/jumbotron.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="../css/js/bootstrap.min.js"></script>
    <script src="../css/js/ie10-viewport-bug-workaround.js"></script>
    <script>
      $(function () {
        var sessionOptions = $('#sessionOptions');
        var availSession = $('#availSessionList');
        var storedSelections = [];

        sessionOptions.find('td').hover(
          function () {
            $(this).addClass("active");
          },
          function () {
            $(this).removeClass("active");
          });

        sessionOptions.find("td").click(function () {
          if (storedSelections.length == 0) {
            console.log("No selections!");
            alert('Please select a session!');
          } else {
            if (this.id === "startSession") {
              $.ajax({
                method: "POST",
                url: "./includes/sessionStart.php",
                data: { sessId: storedSelections[0], online: $('[id="' + storedSelections[0] + '"] td:nth-child(6)').html() },
                success: function (data) {
                  dataParsed = JSON.parse(data);
                  if (dataParsed == "No teams joined") {
                    alert(dataParsed);
                  } else {
                    alert(dataParsed);
                    var reUrl = "../../XWordPuzzleStandAlone/master_view.php?id=" + storedSelections[0];
                    window.location.href = reUrl;
                  }
                }
              });
            } else if (this.id === "deleteSession") {
              console.log('b');
            } else if (this.id === "viewResults") {
              console.log('c');
            }
          }
        });

        availSession.find("tr").click(function () {
          var selection = $(this);
          var selectionId = selection.attr("id");
          var rowSelection = $("[id=" + selectionId + "]");

          if (storedSelections.length == 0) {
            storedSelections.push(selection.attr("id"));
            rowSelection.addClass("info");
            rowSelection.prop("title", "Selected crossword.");

          } else {
            var index = storedSelections.indexOf(String(selectionId));
            if (index > -1) {
              storedSelections.splice(index, 1);
              rowSelection.removeClass("info");

            } else {
              var removeSelection = storedSelections.pop();
              $("[id=" + removeSelection + "]").removeClass("info");
              storedSelections.push(selection.attr("id"));
              rowSelection.addClass("info");
              rowSelection.prop("title", "Selected crossword.");
            }
          }
        });


      });
    </script>
  </head>

  <body>
    <!-- This page can be viewed by both super users and admin -->
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
          <a class="navbar-brand" href="../index.php" style="color:white;">REP Crossword View Page
          </a>
        </div>
        <!-- Collection of nav links, forms, and other content for toggling -->
        <div id="navbarCollapse" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a href="#" style="color:white;">Sessions</a></li>
            <li><a href="../reviews/reviews.php" style="color:white;">Performance</a></li>
            <li><a href="../users/users.php" style="color:white;">Users</a></li>
          </ul>
          <div id="navbar" class="navbar-collapse collapse">
            <div class="navbar-right navbar-form" style="color:white;">
              <?php loginNavBarAction($mysqli); ?>
              <a class="btn btn-success" href="../includes/logout.php" role="button">Log out
              </a>
            </div>
          </div>
        </div>
      </div>
    </nav>

    <div class="jumbotron">
      <div class="container">
        <div class="row">
          <div class="col-xs-12 col-md-10 col-md-offset-1">
            <div class="col-md-6 col-md-offset-3">
              <table class="table table-bordered table-responsive" id="sessionOptions">
                <caption style="font-size:30px;" class="text-center">My sessions</caption>
                <tr>
                  <td style="cursor: pointer; width: 33%" id="startSession">Start</td>
                  <td style="cursor: pointer; width: 33%" id="deleteSession">Delete</td>
                  <td style="cursor: pointer; width: 33%" id="viewResults">View results</td>
                </tr>
              </table>
            </div>
            <!-- The available sessions section -->
            <table id="sessionsOnline" class="table table-striped">
              <thead>
              <tr>
                <th>Group name</th>
                <th>Session ID</th>
                <th>Crossword ID</th>
                <th>Available From</th>
                <th>End time</th>
                <th>Online</th>
                <th>Teams joined</th>
              </tr>
              </thead>
              <tbody id="availSessionList" style="cursor: pointer;">
                <?php
                  availSessionCheck($mysqli);
                ?>
              </tbody>
            </table>

          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <hr>
        <p>&copy; 2016 Product of REP
        </p>
      </div>
    </div>
    <!--**********************************************************************-->
    <!-- For the case of wrong login -->
    <?php
       elseif ((login_check($mysqli) == true) && role_check($mysqli) == 1)  :
      echo '<script>';
      echo 'window.location.href="../user.php"';
      echo '</script>';
      else :
      echo '<script>';
      echo 'window.location.href="../index.php"';
      echo '</script>';
      endif;
    ?>
  </body>
</html>
