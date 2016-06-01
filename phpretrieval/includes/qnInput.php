<?php
    include_once 'php-connect.php';
    include_once 'phpVariables.php';
    //echo $_POST["questions"]."<br>";
    $string = $_POST["questions"];
    $questions = preg_split("/\d\)\s+/",$string);
    array_shift($questions);
    //echo $questions[1] . "<br>";
    //echo $questions[2] . "<br>";
    //echo $questions[3] . "<br>";
    // Checks for the first id from crosswordmasterdb.
    // Inserting into master database id of the new crossword to be added.
    $sql = "INSERT INTO " . $tableName . " (crosswordId) VALUES (0)";
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully" . "<br>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $sql = "SELECT crosswordId FROM " . $tableName;
    $result = $conn->query($sql);
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
        //echo "0 results";
        $latestCrossWordId --;
    }
    echo $latestCrossWordId . "<br>";
    
    // Inserting all the questions and answers into the crossword bank
    $counter = 1;
    foreach ($questions as $qn2BAdded) {
        $answer = preg_split("/^.+\(\d\s*\w+\)\s+/", $qn2BAdded);
        array_shift($answer);
        echo $qn2BAdded . "<br>";
        echo $answer[0] . "<br>";
        $answer[0]=trim($answer[0]);
        $qn2BAddedAgain = preg_split("/".$answer[0]."/", $qn2BAdded);
        echo $qn2BAddedAgain[0] . "<br>";
        $sql = "INSERT INTO " . $crosswordBankName . " VALUES (0," . $latestCrossWordId . ", "
        . $counter . ",\"" . $qn2BAddedAgain[0] . "\", \"" . $answer[0] . "\",\"" . $answer[0] . "\")";
        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully" . "<br>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error . "<br><br>";
        }
        
        $counter ++;
    }
    unset($qn2BAdded); // break the reference with the last element
        header("location:../../XWordPuzzleStandAlone/master_template.php?id=".urlencode($latestCrossWordId));   
?>		
