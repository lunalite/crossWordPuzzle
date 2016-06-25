<?php
    include_once '../../includes/db_connect.php';
    include_once '../../includes/functions.php';
    include_once '../includes/db_connect.php';
    include_once '../includes/functions.php';
    
    sec_session_start();  
    
    $sessId = $_SESSION['sess_id'];
    
    $sql = "SELECT * FROM sessionStart INNER JOIN members ON userid = id
            WHERE sessId = $sessId" ;
    
    $result = $mysqli->query($sql);
    
    $data = [];
		while ($row = $result->fetch_assoc()) {
			$data[] = $row;
		}
    
    for ($x=0;$x<count($data);$x++){
    	
    	while ($x>0){
    		if ($data[$x]['scores'] > $data[$x-1]['scores']){
    			$tmp = $data[$x-1];
    			$data[$x-1] = $data[$x] ;
    			$data[$x] = $tmp;
    			$x -- ;
    		}
    		else
    			break;
    	}
    }
    
    $rank = 1;
    
    foreach ($data as $argv) {
        echo "<tr id=\"".$argv['username']."\"> 
                <td>$rank</td> 
                <td class=\"teamR\">".$argv['username']."</td> 
                <td class=\"scoreR\">".$argv['scores']."</td> 
                <td class=\"TimeR\">".$argv['Time']."</td> 
                </tr>";
        $rank++;
    }
    
?>