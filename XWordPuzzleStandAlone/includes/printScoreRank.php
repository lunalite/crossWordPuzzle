<?php
    include_once '../../includes/db_connect.php';
    include_once '../../includes/functions.php';
    
    sec_session_start();  
    
    $sessId = $_SESSION['sess_id'];
    
    $sql = "SELECT userid, username, scores FROM sessionstart INNER JOIN members ON userid = id
            WHERE sessId = $sessId" ;
    
    $result = $mysqli->query($sql);
    $array=mysqli_fetch_all($result,MYSQLI_NUM);
    $rank = 1;
    
    foreach ($array as $argv) {
        echo "<tr id=\"$argv[1]\"> 
                <td>$rank</td> 
                <td class=\"teamR\">$argv[1]</td> 
                <td class=\"scoreR\">$argv[2]</td> 
                </tr>";
        $rank++;
    }
    
?>