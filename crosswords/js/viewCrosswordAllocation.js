var pixelSize=10;
	var pixelSizeY=20;
	var c = document.getElementById("myCanvas");
	var rect = c.getBoundingClientRect();
	c.addEventListener("click", getPosition,false);
	var ctx = c.getContext("2d");
	var screenWidth=window.innerWidth;
	var screenHeight=window.innerHeight;
	var pixelSizeX=screenWidth/153;
	console.log("dx is "+pixelSizeX);
	c.width = screenWidth*0.75;
        c.height = screenHeight;
	console.log("Screen size is "+screenWidth+" x "+screenHeight);
	var NUM_COLS = 30;
	var NUM_ROWS = 30;
	var tileCellWidth=c.width/NUM_ROWS;
	if (c.height<c.width)
        	c.height=tileCellWidth*NUM_ROWS;
	var Tilepadding=1;
	var tileWidth=tileCellWidth-Tilepadding;
	var mouseX=0;
	var mouseY=0;
	var tiles = [];
	var questionList=[];
	var answerList=[];
	var tileCodeList=[];
	var noOfFields=6;
	var attempts = [];    
    	var answered = []; // 0 - not answered / answered wrongly 1 - answered correctly
	var noOfQuestions=0;
	var d = new Date();
	var startTime=d.getTime();
	//var audio = new Audio('music.wav');
	//audio.play();
	var title="Accounting";
	//sessionStorage.removeItem('answered');
	//sessionStorage.removeItem('attempts');
	var bonus=0;
	console.log("Received ID"+crosswordId);
	

// function to store information into session
	
	var Tile = function(x, y,id,c,qns_id) { //Class Declaration for Tile Object
		this.x = x;
		this.y = y;
		this.width = tileWidth;
		this.char=c;
		this.id=id;
		this.qns_id=qns_id;
		this.intersected=false;
		this.master=false;
		//Each tile is given a unique id starting from 0, traversing each column before going to the next row	
	};
	
	function getTiles(){
		    var url = "../phpretrieval/includes/qnOutput.php";
		    jQuery.getJSON(url, { crosswordId: crosswordId }, function (data) {		        
		        var arr = jQuery.map(data, function (e1) { return e1; });
		        var size = arr.length;
		        console.log("Array is of size " + size);

                // Recalling storage for number of attempts / answered qns in case of refresh
		        for (i = 0; i < size; i++) {
		            questionList.push(data[i]['Question']);
                    word = data[i]['Answer'];
                    word=word.replace(/\s/g,'');
		            answerList.push(word);
		            tileCodeList.push(data[i]['TileCode']);
		        }
		        console.log("Questions: " + questionList);
		        console.log("Answers: " + answerList);
		        console.log("Codes: " + tileCodeList);
		        console.log(answerList.length);
                
		        for (i = 0; i < answerList.length; i++) {
		            console.log("Now filling the " + i + "th answer");
					noOfQuestions++;
		            createTilesFromString(tileCodeList[i], answerList[i]);
		        }			
				DrawTiles();		
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

	 function DrawTiles(){
		for (b=0;b<tiles.length;b++){
			var tile = tiles[b] ;
			tile.drawFaceDown();
			tile.drawAns();
			if(tile.master)
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
					console.log("Now at Tile "+ID1+",filling with char "+ ans.charAt(j));
					if (containsTile(ID1,tiles)){
						console.log("Collision detected on Tile: "+ans.charAt(j));
						var tile=getTileFromId(ID1);
						tile.intersected=true;
						if (j==0){
							tile.master=true;
							tile.qns_id=i;
							//tile.drawFaceDown();
							}
						if (diff>=NUM_ROWS) //Go Down
							ID1+=NUM_ROWS;
						else if (diff>0) //Go Right
							ID1++;
						else if (diff<=-NUM_ROWS)
							ID1+=NUM_ROWS;
						else if (diff < 0)
							ID1--;
						continue;
					}
					var pos = tileIDtoPos(ID1);
					var tile =new Tile(pos[0]*tileCellWidth,pos[1]*tileCellWidth,ID1,ans.charAt(j),i);
					if (j==0)
						tile.master=true;
					tile.char = ans.charAt(j);
					//console.log("Inserting character "+ans.charAt(j));
					//tile.drawFaceDown();
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
		var ID = (parseInt((y)/tileCellWidth) * NUM_ROWS) + parseInt((x)/tileCellWidth);
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
		var scrollRight = $(window).scrollLeft();//Function called when a tile is clicked
		mouseX = e.clientX+scrollRight-rect.left;
		mouseY = e.clientY+scrollTop-rect.top;
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
        // If question is unanswered or 1 attempt remains, do this
            alertify.alert(question);
	}
  
		function getTileFromId(tileID){
			for (z=0;z<tiles.length;z++){
			  var tile=tiles[z];
			  if(tile.id == tileID)
				  return tile;
			}
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
		ctx.fillStyle = "white";
		ctx.fill();
	};
	
	Tile.prototype.drawQnsNo = function() {
		ctx.beginPath();
		ctx.fillStyle = "black";
		var size=tileCellWidth/4;
		ctx.font=size+"pt Arial";
		ctx.fillText((this.qns_id+1),this.x,(this.y+(tileWidth)),tileWidth);
		ctx.fill();
	}
	
	Tile.prototype.drawAns = function() { //Function to draw the tile
		ctx.beginPath();
		ctx.fillStyle = "black";
		var scale =tileCellWidth/2;
		ctx.font=scale+"pt Arial";
		ctx.fillText(this.char,this.x+(tileWidth/4),(this.y+(tileWidth/1.5)),tileCellWidth);
		ctx.fill();

	};
	
	getTiles();