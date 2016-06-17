<?php
    include_once '../../includes/db_connect.php';
    include_once '../../includes/functions.php';
    
    sec_session_start();  
    $_SESSION['sess_id']=1;
    $sessId = $_SESSION['sess_id'];
    
    $sql = "SELECT userid, username, scores FROM sessionStart INNER JOIN members ON userid = id
            WHERE sessId = $sessId" ;
    
    $result = $mysqli->query($sql);
    
    $data = [];
		while ($row = $result->fetch_assoc()) {
			$data[] = $row;
		}
    
    $rank = 1;
    
    
    foreach ($data as $argv) {
        echo "<tr id=\"".$argv['username']."\"> 
                <td>$rank</td> 
                <td class=\"teamR\">".$argv['username']."</td> 
                <td class=\"scoreR\">".$argv['scores']."</td> 
                </tr>";
        $rank++;
    }
    
?>