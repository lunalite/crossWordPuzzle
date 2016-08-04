<?php
    
    include_once 'includes/db_connect.php';
    include_once 'includes/functions.php';
    sec_session_start();
    
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>REP Xword index</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/jumbotron.css" rel="stylesheet">
        <!-- Bootstrap core JavaScript-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="css/js/bootstrap.min.js"></script>
        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="css/js/ie10-viewport-bug-workaround.js"></script>
        <!-- Hashing function -->
        <script type="text/JavaScript" src="./js/sha512.js"></script>
        <script type="text/JavaScript" src="./js/forms.js"></script>
        <script>
            $(function () {
                $('input').keypress(function (e) {
                    console.log(e.which);
                    var key = e.which;
                    if (key == 13)  // the enter key code
                    {
                        $('#submission').click();
                        return false;
                    }
                });
            });
        </script>
    </head>

    <body>
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-brand" href="./index.php" style="color:white;">REP Crossword Index</a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <div class="navbar-right navbar-form" style="color:white;">

                </div><!--/.navbar-collapse -->
            </div>
        </nav>
        <div class="jumbotron">
            <div class="container">
                <div class="col-md-6 col-md-offset-3">
                    
                    <?php echo '
<form method="POST" name="passwordChange_form" action="./includes/resetPass.php">
E-mail Address: <input type="text" name="email" size="20" /><br />
Password: <input type="password" name="password" id="password" /><br>
Confirm password: <input type="password" name="confirmpwd" id="confirmpwd" /><br>
<input type="hidden" name="q" value="';
if (isset($_GET["q"])) {
	echo $_GET["q"];
}
	echo '" /><input type="button" name="ResetPasswordForm" value="Change password" onclick="return passwordformhash(this.form,
                                   this.form.password,
                                   this.form.confirmpwd);" />
</form>';

?>
                </div>
            </div>

        </div>
        <div class="container">
            <div class="row">
                <hr><p>&copy; 2016 Product of REP</p>
            </div>
        </div>
    </body>
</html>
