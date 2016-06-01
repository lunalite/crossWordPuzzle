<?php
$id=$_GET["id"];
echo $id;
?>
 
 
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
<div id="instructions">Start by clicking on an answer from the list. You can change the direction to allocate the answers below.</div>
  <title></title>
  <style>
.canvas,.mylist{
    float: left;
}
.radio{
  clear:both;
}
.submit{
  clear:both;
}
</style>
</head>
<body>
 
<div class="canvas">
<canvas id="myCanvas" style="border:1px solid #d3d3d3;">
Your browser does not support the HTML5 canvas tag.</canvas>
</div>
 
<div class="mylist">
<ul id="questionList"></ul>
</div>
 
<div class="radio">
<form name="myform" action="">
  <input type="radio" checked="checked" name="direction" value="horizontal">Horizontal<br>
  <input type="radio" name="direction" value="vertical">Vertical
</form>
</div>
 
<script type="text/javascript">
var crosswordId = "<?php echo $id ?>";
</script> 
 
<script language="javascript" type="text/javascript" src="js/script.js">
</script> 
 
<div class="submit">
<button onClick="save()">Save</button>
</div>
 
</body>
</html>