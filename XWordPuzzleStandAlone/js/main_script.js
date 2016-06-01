	var pixelSize=10;
	var c = document.getElementById("myCanvas");
	c.addEventListener("click", getPosition,false);
	var ctx = c.getContext("2d");
	var screenWidth=window.innerWidth;
	var screenHeight=window.innerHeight;
	c.width = screenWidth;
    c.height = screenHeight;
	var NUM_COLS = 20;
	var NUM_ROWS = 20;
	var tileCellWidth=screenHeight/NUM_ROWS;
	var Tilepadding=0;
	var tileWidth=tileCellWidth-Tilepadding;
	var mouseX=0;
	var mouseY=0;
	var tiles = [];
	var questionList=[];
	var answerList=[];
	var tileCodeList=[];
	var title=prompt("Please enter the title of the puzzle");
	var noOfFields=6;
	
	var Tile = function(x, y,id,char) { //Class Declaration for Tile Object
		this.x = x;
		this.y = y;
		this.width = tileWidth;
		this.char=char;
		this.id=id;		//Each tile is given a unique id starting from 0, traversing each column before going to the next row	
	};
	
	function getTiles(){
		console.log("Getting data");
		var url="../phpretrieval/includes/getIdFromName.php";
		jQuery.getJSON(url, {title:title}, function(data) {
        // ... handle response as above
			var str = JSON.stringify(data);
			console.log(str);
			var crosswordID=getIDfromStr(str);
			console.log("ID is "+crosswordID);
			var url="../phpretrieval/includes/qnOutput.php";
			jQuery.getJSON(url, {crosswordId:crosswordID}, function(data) {
				 str = JSON.stringify(data);
				 console.log(str);
				 var arr=jQuery.map(data,function(e1){return e1;});
				 var size=arr.length/noOfFields;
				 console.log("Array is of size "+size);
				for (i=0;i<size;i++){
					questionList.push(arr[3+i*noOfFields]);
					answerList.push(arr[4+i*noOfFields]);
					tileCodeList.push(arr[5+i*noOfFields]);
				}		
				console.log("Questions: "+questionList);	
				console.log("Answers: "+answerList);	
				console.log("Codes: "+tileCodeList);
				console.log(answerList.length);
				for (i=0;i<answerList.length;i++){
					console.log("Now filling the "+i+"th answer");
					createTilesFromString(tileCodeList[i],answerList[i]);
				}
			});
		});	
     }
	 
	 function getIDfromStr(str){
		 var s,e,counter;
		 counter=0;
		 for (i=0;i<str.length;i++){
			 if (str.charAt(i)=='"')
			 {s=i;console.log(s);counter++;}
			 else if(str.charAt(i)=='"' && counter ==1){
				 e=i;
			 }
		 }
		 console.log(s);
		 console.log(e);
		 console.log(str.substring(s+1,e))
			return str.substring(3,5);
	 }
	
	function createTilesFromString(str,ans){
		var ID1= str.substring(0,3);
		var ID2= str.substring(3,6);
		ID1=parseInt(ID1);
		ID2=parseInt(ID2);
		console.log("Decoding "+ID1+" and "+ID2+" for "+ans);
		var diff=ID2-ID1;
		console.log("Difference is "+diff);
		console.log("Length of word is "+ans.length);
				for(j=0;j<ans.length;j++){
					console.log("Now at Tile "+ID1);
					var pos = tileIDtoPos(ID1);
					var tile =new Tile(pos[0]*tileCellWidth,pos[1]*tileCellWidth,ID1,ans.charAt(j));
					console.log("Inserting character "+ans.charAt(j));
					tile.drawFaceDown();
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
	
	function getPosition(e) {		//Function called when a tile is clicked
		mouseX = e.clientX;
		mouseY = e.clientY;
		console.log("X "+mouseX);
		console.log("Y"+mouseY);
		var tileSelected=posToTileID(mouseX,mouseY);
		console.log("Selected Tile: "+tileSelected);
		var word=prompt("Please enter your answer");
		
  }
  
	 function checkCollisionX(word,tileID){	//Check for collision of the same letters in the horizontal direction
		for ( i=0; i < word.length ; i++){
			var tile=tiles[tileID+i];
			console.log(tile.char + "," + word.charAt(i));
			if (tile.char != '' && tile.char != word.charAt(i))
				return true;			
		}
		return false;
	}
	
	function checkCollisionY(word,tileID){ //Check for collision of the same letters in the vertical direction
		for ( i=0; i < word.length ; i++){
			var tile=tiles[tileID+i*NUM_ROWS];
			console.log(tile.char + "," + word.charAt(i));
			if (tile.char != '' && tile.char != word.charAt(i))
				return true;			
		}
		return false;
	}
	
	function wordToTiles(word,tileID){	//Insert a word entered by the user into the appropriate tiles
		var pos=new Array();
		pos=tileIDtoPos(tileID);
		console.log("POS: "+pos);
		var length=word.length;
		console.log("Length of word: "+length);
		if (length+pos[0]<=(NUM_ROWS) && !checkCollisionX(word,tileID)){
			for ( i=0; i < length ; i++){
				var tile=tiles[tileID+i];
				console.log("Current tile is at "+tile.x+","+tile.y);
				tile.char=word.charAt(i);
				tile.drawFaceDown();
			}
			return ;
		}
		if (length+pos[1]<=(NUM_COLS) && !checkCollisionY(word,tileID)){
			for ( i=0; i < length ; i++){
				var tile=tiles[tileID+i*NUM_ROWS];
				console.log("Current tile is at "+tile.x+","+tile.y);
				tile.char=word.charAt(i);
				tile.drawFaceDown();
			}
			return ;
		}
		if(length+pos[1]>(NUM_COLS) && length+pos[0]>(NUM_ROWS))
			window.alert("Word is too long!");
		else
			window.alert("Collision detected in both row and column!");
	}

	Tile.prototype.drawFaceDown = function() { //Function to draw the tile
		ctx.beginPath();
		ctx.rect(this.x,this.y, this.width,this.width);
		ctx.fillStyle = "grey";
		ctx.fill();
	};
	
	getTiles();