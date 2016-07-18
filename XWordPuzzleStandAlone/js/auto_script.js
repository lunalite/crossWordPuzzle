	var pixelSize=10;
	var c = document.getElementById("myCanvas");
	var rect = c.getBoundingClientRect();
	c.addEventListener("click", getPosition,false);
	var ctx = c.getContext("2d");
	var screenWidth=window.innerWidth;
	var screenHeight=window.innerHeight;
	c.width = screenWidth*0.5;
    	c.height = c.width;
	var NUM_COLS = 30;
	var NUM_ROWS = 30;
	var tileCellWidth=c.width/NUM_COLS;
	if (c.height < c.width)
		c.height=NUM_ROWS*tileCellWidth;
	var Tilepadding=1;
	var tileWidth=tileCellWidth-Tilepadding;
	var mouseX=0;
	var mouseY=0;
	var xAdj=0.04*rect.left;
	var answerSelected="";
	var currentEncodedID="";
	var answerMap = {};
	var inputDirection="horizontal";
	var header=document.getElementById('instructions');
	var noOfFields=6;
	// add a item
        console.log("Latest id is "+crosswordId);
	var area=NUM_COLS*NUM_ROWS;
	var overall_list = document.getElementById("questionList");	
	var questionList = [];
	var answerList=[];
	var ansStack=[];
	var tileCodeStack=[];
	var actionStack=[];
	var tiles = [];
	var Questions=[];
	var regexInput="";
	var title="";
	var desc="";
	var botMode=false;
		
	function undo(){
		console.log("Stack before undo is now "+actionStack+"\n"+ansStack+"\n"+tileCodeStack);
		if (actionStack.length <= 0)
			return ;
		var action=actionStack.pop();
		var lastAns=ansStack.pop();
		var lastID=tileCodeStack.pop();
		if (action == 0){	
			console.log("Removing answer "+lastAns);
			questionList.push(lastAns);
			populate(questionList);
			removeTiles(lastID,lastAns.length);
		}else if (action == 1){
			var tileID1=parseInt(lastID.substring(0,4));
			var tileID2=parseInt(lastID.substring(4,8));
			var temp=inputDirection;
			var diff=tileID2-tileID1;
			if (diff>=NUM_ROWS) //Go Down
				inputDirection="vertical";
			else
				inputDirection="horizontal";
			wordToTiles(lastAns.replace(/\s/g,''),tileID1);
			inputDirection=temp;
			var index=questionList.indexOf(lastAns);
			questionList.splice(index, 1);
			populate(questionList);
		}
		
	}
	
	function removeTiles(tileCodeID,length){
		var tileID1=tileCodeID.substring(0,4);
		var tileID2=tileCodeID.substring(4,8);
		console.log("Removing tiles "+tileID1+" and "+tileID2);
		tileID1=parseInt(tileID1);
		tileID2=parseInt(tileID2);
		var diff=tileID2-tileID1;
		for (i=0;i<length;i++){
			var tile=tiles[tileID1];
				if (diff>=NUM_ROWS) //Go Down
						tileID1+=NUM_ROWS;
				else if (diff>0) //Go Right
						tileID1++;
				else if (diff<=-NUM_ROWS)
						tileID1+=NUM_ROWS;
				else if (diff < 0)
						tileID1--;
			if (tile.intersected == true){
				tile.intersected=false;
				console.log("Skipping tile "+tile.char);
				tile.drawFaceDown();
				continue;
			}
			console.log("Removing tile "+tile.char+" now...");
			tile.char="";
			tile.drawFaceDown();
		}
	}
	
	var rad = document.myform.direction;
	for(var i = 0; i < rad.length; i++) {
		rad[i].onclick = function() {
			console.log(this.value)
			inputDirection=this.value;
		};
	}
	overall_list.addEventListener("click", function (e) {
	    if (e.target && e.target.nodeName == "LI") {
	        answerSelected = e.target.textContent;
	        header.innerHTML = 'Selected "' + answerSelected + '"! <br> Now select a tile where you want to place your answer at';
	    }
	});
	
	function shuffle(array) {
		  var currentIndex = array.length, temporaryValue, randomIndex;
		
		  // While there remain elements to shuffle...
		  while (0 !== currentIndex) {
		
		    // Pick a remaining element...
		    randomIndex = Math.floor(Math.random() * currentIndex);
		    currentIndex -= 1;
		
		    // And swap it with the current element.
		    temporaryValue = array[currentIndex];
		    array[currentIndex] = array[randomIndex];
		    array[randomIndex] = temporaryValue;
		  }
		
		  return array;
	}
	
	function autobomb(tileid){
		var dirStack=[];
		reset();
		var allocatedAnswers=[];
		var allocatedTiles=[];
		var a=0;
		var tileid=50;
		inputDirection="vertical";
		botMode=true;
		var counter=0;
		var tempList= [];
		tempList=answerList.slice();
		tempList=shuffle(tempList);
		while (tempList.length>0){
			counter++;
			if (counter >= 2*tempList.length){
				var r = confirm("Failed to allocate all answers! Retry with a different configuration?");
				if (r == true) {
					var newID=Math.floor((Math.random() * area));
					autobomb(newID);
					return ;
				} else {
				    return ;
				}				
			}
			console.log(tempList.length+" remaining");
			var toBeInserted=String(tempList.splice(0,1));
			var found = false;
			//window.alert("Array is now "+tempList+" after removing "+toBeInserted);
			answerSelected=toBeInserted;
			console.log("Now trying to insert "+toBeInserted);
			if (allocatedAnswers.length>0){
					for (j=0;j<allocatedAnswers.length;j++){
						// Loop through all the already allocated answers
						/*if (inputDirection=="horizontal" && (j%2!=0))
							continue;
						if (inputDirection=="vertical" && (j%2==0))
							continue;*/
						var toInsertAgainst = allocatedAnswers[j];
						//window.alert("Detected that tile "+ansStack[j]+" is "+dirStack[j]);
						if (dirStack[j] == "horizontal")
							inputDirection="vertical";
						else
							inputDirection="horizontal";
						//window.alert("Comparing "+toBeInserted+" against "+allocatedAnswers[j]+" by inserting in the direction: "+inputDirection); 
						console.log("Inserting against "+toInsertAgainst);
						tileid=allocatedTiles[j];
						for (l=0;l<toBeInserted.length;l++){ //Loop through each char of word to be inserted
							console.log("STUCK IN THIS LOOP");
							var temp=tileid;
							var moveToNextAllocatedAns=false;
							for (k=0;k<toInsertAgainst.length;k++){ // Check through each char against each char of word to insert against
								console.log("trying to insert "+toBeInserted.charAt(l)+" of "+toBeInserted+" against " +toInsertAgainst+" at tile "+tileid);
								if (wordToTiles(toBeInserted,tileid)){
									dirStack.push(inputDirection);
									ansStack.push(answerSelected);
									tileCodeStack.push(currentEncodedID);
									actionStack.push(0);
									counter=0;
									//window.alert(toBeInserted+" inserted!");
									j=100;
									l=100;
									allocatedAnswers.push(toBeInserted);
									allocatedTiles.push(tileid);
									//toggleDirection();
									found=true;
									break;
								}
								else{
									if ( (k == (toInsertAgainst.length-1)) && (l == (toBeInserted.length-1))) { //if all combinations have been reached for the current allocated answer
										moveToNextAllocatedAns=true;
										l=100;
										break;
									}
									if (inputDirection == "horizontal")
										tileid+=NUM_COLS;
									else
										tileid++;
								}
							}
							if (moveToNextAllocatedAns)
								break;
							if (inputDirection == "horizontal")
								tileid = --temp;
							else
								tileid = temp - NUM_COLS ;
							if (tileid <0 || tileid > (area-1)){
								l=100;
							}
								
						}
					}
					//window.alert("ALL COMBINATIONS EXHAUSTED");
					console.log("RECYCLING");
					if (!found){
						//window.alert("RECYCLING "+toBeInserted+" because tileID is "+tileid+" when trying to insert against "+toInsertAgainst);
						tempList.push(toBeInserted);
					}
				}
			else{
				console.log("INITIATING "+tileid+","+toBeInserted)
				dirStack.push(inputDirection);
				wordToTiles(toBeInserted,tileid);
				ansStack.push(answerSelected);
				tileCodeStack.push(currentEncodedID);
				actionStack.push(0);
				allocatedAnswers.push(toBeInserted);
				allocatedTiles.push(tileid);
				//toggleDirection();
			}

		}
		botMode=false;
		answerSelected="";
		alert("Found a possible configuration!");
	}
	
    function toggleDirection(){
    	if (inputDirection == "horizontal")
    		inputDirection="vertical";
    	else
    		inputDirection="horizontal";
    }
    
    function getAnswers(){
		console.log("Getting data");
		var url="../phpretrieval/includes/qnOutput.php";
		jQuery.getJSON(url, { crosswordId: crosswordId }, function (data) {
		    // ... handle response as above

		    var arr = jQuery.map(data, function (e1) { return e1; });
		    var size = arr.length;
		    console.log("Array is of size " + size);
		    for (f = 0; f < size; f++){
		    	var tileCode=arr[f]['TileCode'];
		    	console.log("code is "+tileCode);
		    	var ans=arr[f]['Answer'];
		   	var qns=arr[f]['Question'];
		    	ans=ans.replace(/\s/g,'');
		    	regexInput += "@"+qns+ans+"\n";
		    	console.log("Reconstructed string"+regexInput);
		    	Questions.push(qns);
		        questionList.push(ans);
		        answerList.push(ans);
		        answerMap[ans]=tileCode;
		        if (tileCode!="Not Assigned yet"){
		        	console.log("RESUMING");
			        var tileID1=parseInt(tileCode.substring(0,4));
				var tileID2=parseInt(tileCode.substring(4,8));
				var temp=inputDirection;
				var diff=tileID2-tileID1;
				if (diff>=NUM_ROWS) //Go Down
					inputDirection="vertical";
				else
					inputDirection="horizontal";
				answerSelected=ans;
				if(wordToTiles(ans.replace(/\s/g,''),tileID1)){
					inputDirection=temp;
					var index=questionList.indexOf(ans);
					questionList.splice(index, 1);
				}
		        }
		    }
		    console.log("Answer list formed: " + questionList);
		    answerSelected="";
		    populate(questionList);
		});	
       }
	
	function reset(){
		answerSelected="";
		ctx.clearRect(0, 0, c.width, c.height);
		questionList=[];
		questionList =answerList.concat();
		console.log("Questions are now "+questionList);
		populate(questionList);
		tiles=[];
		ansStack=[];
		tileCodeStack=[];
		actionStack=[];
		initTiles();
	}
	
	function populate(list){
		while (overall_list.firstChild) {
			overall_list.removeChild(overall_list.firstChild);
		}
		for (i = 0; i < list.length; i++) { 
			var entry = document.createElement('li');
			entry.appendChild(document.createTextNode(list[i]));
			console.log("Appending "+list[i]);
			overall_list.appendChild(entry);
		}
		   console.log("Canvas is "+rect.left+" from the left and "+rect.top+" from the top");		
	}


	
	var Tile = function(x, y,id) { //Class Declaration for Tile Object
		this.x = x;
		this.y = y;
		this.first=false;
		this.last=false;
		this.direction=""
		this.width = tileWidth;
		this.char='';
		this.id=id;
		this.qns=-1;	
		this.intersected=false;//Each tile is given a unique id starting from 0, traversing each column before going to the next row	
	};
	
	function updateList(){
		if (answerSelected!="")
			header.innerHTML = 'Answer allocated successfully. Please select the next answer to allocate';
		else
			header.innerHTML = 'No answer selected!Please select one!';
		var index = questionList.indexOf(answerSelected);
		//console.log("Index is at"+index);
		if (index != -1){
			questionList.splice(index, 1);
			populate(questionList);
			if (questionList.length == 0)
				header.innerHTML = 'You have allocated all the answers. Click ee to submit the Puzzle';
		}
	}
	
	function getTitle(){
		var url2="includes/getTitleFromId.php?id="+crosswordId;
		console.log("getting title");
		jQuery.getJSON(url2, function (data) {
		    console.log("RECEIVED");
		    title = data[0]['PuzzleName'];
		    desc = data[0]['crosswordDescription'];
		    console.log("Title: "+title+","+"Description: "+desc);
		});
	}
	
	function save(){
		if (questionList.length!=0){
			window.alert("Not all answers have been allocated yet!");
			return ;
		}
		//console.log("MAP SAVED: "+answerMap["Why"]);
		var tiles_string="";
		var savedTiles=new Array();
		for (i=0;i<tiles.length;i++){
			if(tiles[i].char != '')
				savedTiles.push(tiles[i]);
		}
		for (i=0;i<savedTiles.length;i++){
			console.log("Saved IDS: ")
			console.log(savedTiles[i].id);
			if (savedTiles[i].id < 10)
				tiles_string+="00";
			else if (savedTiles[i].id < 100)
				tiles_string+="0";
			tiles_string+=savedTiles[i].id;
			tiles_string+=savedTiles[i].char;
			tiles_string+=',';
		}
		tiles_string=tiles_string.slice(0,-1);
		//console.log("Tiles String: "+tiles_string);
		var url="../phpretrieval/includes/updateTiles.php";
		for (i=0;i<answerList.length;i++){
			console.log("Saving tilecode "+answerMap[answerList[i]]);
			var jqxhr = $.post(url, { id: crosswordId, answer: answerList[i], tileCode: answerMap[answerList[i]], title: title, description: desc }, function () {
			    console.log("Save successfully");
			});
		 
		}alert('You have successfully saved the puzzle as ' + title);
	}
	
	function saveAs(){	//Save the Master template
		if (questionList.length!=0){
			window.alert("Not all answers have been allocated yet!");
			return ;
		}
		//console.log("MAP SAVED: "+answerMap["Why"]);
		title=prompt("Please enter the title of the puzzle.");
        	desc=prompt("Please enter the description of the puzzle.");
		var tiles_string="";
		var savedTiles=new Array();
		for (i=0;i<tiles.length;i++){
			if(tiles[i].char != '')
				savedTiles.push(tiles[i]);
		}
		for (i=0;i<savedTiles.length;i++){
			console.log("Saved IDS: ")
			console.log(savedTiles[i].id);
			if (savedTiles[i].id < 10)
				tiles_string+="00";
			else if (savedTiles[i].id < 100)
				tiles_string+="0";
			tiles_string+=savedTiles[i].id;
			tiles_string+=savedTiles[i].char;
			tiles_string+=',';
		}
		tiles_string=tiles_string.slice(0,-1);
		//console.log("Tiles String: "+tiles_string);
		var url2="../../crosswords/includes/duplicate.php";	
		var jqxhr = $.post(url2, { id: crosswordId, questions: regexInput }, function (data) {
			    var newID2=String(data);
			    newID2=newID2.substring(1,4);
			    newID2=parseInt(newID2);
			    var url="../phpretrieval/includes/updateTiles.php";
				for (i=0;i<answerList.length;i++){
					console.log("Saving tilecode "+answerMap[answerList[i]]);
					console.log("SAVING GAIA "+title+","+desc);
					var jqxhr = $.post(url, { id: newID2, answer: answerList[i], tileCode: answerMap[answerList[i]], title: title, description: desc }, function() 	{console.log("Save successfully");});
				}
		});
		 
		alert('You have successfully saved the puzzle as ' + title);
	}
	
	function posToTileID(x,y){		//Convert Mouse Click position to ID of the tile clicked
		var ID = (parseInt(y/tileCellWidth) * NUM_ROWS) + parseInt(x/tileCellWidth);
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
	
	function getPosition(e) {	 //Function called when a tile is clicked
		var scrollTop = $(window).scrollTop();
		var scrollRight = $(window).scrollLeft();
		//console.log("Scrolled "+scrollTop);
		mouseX = e.clientX+scrollRight-rect.left*0.96;
		mouseY = e.clientY+scrollTop-rect.top;
		console.log("X "+mouseX);
		console.log("Y"+mouseY);
		var tileSelected=posToTileID(mouseX,mouseY);
		console.log("Selected Tile: "+tileSelected);
		if(answerSelected == "")
			deleteWord(tileSelected);
		else{
			if(wordToTiles(answerSelected.replace(/\s/g,''),tileSelected)){
				console.log(answerSelected+"::"+currentEncodedID);
				ansStack.push(answerSelected);
				tileCodeStack.push(currentEncodedID);
				actionStack.push(0);
				console.log("Stack is now "+actionStack+"\n"+ansStack+"\n"+tileCodeStack);
				answerSelected="";	
			}
		}
  }
  
  	 function checkAdjacent(word,tileID){
  	 	if (inputDirection == "horizontal"){	//Check for adjaceny X
			for ( i=0; i < word.length ; i++){
				if (i==0 && (((tileID-1)%NUM_ROWS) != 0)){
					console.log("1st");
					var tile=tiles[tileID-1];
					if (tile != null)
						if(tile.char != '')
							return true;
				}
				else if (i==(word.length-1) && (((tileID+i+1)%NUM_COLS) != 0)){
					console.log("last");
					var tile=tiles[tileID+i+1];
					if (tile != null)
						if(tile.char != '')
							return true;
				}
				var ID1=tileID+i-NUM_COLS;
				var ID2=tileID+i+NUM_COLS;
				console.log("IDS are "+ID1+" and "+ID2);
				var tile1=tiles[ID1];
				var tile2=tiles[ID2];
				if (tile1 != null && tile2 != null)
					console.log("Detected tile "+tile1.char+" of direction "+ tile1.direction +",of last"+tile1.last+ " and "+tile2.char+" of direction "+tile2.direction+",of last "+tile2.last);
				if (tile1 != null)
					if (					
						(tile1.last && tile1.direction == "vertical") || (
						tile1.direction == "horizontal" && tileID>(NUM_COLS-1)
						)					
					)
						return true;
				if (tile2 != null)
					if (
						(tile2.first && tile2.direction == "vertical") || (
						 tile2.direction == "horizontal" && tileID<=(area-1-NUM_COLS)
						)
					)
						return true;				
			}					
		}
		 else if (inputDirection == "vertical"){	
			for ( i=0; i < word.length*NUM_ROWS ; i+=NUM_ROWS){	
				if (i==0 && tileID>(NUM_COLS-1)){
					console.log("1st");
					var tile=tiles[tileID-NUM_ROWS];
					if (tile != null)
						if(tile.char != '')
							return true;
				}
				else if (i==((word.length-1)*NUM_ROWS) && (tileID+i)<=(area-1-NUM_COLS)){
					var tile=tiles[tileID+i+NUM_ROWS];
					if (tile != null)
						if(tile.char != '')
							return true;
				}
				var ID1=tileID+i-1;
				var ID2=tileID+i+1;	
				var tile1=tiles[ID1];
				var tile2=tiles[ID2];
				if (tile1 != null && tile2 != null)
					console.log("Detected tile "+tile1.char+" of direction "+ tile1.direction + " and "+tile2.char+" of direction "+tile2.direction);
				if (tile1 != null)
					if (
						(tile1.last && tile1.direction == "horizontal") || (
						 tile1.direction == "vertical" && (tileID%NUM_ROWS) != 0)
					 )
						return true
				if (tile2 != null)
					if (
						(tile2.first && tile2.direction == "horizontal") || ( 
						tile2.direction == "vertical" && ((tileID+1)%NUM_ROWS) != 0)
					)
						return true;				
			}					
		}
		return false;
	}
  
	 function checkCollisionX(word,tileID){	//Check for collision of the same letters in the horizontal direction
		for ( i=0; i < word.length ; i++){
			var tile=tiles[tileID+i];
			//console.log(tile.char + "," + word.charAt(i));
			if (tile.char != '' && tile.char != word.charAt(i))
				return true;			
		}
		return false;
	}
	
	function checkCollisionY(word,tileID){ //Check for collision of the same letters in the vertical direction
		for ( i=0; i < word.length ; i++){
			var tile=tiles[tileID+i*NUM_ROWS];
			//console.log(tile.char + "," + word.charAt(i));
			if (tile.char != '' && tile.char != word.charAt(i))
				return true;			
		}
		return false;
	}
	
	function deleteWord(tileID){
		var tile=tiles[tileID];
		var ansToDelete=answerList[tile.qns];
		console.log("trying to delete "+tile.char);
		if (tile.char != ''){
			var r = confirm("Are you sure you want to delete "+ansToDelete+"?");
			if (r == true) {
				var tileCodeId=answerMap[ansToDelete];
				console.log("The map is "+tileCodeId);
				questionList.push(ansToDelete);
				populate(questionList);
				removeTiles(tileCodeId,ansToDelete.length);
				actionStack.push(1);
				ansStack.push(ansToDelete);
				tileCodeStack.push(tileCodeId);
			}
		}
	}
	
	function wordToTiles(word,tileID){	
		var bool=checkAdjacent(word,tileID);
		console.log(word+" failed the test: "+bool);
		if (bool && botMode){
			return false;
		}
		if (tileID<0 || tileID > (area-1))
			return false;
		var tileID1="";var tileID2="";
		var pos=new Array();
		pos=tileIDtoPos(tileID);
		//console.log("POS: "+pos);
		var length=word.length;
		//console.log("Length of word: "+length);
		if ( inputDirection == "horizontal" && length+pos[0]<=(NUM_ROWS) && !checkCollisionX(word,tileID)){
			for ( i=0; i < length ; i++){
				var tile=tiles[tileID+i];
				tile.qns=answerList.indexOf(answerSelected);
				tile.direction=inputDirection;
				if (i==0)
					tile.first=true;
				else if (i == (length-1))
					tile.last=true;
				//console.log("Current tile "+tile.char+" is at "+tile.x+","+tile.y);
				var len=tile.char.length;
				//console.log(len);
				if (tile.char.length > 0){
					tile.intersected=true;
					console.log("COLLISION DETECTED ON TILE "+tile.char);
				}
				if (i==0){
					if (tile.id < 10)
						tileID1+="000";
					else if (tile.id < 100)
						tileID1+="00";
					else if (tile.id < 1000)
						tileID1+="0";
					tileID1+=tile.id;
				}
				else if (i==length-1){
					if (tile.id < 10)
						tileID2+="000";
					else if (tile.id < 100)
						tileID2+="00";
					else if (tile.id < 1000)
						tileID2+="0";
					tileID2+=tile.id;
				}
				tile.char=word.charAt(i);
				tile.drawFaceDown();
			}			
			var encodedID=tileID1+tileID2;
			currentEncodedID=encodedID;
			//console.log("Encoded ID:"+encodedID);
			answerMap[answerSelected]=encodedID;
			updateList();
			//console.log("Stack now contains "+ansStack);
			return true;
		}
		if (inputDirection =="vertical" && length+pos[1]<=(NUM_COLS) && !checkCollisionY(word,tileID)){
			for ( i=0; i < length ; i++){
				var tile=tiles[tileID+i*NUM_ROWS];
				tile.qns=answerList.indexOf(answerSelected);
				tile.direction=inputDirection;
				if (i==0)
					tile.first=true;
				else if (i == (length-1))
					tile.last=true;
				//console.log("Current tile "+tile.char+" is at "+tile.x+","+tile.y);
				//console.log(tile.char.length);
				if (tile.char.length > 0){
					tile.intersected=true;
					//console.log("COLLISION DETECTED ON TILE "+tile.char);
				}
				if (i==0){
					if (tile.id < 10)
						tileID1+="000";
					else if (tile.id < 100)
						tileID1+="00";
					else if (tile.id < 1000)
						tileID1+="0";
					tileID1+=tile.id;
				}
				else if (i==length-1){
					if (tile.id < 10)
						tileID2+="000";
					else if (tile.id < 100)
						tileID2+="00";
					else if (tile.id < 1000)
						tileID1+="0";
					tileID2+=tile.id;
				}
				tile.char=word.charAt(i);
				tile.drawFaceDown();
			}						
			var encodedID=tileID1+tileID2;
			//console.log("Encoded ID:"+encodedID);
			currentEncodedID=encodedID;
			answerMap[answerSelected]=encodedID;
			updateList();
			//console.log("Stack now contains "+ansStack);
			return true;
		}
		if((length+pos[0]>(NUM_COLS) && inputDirection == "horizontal" )|| (length+pos[1]>(NUM_ROWS) && inputDirection == "vertical")){
			if (botMode == false)
				window.alert("Word is too long in the current direction! Select another tile!");
			return false;
			}
		else{
			if (botMode == false)
				window.alert("Collision detected!Select another tile!");
			return false;	
		}
	}

	Tile.prototype.drawFaceDown = function() { //Function to draw the tile
		ctx.beginPath();
		ctx.rect(this.x,this.y, this.width,this.width);
		ctx.fillStyle = "white";
		ctx.fill();
		ctx.fillStyle = "black";
		ctx.font = "bold "+0.6*tileCellWidth+"pt Courier";
		ctx.fillText(this.char,this.x+(tileWidth/4),(this.y+(tileWidth/1.5)),tileWidth);
	};
	
	function initTiles(){
		var counter=0;
		for (var i = 0; i < NUM_COLS; i++) {	//Initialise all the tiles
			for (var j = 0; j < NUM_ROWS; j++) {
				var tile =new Tile(j * tileCellWidth, i * tileCellWidth,counter);
				tile.drawFaceDown();
				tiles.push(tile);
				counter++;
			}
		}
	}
	
	function post(path, params, method) {
	    method = method || "post"; // Set method to post by default if not specified.
	
	    // The rest of this code assumes you are not using a library.
	    // It can be made less wordy if you use one.
	    var form = document.createElement("form");
	    form.setAttribute("method", method);
	    form.setAttribute("action", path);
	
	    for(var key in params) {
	        if(params.hasOwnProperty(key)) {
	            var hiddenField = document.createElement("input");
	            hiddenField.setAttribute("type", "hidden");
	            hiddenField.setAttribute("name", key);
	            hiddenField.setAttribute("value", params[key]);
	
	            form.appendChild(hiddenField);
	         }
	    }
	
	    document.body.appendChild(form);
	    form.submit();
	}
	
	getTitle();
	initTiles();
	getAnswers();

	