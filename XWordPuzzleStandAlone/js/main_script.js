// Declaration of all variables
    var pixelSize=10;
	var pixelSizeX=0;
	var pixelSizeY=60;
	var c = document.getElementById("myCanvas");
	c.addEventListener("click", getPosition,false);
	var ctx = c.getContext("2d");
	var screenWidth=window.innerWidth;
	var screenHeight=window.innerHeight;
	c.width = screenWidth;
    c.height = screenHeight;
	var NUM_COLS = 40;
	var NUM_ROWS = 40;
	var tileCellWidth=screenWidth/NUM_ROWS;
	var Tilepadding=6;
	var tileWidth=tileCellWidth-Tilepadding;
	var mouseX=0;
	var mouseY=0;
	var tiles = [];
	var questionList=[];
	var answerList=[];
	var tileCodeList=[];
	//var title=prompt("Please enter the title of the puzzle");
	var noOfFields=6;
	var attempts = [];    
    var answered = []; // 0 - not answered / answered wrongly 1 - answered correctly
	var noOfQuestions=0;
	//var audio = new Audio('music.wav');
	//audio.play();
	var title="Accounting";
	//sessionStorage.removeItem('answered');
	//sessionStorage.removeItem('attempts');
	


// function to obtain session storage information
// Note that it is one-off storage of data. Closing of session will cause data to be gone
    function recallStorage(size) {
        attemptsData = JSON.parse(sessionStorage.getItem('attempts'));
        answeredData = JSON.parse(sessionStorage.getItem('answered'));
        if (attemptsData != null) {
            attempts = attemptsData.slice(0);
            answered = answeredData.slice(0);
			for (i=0;i<answered.length;i++){
				if (answered[i]==1){
					wordToTiles(answerList[i]);
					console.log("reloading....");
				}
			}
            /*
            ** NEED UPDATE HERE
            ** A code for converting the correctly answered tiles to word using wordToTiles();
            ** I've no idea how it works so i'll leave it to you.
            */
        }
        else {
            // for case where no data is present
            for (i = 0; i < size; i++) {
                attempts.push(0);
                answered.push(0);
            }
        }
    }


