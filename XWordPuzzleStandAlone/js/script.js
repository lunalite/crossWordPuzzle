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
	var Tilepadding=5;
	var tileWidth=tileCellWidth-Tilepadding;
	var mouseX=0;
	var mouseY=0;
	
	window.alert("Welcome to the Master Template! Start by clicking a tile and enter the word desired. The Master Template will first attempt to fill by row if possible. Otherwise, it tries to fill by column instead!");
	
	var Tile = function(x, y,id) { //Class Declaration for Tile Object
		this.x = x;
		this.y = y;
		this.width = tileWidth;
		this.char='';
		this.id=id;		//Each tile is given a unique id starting from 0, traversing each column before going to the next row	
	};
	
	function save(){	//Save the Master template
		var title=prompt("Please enter the title of the puzzle");
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
		console.log("Tiles String: "+tiles_string);
		var url="http://lewspage.hostei.com/xword_php/saveXword.php?name="+title+"&tiles="+tiles_string;
		var jqxhr = jQuery.get( url, function() {
				window.alert("Save successfully!");
		})
		// Insert code here to save tiles into database
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
		var word=prompt("Please enter the word",tiles[tileSelected].char);
		console.log("Word Entered: "+word);
		wordToTiles(word,tileSelected);
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
		ctx.fillStyle = "white";
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
			console.log("Tile "+i+","+j+" : "+tiles[counter].x+","+tiles[counter].y);
			counter++;
		}
	}