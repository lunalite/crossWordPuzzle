<?php
include_once '../includes/passwordSuccess.php';
include_once '../includes/functions.php';

sec_session_start();

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Change Password</title>
        <script type="text/JavaScript" src="../js/sha512.js"></script> 
        <script type="text/JavaScript" src="../js/forms.js"></script>
    </head>
    <body>
        <?php if ((login_check($mysqli) == true)) : ?>
You are changing password for userID: 
<?php echo $_POST['userId']; 
$_SESSION['userPW2BC'] = $_POST['userId'];
?>

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

        <!--**********************************************************************-->
        <!-- For the case of wrong login -->
        <?php else :
            echo '<script>';
            echo 'window.location.href="../index.php"';
            echo '</script>';

        endif; ?>
    </body>
</html>
