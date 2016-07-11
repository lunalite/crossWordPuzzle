<?php  
    include_once '../includes/db_connect.php';
    include_once '../includes/functions.php';
    
    sec_session_start();

    $id=$_GET["id"];
?>
<html>
    <head>
        <meta charset="utf-8">
        <meta content="width=device-width" name="viewport">
        <meta content="yes" name="apple-mobile-web-app-capable">
        <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
        <link href="../css/bootstrap.css" rel="stylesheet">
        <link href="../css/navbar-fixed-side.css" rel="stylesheet" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
        <link rel="stylesheet" href="themes/alertify.core.css" />
        <link rel="stylesheet" href="themes/alertify.default.css" id="toggleCSS" />
        <style>
            .alertify-log-custom {
                    background: blue;
                }
            @font-face {
            font-family: "Eileen Caps Black";
            src: url(../fonts/pdark.ttf) format("truetype");
            }
            #title{
				position:absolute;
				right:15%;
			}
		.canvas{
                background-color: black;
            }
            #title{
            	color : white ;
            }
        </style>

    </head>

    <body>
    <!-- This page can be viewed by both super users and admin -->
    <?php if ((login_check($mysqli) == true) && role_check($mysqli) == 2) : ?>
    <div class="container">
	    <a href="javascript:history.back()">Click Here to go back</a>
    </div>
                   <script src="lib/alertify.min.js"></script>
                   <div class="canvas"><canvas id="myCanvas">
        Your browser does not support the HTML5 canvas tag.</canvas></div>
        <script type="text/javascript">
                            var crosswordId = "<?php echo $id ?>";
        </script>
        <script type="text/javascript" src="js/viewCrosswordAllocation.js">
        </script>

        <!--**********************************************************************-->
        <!-- For the case of wrong login -->
        <?php elseif ((login_check($mysqli) == true) && role_check($mysqli) == 1)  :
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