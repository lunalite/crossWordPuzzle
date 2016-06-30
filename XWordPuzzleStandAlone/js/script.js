	var pixelSize=10;
	var c = document.getElementById("myCanvas");
	var rect = c.getBoundingClientRect();
	c.addEventListener("click", getPosition,false);
	var ctx = c.getContext("2d");
	var screenWidth=window.innerWidth;
	var screenHeight=window.innerHeight;
	c.width = screenWidth*0.6;
    	c.height = c.width;
	var NUM_COLS = 30;
	var NUM_ROWS = 30;
	if (c.width < c.height)
		var tileCellWidth=c.width/NUM_COLS;
	else
		var tileCellWidth=c.height/NUM_COLS;
	var Tilepadding=1;
	var tileWidth=tileCellWidth-Tilepadding;
	var mouseX=0;
	var mouseY=0;
	var answerSelected="";
	var currentEncodedID="";
	var answerMap = {};
	var inputDirection="horizontal";
	var header=document.getElementById('instructions');
	var noOfFields=6;
	// add a item
        console.log("Latest id is "+crosswordId);
	
	var overall_list = document.getElementById("questionList");	
	var questionList = [];
	var answerList=[];
	var ansStack=[];
	var tileCodeStack=[];
	var actionStack=[];
	
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

    function getAnswers(){
		console.log("Getting data");
		var url="../../phpretrieval/includes/qnOutput.php";
		jQuery.getJSON(url, { crosswordId: crosswordId }, function (data) {
		    // ... handle response as above

		    var arr = jQuery.map(data, function (e1) { return e1; });
		    var size = arr.length;
		    console.log("Array is of size " + size);
		    for (f = 0; f < size; f++){
		    	var tileCode=arr[f]['TileCode'];
		    	console.log("code is "+tileCode);
		    	var ans=arr[f]['Answer'];
		    	ans=ans.replace(/\s/g,'');
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
				wordToTiles(ans.replace(/\s/g,''),tileID1);
				inputDirection=temp;
				var index=questionList.indexOf(ans);
				questionList.splice(index, 1);
		        }
		    }
		    console.log("Answer list formed: " + questionList);
		    answerSelected="";
		    populate(questionList);
		});	
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

    	getAnswers();
	
	var Tile = function(x, y,id) { //Class Declaration for Tile Object
		this.x = x;
		this.y = y;
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
				header.innerHTML = 'You have allocated all the answers. Click Save to submit the Puzzle';
		}
	}
	
	function save(){	//Save the Master template
		if (questionList.length!=0){
			window.alert("Not all answers have been allocated yet!");
			return ;
		}
		//console.log("MAP SAVED: "+answerMap["Why"]);
		var title=prompt("Please enter the title of the puzzle.");
        	var desc=prompt("Please enter the description of the puzzle.");
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
		 
	}alert('You have successfully saved the puzzle as ' + title);}
	
	function posToTileID(x,y){		//Convert Mouse Click position to ID of the tile clicked
		var ID = (parseInt((y-2*pixelSize)/tileCellWidth) * NUM_ROWS) + parseInt((x-pixelSize)/tileCellWidth);
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
		var scrollTop = $(window).scrollTop();
		var scrollRight = $(window).scrollLeft();
		//console.log("Scrolled "+scrollTop);//Function called when a tile is clicked
		mouseX = e.clientX-rect.left+tileCellWidth/2;;
		mouseY = e.clientY+scrollTop-rect.top+tileCellWidth/2;
		//console.log("X "+mouseX);
		//console.log("Y"+mouseY);
		var tileSelected=posToTileID(mouseX,mouseY);
		//console.log("Selected Tile: "+tileSelected);
		//console.log("Inserting word "+answerSelected);
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
	
	function wordToTiles(word,tileID){		//Insert a word entered by the user into the appropriate tiles
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
			window.alert("Word is too long in the current direction! Select another tile!");
			return false;
			}
		else{
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
		ctx.font = "bold 10pt Courier";
		ctx.fillText(this.char,this.x+(tileWidth/2),(this.y+(tileWidth/2)),tileWidth);
	};
	
	var tiles = [];
	var counter=0;
	for (var i = 0; i < NUM_COLS; i++) {	//Initialise all the tiles
		for (var j = 0; j < NUM_ROWS; j++) {
			var tile =new Tile(j * tileCellWidth, i * tileCellWidth,counter);
			tile.drawFaceDown();
			tiles.push(tile);
			counter++;
		}
	}
	
	counter=0;
	for (var i = 0; i < NUM_COLS; i++) {	//Debugging purposes
		for (var j = 0; j < NUM_ROWS; j++) {
			//console.log("Tile "+i+","+j+" : "+tiles[counter].x+","+tiles[counter].y);
			counter++;
		}
	}	
					
