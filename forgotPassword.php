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
        <script src='https://www.google.com/recaptcha/api.js'></script>
        <script>

$(function() {
  $('#forgetPassSubmit').submit(function(event) {
    if(grecaptcha.getResponse().length == 0) {
      event.preventDefault();
      alert('Please complete the captcha.');
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

<form id="forgetPassSubmit" action="./includes/forgotPass.php" method="POST">
<div class="form-group">
    <label for="exampleInputEmail1">Email address</label>
    <input type="email" class="form-control" id="exampleInputEmail1" name="email" aria-describedby="emailHelp" placeholder="Enter email">
    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
</div>
  <div class="g-recaptcha" data-sitekey="6LexyiYTAAAAADo8nNcWQ9FKPQPQBwqiVTKTcm26"></div>
<br />
<input type="submit" name="ForgotPassword" class="btn btn-primary" value=" Request Reset " />
                    </form>

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
