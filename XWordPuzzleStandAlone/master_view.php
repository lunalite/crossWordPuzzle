<?php
$id=$_GET["id"];
?>

<html>
<head>
<h1>Game Over</h1>
<h2>Scores</h2>
<style>
	h1{
	}
	.ulist,.slist{
		float: left;
	}
</style>
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
</head>
<body>
	<div class="ulist"><ul id="userList" style="list-style-type:none"></ul></div>
	<div class="slist"><ul id="scoreList" style="list-style-type:none"></ul></div>
	<script>
	var sessId = "<?php echo $id ?>";
	var user_list = document.getElementById("userList");
	var score_list = document.getElementById("scoreList");
	var userList=[];
	var scoreList=[];
	function stateChange() {
		setTimeout(function () {
		retrieveScores();
		}, 5000);
	}
	function retrieveScores(){
		while (user_list.firstChild) {
			user_list.removeChild(user_list.firstChild);
		}
		while (score_list.firstChild) {
			score_list.removeChild(score_list.firstChild);
		}
			var url = "../phpretrieval/includes/getAllScoresFromId.php?id="+sessId;
			jQuery.getJSON(url, function (data) {
					console.log("Received object "+data);
					for (x=0;x<data.length;x++){
						var userId=data[x][1];
						scoreList.push(data[x][0]);
						var entry = document.createElement('li');
						entry.appendChild(document.createTextNode(data[x][0]));
						score_list.appendChild(entry);
						console.log("user id "+userId);
						var url = "../phpretrieval/includes/getNameFromUserId.php?id="+userId;
						jQuery.getJSON(url, function (data2) {
							console.log("received "+data2);
							userList.push(data2);
							var entry = document.createElement('li');
							entry.appendChild(document.createTextNode(data2));
							user_list.appendChild(entry);
						});
					}
		});
		stateChange();
	}
	
	retrieveScores();
	console.log("in script...");

</script>
<a href="../index.php">Click here to go back</a>
</body>
</html>