// function to store information into session
    function storeData() {
        sessionStorage.setItem('attempts', JSON.stringify(attempts));
        sessionStorage.setItem('answered', JSON.stringify(answered));
    }

	c.onmouseover = function(e) {
		console.log("Moved");
		if (c.isPointInPath)
			console.log("Hovering...");
  }
	
	var Tile = function(x, y,id,c,qns_id) { //Class Declaration for Tile Object
		this.x = x;
		this.y = y;
		this.width = tileWidth;
		this.char=c;
		this.id=id;
		this.qns_id=qns_id;
		this.intersected=false;
		//Each tile is given a unique id starting from 0, traversing each column before going to the next row	
	};
	
	function getTitle(){
		var url2="includes/getTitle.php";
		console.log("getting title");
		jQuery.getJSON(url2, function (data) {
		    console.log("RECEIVED");
		    str = JSON.stringify(data);
		    str = str.substring(3, str.length - 3);
		    document.getElementById("title").innerHTML = str;
		});
	}
	
	function getTiles(){
		console.log("Getting data");
		var url="../phpretrieval/includes/getIdFromsSess.php";
		jQuery.getJSON(url, function (data) {
		    // ... handle response as above
		    //var str = JSON.stringify(data);
		    //console.log(str);
		    //var crosswordID=getIDfromStr(str);

		    console.log(data);
		    var crosswordID = data;

		    console.log("ID is " + crosswordID);
		    var url = "../phpretrieval/includes/qnOutput.php";
		    jQuery.getJSON(url, { crosswordId: crosswordID }, function (data) {
		        str = JSON.stringify(data);
		        console.log(str);
		        var arr = jQuery.map(data, function (e1) { return e1; });
		        var size = arr.length / noOfFields;
		        console.log("Array is of size " + size);

                // Recalling storage for number of attempts / answered qns in case of refresh
		        for (i = 0; i < size; i++) {
		            questionList.push(arr[3 + i * noOfFields]);
		            answerList.push(arr[4 + i * noOfFields].replace(/\s/g,''));
		            tileCodeList.push(arr[5 + i * noOfFields]);
		        }
		        console.log("Questions: " + questionList);
		        console.log("Answers: " + answerList);
		        console.log("Codes: " + tileCodeList);
		        console.log(answerList.length);
                console.log('Saved attempts are: ' + attempts);
                console.log('Saved answered are: ' + answered);
		        for (i = 0; i < answerList.length; i++) {
		            console.log("Now filling the " + i + "th answer");
					noOfQuestions++;
		            createTilesFromString(tileCodeList[i], answerList[i]);
		        }				recallStorage(answerList.size);
		    });
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

	 function printTiles(){
		//for (b=0;b<tiles.length;b++)
			//console.log(tiles[b]);
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
	
	function createTilesFromString(str,ans){
		var ID1= str.substring(0,4);
		var ID2= str.substring(4,8);
		ID1=parseInt(ID1);
		ID2=parseInt(ID2);
		console.log("Decoding "+ID1+" and "+ID2+" for "+ans);
		var diff=ID2-ID1;
		//console.log("Difference is "+diff);
		//console.log("Length of word is "+ans.length);
				for(j=0;j<ans.length;j++){
					//console.log("Now at Tile "+ID1+",with i = "+i);
					var pos = tileIDtoPos(ID1);
					var tile =new Tile(pos[0]*tileCellWidth,pos[1]*tileCellWidth,ID1,ans.charAt(j),i);
					//console.log("Inserting character "+ans.charAt(j));
					tile.drawFaceDown();
					if (containsTile(ID1,tiles)){
						console.log("Collision detected on Tile: "+ans.charAt(j));
						getTileFromId(ID1).intersected=true;
						tile.intersected=true;
					}
					tiles.push(tile);
					if (diff>=NUM_ROWS) //Go Down
						ID1+=NUM_ROWS;
					else if (diff>0) //Go Right
						ID1++;
					else if (diff<=-NUM_ROWS)
						ID1+=NUM_ROWS;
					else if (diff < 0)
						ID1--;
				}
				printTiles();
		}
	
	function posToTileID(x,y){		//Convert Mouse Click position to ID of the tile clicked
		var ID = (parseInt((y-pixelSize)/tileCellWidth) * NUM_ROWS) + parseInt((x-pixelSize)/tileCellWidth);
		return ID;
	}
	
	function tileIDtoPos(ID){		//Convert tile ID to starting position of tile
		var pos = new Array();
		var y = parseInt(ID / NUM_ROWS);
		var x = ID % NUM_ROWS;
		pos[0]=x;
		pos[1]=y;
		return pos;
	}
	
	function getPosition(e) {
		var scrollTop = $(window).scrollTop();		//Function called when a tile is clicked
		mouseX = e.clientX+pixelSizeX;
		mouseY = e.clientY+scrollTop-pixelSizeY;
		console.log("X "+mouseX);
		console.log("Y"+mouseY);
		var tileSelected=posToTileID(mouseX,mouseY);
		var tile=getTileFromId(tileSelected);
		console.log(tile.char);
		if (tile.intersected){
			alertify.alert("Pick a non intersecting tile!");
			return ;
		}
		var question=questionList[tile.qns_id];
		console.log("Selected Tile: "+tileSelected);

        // Check if number of attempts exceeded 2 times
        if (attempts[tile.qns_id] > 1) alertify.error("You have exceeded the number of attempts for this question!");

        // Check if question is answered correctly. If it is, do nothing
        else if (answered[tile.qns_id] == 1) ;

        // If question is unanswered or 1 attempt remains, do this
        else {
            alertify.prompt(question, function (e, word) {
                if (e) {
                    word = word.toUpperCase();
					word=word.replace(/\s/g,'');
                    var correct = checkAnswer(word, tileSelected);

                    if (correct) {
                        // Write down the words onto the tiles
                        wordToTiles(word);
                        alertify.success("CORRECT!");
                    }
                    else
                        alertify.error("WRONG!");
                }
            }, "");

        }
     }
  
		function getTileFromId(tileID){
			for (z=0;z<tiles.length;z++){
			  var tile=tiles[z];
			  if(tile.id == tileID)
				  return tile;
			}
		}
		
	function stateChange() {
		setTimeout(function () {
		window.location.replace("includes/deleteSessions.php");
		}, 5000);
	}
  
	  function checkAnswer(word,tileID){
			var tile=getTileFromId(tileID);
			var correctAnswer=answerList[tile.qns_id];
            console.log('qns id ' + tile.qns_id);
			console.log("Answer is "+correctAnswer);

            // Checking mechanism
			if (correctAnswer==word) {
                
                // Change answered array for that qn to 1 and store it
			    answered[tile.qns_id] = 1;
			    storeData();

                // Scores are only added to the first person who answered correctly
			    addScore(tile.qns_id);
			    console.log('score added: ' + scoreAdded(tile.qns_id));

                // Once all answers are answered correctly, call for exitGame()
				noOfQuestions--;
				if (noOfQuestions==0)
					exitGame();
				return true;
                }

			else {
			    attempts[tile.qns_id]++;
			    console.log(attempts);

                // Store attempts into session in case user refreshes browser
			    storeData();

				return false;		  
                }
	  }
	  
	  function exitGame(){
		 sessionStorage.removeItem('answered');
		 sessionStorage.removeItem('attempts');
		  alertify.alert("The Game Has ended!");
		  var url="../phpretrieval/includes/getScore.php";
		  jQuery.getJSON(url, function (data) {
				var str = JSON.stringify(data);
				str=str.substring(2,str.length-1);
				var score=parseInt(str);
				console.log("Got score"+score);	
			});
		  stateChange();
	  }
  
	 function checkCollisionX(word,tileID){	//Check for collision of the same letters in the horizontal direction
		for ( i=0; i < word.length ; i++){
			var tile=getTileFromId(tileID);
			console.log(tile.id);
			console.log(tile.char + "," + word.charAt(i));
			if (tile.char != '' && tile.char != word.charAt(i))
				return true;			
		}
		return false;
	}
	
	function checkCollisionY(word,tileID){ //Check for collision of the same letters in the vertical direction
		for ( i=0; i < word.length ; i++){
			var tile=getTileFromId(tileID);
			console.log(tile.char + "," + word.charAt(i));
			if (tile.char != '' && tile.char != word.charAt(i))
				return true;			
		}
		return false;
	}
	
	function wordToTiles(word){	//Insert a word entered by the user into the appropriate tiles
		var qns_id=answerList.indexOf(word);
		var tracker=0;
		for (a=0;a<tiles.length;a++){
			var tile=tiles[a];
			if (tile.qns_id==qns_id){
				tile.char=word[tracker++];
				tile.drawAns();
				console.log("Drawn on tile "+tile.char);
			}
		}	
	}



	Tile.prototype.drawFaceDown = function() { //Function to draw the tile
		ctx.beginPath();
		ctx.rect(this.x,this.y, this.width,this.width);
		ctx.fillStyle = "black";
		ctx.fill();
	};
	
	Tile.prototype.drawAns = function() { //Function to draw the tile
		ctx.beginPath();
		ctx.fillStyle = "white";
		ctx.fillText(this.char,this.x+(tileWidth/2),(this.y+(tileWidth/2)),tileWidth);
		ctx.fill();

	};
	
	getTiles();
	getTitle();


/************ AJAX implementation for scoring purposes ************/
var xmlHTTP = createXMLhttpRequestObject();

function createXMLhttpRequestObject() {
    var xmlHTTP;

    if (window.ActiveXObject) {
        try {
            xmlHTTP = new ActiveXObject("Microsoft.XMLHTTML");
        } catch(e) {
            xmlHTTP = false;
        }
    } else {
        try {
            xmlHTTP = new XMLHttpRequest();
        } catch(e) {
            xmlHTTP = false;
        }
    }

    if (!xmlHTTP)
        alert("can't create Object!");
    else
        return xmlHTTP;
}

function scoreAdded(qid) {
    // Check number of attempts;
    var att = attempts[qid];
    
    // Give amount of scores based on attempts
    switch(att) {
        case 0: 
            return 3;
            break;
        case 1: 
            return 2;
            break;
        case 2: 
            return 0;
            break;
        default:
            return 99;
    }
}

function addScore(qid) {
    if (xmlHTTP.readyState == 0 || xmlHTTP.readyState == 4) {
        xmlHTTP.open("GET", "./includes/addScore.php?score=" + scoreAdded(qid) + "&qid=" + qid, true);
        xmlHTTP.onreadystatechange = handleServerResponse;
        xmlHTTP.send(null); //null for $_GET responses.
    } 
}

function handleServerResponse() {
    if (xmlHTTP.readyState == 4) {
        if (xmlHTTP.status == 200 ) {
            xmlResponse = xmlHTTP.responseXML;
            xmlDocumentElement = xmlResponse.documentElement;
            message = xmlDocumentElement.firstChild.data;
            console.log(message);
        } else {
        alert('something went wrong');
        }
    }
}
