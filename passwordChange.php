<?php
include_once 'includes/passwordSuccess.php';
include_once 'includes/functions.php';

sec_session_start();
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
        <?php if ((login_check($mysqli) == true) && role_check() != 0) : ?>

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
    </body>

    <!--*********************************************-->
        <!-- For the case of not being a super user -->
        <?php else : ?>
            <p>
                <span class="error">You are not authorized to access this page.</span> 
                Please <a href="index.php">login</a> to a superuser.
            </p>
        <?php endif; ?>
</html>
