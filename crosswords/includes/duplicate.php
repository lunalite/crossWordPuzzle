<?php
    include_once '../../includes/db_connect.php';
    include_once '../../includes/psl-config.php';
    include_once '../../includes/phpVariables.php';

	$defaultTileCode='Not Assigned yet';
    //echo $_POST["questions"]."<br>";
    $string = $_POST["questions"];
    $id = $_POST["id"];
    $questions = preg_split("/@/",$string);
    array_shift($questions);

    // Checks for the first id from crosswordmasterdb.
    $sql = "SELECT crosswordId FROM ".$GLOBALS['crosswordMaster'];
    $result = $mysqli->query($sql);
    $latestCrossWordId = 0;

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            //echo "id: " . $row["crosswordId"]. "<br>";
            // Obtain the maximum id
            if ($row["crosswordId"] > $latestCrossWordId) 
                $latestCrossWordId = $row["crosswordId"];
        }
    } 
    else {
        $latestCrossWordId --;
    }
    $latestCrossWordId ++;

    
    // Inserting into master database id of the new crossword to be added.
    $sql = "INSERT INTO ".$GLOBALS['crosswordMaster']. " (crosswordId) VALUES (" . $latestCrossWordId . ")";
    if ($mysqli->query($sql) === TRUE) {
    } else {

    }
    
    $sql = "SELECT crosswordId FROM ".$GLOBALS['crosswordMaster'];
    $result = $mysqli->query($sql);
    $latestCrossWordId = 0;
    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            //echo "id: " . $row["crosswordId"]. "<br>";
            // Obtain the maximum id
            if ($row["crosswordId"] > $latestCrossWordId) 
                $latestCrossWordId = $row["crosswordId"];
        }
    } 
    else {
 
        $latestCrossWordId --;
    }

    
    // Inserting all the questions and answers into the crossword bank

    $counter = 1;
    foreach ($questions as $qn2BAdded) {
        $answer = preg_split("/^.+\(\d\s*\w+\)\s+/", $qn2BAdded);
        array_shift($answer);

        $qn2BAddedAgain = preg_split("/".$answer[0]."/", $qn2BAdded);

        $qn2BAddedAgain=filter_var($qn2BAddedAgain[0], FILTER_SANITIZE_STRING);


        $answer[0]=trim($answer[0]);
        $answer[0] = preg_replace('/\s+/', '', $answer[0]);
	    $answer[0]=strtoupper($answer[0]);
        $answer[0]=filter_var($answer[0], FILTER_SANITIZE_STRING);


        $sql = "INSERT INTO ".$GLOBALS['crosswordPuzzles'] . " VALUES (0," . $latestCrossWordId . ", "
        . $counter . ",\"" . $qn2BAddedAgain . "\", \"" . $answer[0] . "\",\"" . $defaultTileCode . "\")";
        if ($mysqli->query($sql) === TRUE) {
            
        } else {
            
        }
        
        $counter ++;
    }
    unset($qn2BAdded); // break the reference with the last element
    echo json_encode($latestCrossWordId);
        
?>		
