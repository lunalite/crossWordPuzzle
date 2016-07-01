<?php
include_once '../includes/passwordSuccess.php';
include_once '../includes/functions.php';



?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Change Password</title>
        <script type="text/JavaScript" src="../js/sha512.js"></script> 
        <script type="text/JavaScript" src="../js/forms.js"></script>
    </head>
    <body>

    You are changing password for <?php echo $_POST['userId'];?>.

        <form method="post" name="passwordChange_form" action="<?php echo esc_url($_SERVER['PHP_SELF']); ?>">

            Password: <input type="password"
                             name="password" 
                             id="password"/><br>
            Confirm password: <input type="password" 
                                     name="confirmpwd" 
                                     id="confirmpwd" /><br>
            <input type="button" 
                   value="Change password" 
                   onclick="return passwordformhash(this.form,
                                   this.form.password,
                                   this.form.confirmpwd);" /> 
        </form>
        <div class="container">
            <div class="row">
                <hr><p>&copy; 2016 Product of REP</p>
            </div>
        </div>
    </body>
</html>
