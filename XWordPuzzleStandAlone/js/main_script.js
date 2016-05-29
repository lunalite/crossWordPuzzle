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
	var title=prompt("Please enter the title of the puzzle");
	
	var Tile = function(x, y,id,char) { //Class Declaration for Tile Object
		this.x = x;
		this.y = y;
		this.width = tileWidth;
		this.char=char;
		this.id=id;		//Each tile is given a unique id starting from 0, traversing each column before going to the next row	
	};
	
	function getTiles(){
		console.log("Getting data");
		var url="http://lewspage.hostei.com/xword_php/getXword.php";
		jQuery.getJSON(url, {name:title}, function(data) {
        // ... handle response as above
			console.log("Data is "+data);
			var str = JSON.stringify(data);
			console.log("String is "+str);
			var index=str.search("tiles")+8;
			var end=str.search("}]}")-1;
			var tilesString=str.substring(index,end);
			console.log("Final String is "+tilesString);
			createTilesFromString(tilesString);
			console.log("Tiles are now "+tiles);
		});	
     }
	
	function createTilesFromString(str){
		var c='';
		var i =0;
		c=str.charAt(i);
		var length=str.length;
		while ( i < length ){
			var tileID = str.substring(i,i+3);
			var char =str.charAt(i+3);
			var pos = tileIDtoPos(tileID);
			var tile =new Tile(pos[0]*tileCellWidth,pos[1]*tileCellWidth,i/5,char);
			console.log("Created Tile "+ tile.id +" : "+tile.char+" at position " + pos);
			tile.drawFaceDown();
			tiles.push(tile);
			i+=5;
			c=str.charAt(i);
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