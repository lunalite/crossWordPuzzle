<?php
include_once 'includes/passwordSuccess.php';
include_once 'includes/functions.php';

sec_session_start();

if ((login_check($mysqli) == true) && role_check() != 0) :
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Change Password</title>
        <link rel="stylesheet" type="text/css" href="styles.css" />
        <script type="text/JavaScript" src="js/sha512.js"></script> 
        <script type="text/JavaScript" src="js/forms.js"></script>
    </head>
    <body>
        <p>If you are done, please <a href="includes/logout.php">log out</a>.</p>
        <p>You are currently logged in as <?php echo htmlentities($_SESSION['username'])?>.</p>

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
        <?php elseif ((login_check($mysqli) == true) && role_check() == 0)  :
            echo '<script>';
            echo 'window.location.href="../user.php"';
            echo '</script>';
            
        else :
            echo '<script>';
            echo 'window.location.href="../index.php"';
            echo '</script>';

        endif; ?>
    </body>
</html>
