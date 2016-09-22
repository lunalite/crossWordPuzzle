// Added the jQuery ready document function for AJAX purposes


  // Declaration of all variables
  var pixelSize = 10;
  var pixelSizeY = 15;
  var c = document.getElementById("myCanvas");
  var rect = c.getBoundingClientRect();
  c.addEventListener("click", getPosition, false);
  var ctx = c.getContext("2d");
  var screenWidth = window.innerWidth;
  var screenHeight = window.innerHeight;
  var pixelSizeX = screenWidth / 153;
  console.log("dx is " + pixelSizeX);
  c.width = screenWidth * 0.75;
  c.height = screenHeight;
  console.log("Screen size is " + screenWidth + " x " + screenHeight);
  var NUM_COLS = 30;
  var NUM_ROWS = 30;
  var tileCellWidth = c.width / NUM_COLS;
  if (c.height < c.width) {
    c.height = tileCellWidth * NUM_ROWS;
  }
  var Tilepadding = 1;
  var tileWidth = tileCellWidth - Tilepadding;
  var mouseX = 0;
  var mouseY = 0;
  var tiles = [];
  var questionList = [];
  var answerList = [];
  var tileCodeList = [];
  var noOfFields = 6;
  var attempts = [];
  var answered = []; // 0 - not answered / answered wrongly 1 - answered correctly
  var noOfQuestions = 0;
  //var audio = new Audio('music.wav');
  //audio.play();
  var title = "Accounting";
  var bonus = 0;
  var qStack = []; //qStack to store the number of correct answers, etc. in array before transforming into jquery
  var startTime;
  var tileSelected = 0 ;
  var currentQns = "NIL";

  // function to obtain session storage information
  // Note that it is one-off storage of data. Closing of session will cause data to be gone
  
  function clearStorage(){
 	    localStorage.removeItem('startTime');
	    localStorage.removeItem('answered');
	    localStorage.removeItem('attempts');
	    localStorage.removeItem('noOfQuestions');
  }
  
  function recallStorage(size) {
    attemptsData = JSON.parse(localStorage.getItem('attempts'));
    answeredData = JSON.parse(localStorage.getItem('answered'));

    if (attemptsData != null) {
      noOfQuestions = (localStorage.getItem('noOfQuestions'));
      console.log("Number of questions left: " + noOfQuestions);
      attempts = attemptsData.slice(0);
      answered = answeredData.slice(0);
      for (i = 0; i < answered.length; i++) {

        if (answered[i] == 1) {
          wordToTiles(answerList[i]);
          console.log("reloading....");
        }
      }
    } else {
      // for case where no data is present
      for (i = 0; i < size; i++) {
        attempts.push(0);
        answered.push(0);
      }
    }

    if (localStorage.getItem('startTime') !== null) {
      startTime = new Date(localStorage.getItem('startTime'));
    } else {
      // startTime is stored into localStorage in case shit happens
      startTime = new Date();
      localStorage.setItem('startTime', startTime);
    }
  }


  // function to store information into session
  function storeData() {
    console.log('storing...');
    localStorage.setItem('attempts', JSON.stringify(attempts));
    localStorage.setItem('answered', JSON.stringify(answered));
    localStorage.setItem('noOfQuestions', JSON.stringify(noOfQuestions));
  }

  c.onmouseover = function (e) {
    console.log("Moved");
    if (c.isPointInPath)
      console.log("Hovering...");
  }

  var Tile = function (x, y, id, c, qns_id) { //Class Declaration for Tile Object
    this.x = x;
    this.y = y;
    this.width = tileWidth;
    this.char = c;
    this.id = id;
    this.qns_id = qns_id;
    this.intersected = false;
    this.master = false;
    this.drawn = false;
    //Each tile is given a unique id starting from 0, traversing each column before going to the next row	
  };
  

  function getTitle() {
    var url2 = "includes/getTitle.php";
    console.log("getting title");
    jQuery.getJSON(url2, function (data) {
      console.log("RECEIVED");
      str = data[0]['PuzzleName'];
      document.getElementById("title").innerHTML = str;
    });
  }

  function getTiles() {
    console.log("Getting data");
    var url = "../phpretrieval/includes/getIdFromsSess.php";
    jQuery.getJSON(url, function (data) {
      // ... handle response as above
      //var str = JSON.stringify(data);
      //console.log(str);
      //var crosswordID=getIDfromStr(str);

      var crosswordID = data[0]['crosswordId'];

      console.log("ID is " + crosswordID);
      var url = "../phpretrieval/includes/qnOutput.php";
      jQuery.getJSON(url, { crosswordId: crosswordID }, function (data) {
        var arr = jQuery.map(data, function (e1) { return e1; });
        var size = arr.length;
        //console.log("Array is of size " + size);

        // Recalling storage for number of attempts / answered qns in case of refresh
        for (i = 0; i < size; i++) {
          questionList.push(data[i]['Question']);
          word = data[i]['Answer'];
          word = word.replace(/\s/g, '');
          answerList.push(word);
          tileCodeList.push(data[i]['TileCode']);
        }
        //console.log("Questions: " + questionList);
        //console.log("Answers: " + answerList);
        //console.log("Codes: " + tileCodeList);
        //console.log(answerList.length);

        for (i = 0; i < answerList.length; i++) {
          console.log("Now filling the " + i + "th answer");
          noOfQuestions++;
          createTilesFromString(tileCodeList[i], answerList[i]);
        }
        DrawEmptyTiles();
        DrawNumbers();
        recallStorage(size);
        console.log('Saved attempts are: ' + attempts);
        console.log('Saved answered are: ' + answered);
        console.log('startTime is: ' + startTime);
        console.log('elapsed time: ' + timeCheck());
      });
    });
  }

  function setTimeoutToGameOver(){
  	var currentTime = new Date().getTime()/1000 ;
  	console.log("Current Time is "+currentTime);
  	var url = "../phpretrieval/includes/getTimeEndFromSessId.php";
    	jQuery.getJSON(url, function (data) {
    		console.log("End Time: "+data);
    		var milliSecondsLeft = (data - currentTime)*1000 ;
    		console.log("You have "+milliSecondsLeft +" left.");
    		if (milliSecondsLeft > 0)
    			setTimeout(function () {exitGame();}, milliSecondsLeft );
    		else
    			exitGame();
    	});
  }

  /*
  function getIDfromStr(str){
  str=str.substring(3);
  console.log("Chopped str is "+str);
  var ID=parseInt(str);
  return ID;
  }
  */

  function printTiles() {
    //for (b=0;b<tiles.length;b++)
    //console.log(tiles[b]);
  }

  function DrawEmptyTiles() {
    for (b = 0; b < tiles.length; b++) {
      var tile = tiles[b];
      tile.drawFaceDown();
    }
  }

  function DrawNumbers() {
    for (b = 0; b < tiles.length; b++) {
      var tile = tiles[b];
      if (tile.master)
        tile.drawQnsNo();
    }
  }

  function containsTile(id, list) {
    var i;
    for (i = 0; i < list.length; i++) {
      if (list[i].id == id) {
        return true;
      }
    }
    return false;
  }

  function createTilesFromString(str, ans) {
    var ID1 = str.substring(0, 4);
    var ID2 = str.substring(4, 8);
    ID1 = parseInt(ID1);
    ID2 = parseInt(ID2);
    //console.log("Decoding " + ID1 + " and " + ID2 + " for " + ans);
    var diff = ID2 - ID1;
    //console.log("Difference is "+diff);
    //console.log("Length of word is "+ans.length);
    for (j = 0; j < ans.length; j++) {
      console.log("Now at Tile "+ID1+",with i = "+i+" for qns id "+answerList.indexOf(ans));
      if (containsTile(ID1, tiles)) {
        //console.log("Collision detected on Tile: " + ans.charAt(j));
        var tile = getTileFromId(ID1);
        tile.intersected = true;
      }
      var pos = tileIDtoPos(ID1);
      var tile = new Tile(pos[0] * tileCellWidth, pos[1] * tileCellWidth, ID1, ans.charAt(j), i);
      if (j == 0)
        tile.master = true;
      //console.log("Inserting character "+ans.charAt(j));
      //tile.drawFaceDown();
      tiles.push(tile);
      if (diff >= NUM_ROWS) //Go Down
        ID1 += NUM_ROWS;
      else if (diff > 0) //Go Right
        ID1++;
      else if (diff <= -NUM_ROWS)
        ID1 += NUM_ROWS;
      else if (diff < 0)
        ID1--;
    }
    printTiles();
  }

  function posToTileID(x, y) {		//Convert Mouse Click position to ID of the tile clicked
    var ID = (parseInt((y) / tileCellWidth) * NUM_ROWS) + parseInt((x) / tileCellWidth);
    return ID;
  }

  function tileIDtoPos(ID) {		//Convert tile ID to starting position of tile
    var pos = new Array();
    var y = parseInt(ID / NUM_ROWS);
    var x = ID % NUM_ROWS;
    pos[0] = x;
    pos[1] = y;
    return pos;
  }

  function getPosition(e) {
    console.log("Rect.left is "+rect.left+" while rect.top is "+rect.top);
    var scrollTop = $(window).scrollTop();
    var scrollRight = $(window).scrollLeft(); //Function called when a tile is clicked
    console.log("Detected scrolled up "+scrollTop+" and scrollRight "+scrollRight);
    mouseX = e.clientX + scrollRight - rect.left;
    mouseY = e.clientY + scrollTop - rect.top;
    console.log("raw X " + e.clientX);
    console.log("raw Y" +  e.clientY);
    tileSelected = posToTileID(mouseX, mouseY);
    console.log("Selected Tile: " + tileSelected);
    var tile = getTileFromId(tileSelected);
    console.log(tile.char);
    if (tile.intersected) {
      alertify.alert("Pick a non intersecting tile!");
      return;
    }
    var question = questionList[tile.qns_id];
    currentQns = question ;


    // Check if number of attempts exceeded 2 times
    if (attempts[tile.qns_id] > 1) {
      alertify.error("You have exceeded the number of attempts for this question!");
      // Check if question is answered correctly. If it is, do nothing
    } else if (answered[tile.qns_id] == 1) {

      // If question is unanswered or 1 attempt remains, do this      
    } else {
      document.getElementById("currentQuestion").innerHTML=question;
      document.getElementById("userAnswer").focus();
      document.getElementById("userAnswer").select();
      /*alertify.prompt(question, function (e, word) {
        if (e) {
          word = word.toUpperCase();
          word = word.replace(/\s/g, '');

          var correct = checkAnswer(word, tileSelected);

          if (correct) {
            // Write down the words onto the tiles
            wordToTiles(word);
            alertify.success("CORRECT!");
          }
          else{
            	alertify.error("WRONG!");      		
            }
        }
      }, "");*/

    }
  }
  
  function userAnswers(){
  	if (currentQns == "NIL"){
  		alertify.error("Select a tile first!");
  		return ;
  	}
  	var userAnswer=document.getElementById("userAnswer").value;
  	if (userAnswer.length == 0){
  		alertify.error("Please enter an answer!");
  		return ;
  	}
  	userAnswer = userAnswer.toUpperCase();
        userAnswer = userAnswer.replace(/\s/g, '');
	console.log("USER ENTERED "+userAnswer);
          var correct = checkAnswer(userAnswer, tileSelected);

          if (correct) {
            // Write down the words onto the tiles
            wordToTiles(userAnswer);
            alertify.success("CORRECT!");
             document.getElementById("userAnswer").value='';
            document.getElementById("currentQuestion").innerHTML="Great !";
            currentQns = "NIL";
          }
          else{
           	 alertify.error("WRONG!");
           	 if (attempts[questionList.indexOf(currentQns)] > 1) {
            		currentQns = "NIL" ;	
            		document.getElementById("currentQuestion").innerHTML="You have exceeded the number of attempts for this question!";
            	}   
            }
  }

  function getTileFromId(tileID) {
    for (z = 0; z < tiles.length; z++) {
      var tile = tiles[z];
      if (tile.id == tileID)
        return tile;
    }
  }

  function stateChange() {
    for (i in attempts) {
      qStack.push({
        attempts: attempts[i],
        answered: answered[i]
      })
    }
    setTimeout(function () {
      window.location.replace("includes/deleteSessions.php");
    }, 5000);
  }

  // Check time by subtracting from initial starting time to get time elapsed.
  function timeCheck() {
    var endTime = new Date();
    var timeTaken = (endTime - startTime) / 1000;
    console.log("Took " + timeTaken);
    return timeTaken;
  }

  function checkAnswer(word, tileID) {
    var tile = getTileFromId(tileID);
    var correctAnswer = answerList[tile.qns_id];
    console.log('qns id ' + tile.qns_id);
    console.log("Answer is " + correctAnswer);
    console.log(word);

    // Checking mechanism
    if (correctAnswer == word) {

      // Change answered array for that qn to 1 and store it
      answered[tile.qns_id] = 1;
      noOfQuestions--;
      storeData();
      addScore(tile.qns_id);
      console.log('score added: ' + scoreAdded(tile.qns_id));

      // Once all answers are answered correctly, call for                 
      if (noOfQuestions == 0)
        exitGame();
      return true;
    }

    else {
      attempts[tile.qns_id]++;
      if (attempts[tile.qns_id] == 2)
        noOfQuestions--;
      if (noOfQuestions == 0)
        exitGame();
      console.log(attempts);

      // Store attempts into session in case user refreshes browser
      storeData();

      return false;
    }
  }

  function exitGame() {
    localStorage.removeItem('startTime');
    localStorage.removeItem('answered');
    localStorage.removeItem('attempts');
    localStorage.removeItem('noOfQuestions')
    alertify.alert("The Game Has ended!");
    stateChange();
    historise();
  }

  function checkCollisionX(word, tileID) {	//Check for collision of the same letters in the horizontal direction
    for (i = 0; i < word.length; i++) {
      var tile = getTileFromId(tileID);
      console.log(tile.id);
      console.log(tile.char + "," + word.charAt(i));
      if (tile.char != '' && tile.char != word.charAt(i))
        return true;
    }
    return false;
  }

  function checkCollisionY(word, tileID) { //Check for collision of the same letters in the vertical direction
    for (i = 0; i < word.length; i++) {
      var tile = getTileFromId(tileID);
      console.log(tile.char + "," + word.charAt(i));
      if (tile.char != '' && tile.char != word.charAt(i))
        return true;
    }
    return false;
  }

  function wordToTiles(word) {	//Insert a word entered by the user into the appropriate tiles
    var qns_id = answerList.indexOf(word);
    var tracker = 0;
    for (a = 0; a < tiles.length; a++) {
      var tile = tiles[a];
      if (tile.qns_id == qns_id) {
        tile.char = word[tracker++];
        tile.drawAns();
        console.log("Drawn on tile " + tile.char+" of ID "+tile.id);
      }
    }
  }



  Tile.prototype.drawFaceDown = function () { //Function to draw the tile
    ctx.beginPath();
    ctx.rect(this.x, this.y, this.width, this.width);
    ctx.fillStyle = "white";
    ctx.fill();

  };

  Tile.prototype.drawQnsNo = function () {
    ctx.beginPath();
    ctx.fillStyle = "black";
    var size = tileCellWidth / 4;
    ctx.font = size + "pt Arial";
    ctx.fillText((this.qns_id + 1), this.x, (this.y + (tileWidth)), tileWidth);
    ctx.fill();
  }

  Tile.prototype.drawAns = function () { //Function to draw the tile
    ctx.beginPath();
    ctx.fillStyle = "black";
    var size = tileCellWidth / 2;
    ctx.font = size + "pt Arial";
    ctx.fillText(this.char, this.x + (tileWidth / 4), (this.y + (tileWidth / 1.5)), tileWidth);
    ctx.fill();

  };


  setTimeoutToGameOver();
  getTiles();
  getTitle();


  function scoreAdded(qid) {
    // Check number of attempts;
    var att = attempts[qid];

    // Give amount of scores based on attempts
    switch (att) {
      case 0:
        return 3;
        break;
      case 1:
        return 2;
        break;
      case 2:
        console.log("too bad");
        return 0;
        break;
      default:
        return 99;
    }
  }
  

  function addScore(qid) {
    $.ajax({
      type: "POST",
      datatype: 'json',
      url: "./includes/addScore.php",
      data: { "qid": qid, "score": scoreAdded(qid), "time": timeCheck() },
      cache: false,
      success: function (data) {
        console.log(data);
      }
    });
  }

  function historise() {
    $.ajax({
      type: "POST",
      datatype: 'json',
      url: "./includes/historise.php",
      data: { "qStack": JSON.stringify(qStack), "time": timeCheck() },
      cache: false,
      success: function (data) {
        console.log(data);
      }
    });
  }

