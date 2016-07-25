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
            $("#availSessionList").on("click", "tr", function (e) {
                if ($(this).find("td").attr("sessId") == -1) {
                    e.preventDefault();
                } else if (sessionStorage.getItem("sessId") === this.id) {
                    var url = "master.php?";
                    window.location.href = url;
                    sessionStorage.removeItem("sessId");
                } else {
                    sessionStorage.setItem("sessId", this.id);
                    var url = "master.php?sessId=" + this.id;
                    window.location.href = url;
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
          <a class="navbar-brand" href="../index.php" style="color:white;">REP Crossword View Page
          </a>
        </div>
        <!-- Collection of nav links, forms, and other content for toggling -->
        <div id="navbarCollapse" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li>
              <a href="./crosswords.php" style="color:white;">Sessions</a>
            </li>
            <li>
              <a href="../reviews/reviews.php" style="color:white;">Performance</a>
            </li>
            <li>
              <a href="../users/users.php" style="color:white;">Users</a>
            </li>
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
            <!-- The available sessions section -->
              <h3>Available sessions</h3>
              <table id="sessionsOnline" class="table table-striped">
                  <thead>
                  <tr>
                      <th>Session ID</th>
                      <th>Crossword ID</th>
                      <th>Crossword Description</th>
                      <th>Online</th>
                      <th>Open for classGroup</th>
                      <th>Teams joined</th>
                  </tr>
              </thead>
                  <tbody id="availSessionList" style="cursor: pointer;">
                      <?php
                          availSessionCheck($mysqli);
                      ?>
                  </tbody>
              </table>

                <!-- For showing of start and delete sessions -->
                <?php if (!isset($_GET['sessId'])) : ?>
                <form action="includes/sessionStart.php" id="startSession" method="post">
                    <select name="sessId" form="startSession">
                        <?php
                            sessionCheckD($mysqli);
                        ?>
                    </select>
                    <input type="submit" class="btn btn-primary btn-sm" name="startSession" value="Start Session">
                    <input type="submit" class="btn btn-primary btn-sm" name="deleteSession" value="Delete Session">
                </form>

                <?php else : ?>
                <form action="includes/sessionGroupChange.php" id="changeGroup" method="post">
                    <select name="classGroup" form="changeGroup">
                        <?php
                            groupCheckD($mysqli);
                        ?>
                    </select>
                    <input type="hidden" name="sessId" value="<?php echo $_GET['sessId'];?>">
                    <input type="submit" class="btn btn-primary btn-sm" name="changeGroup" value="Change Group">
                    <?php endif; ?>

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
