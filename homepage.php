<!DOCTYPE html>
<html>
<?php 
 include 'header.php'
?>
<head>
	<link href="./resources/css/index.css" type="text/css" rel ="stylesheet">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>AgriTalk</title>
	<script src="https://code.jquery.com/jquery-1.10.2.js"></script>

</head>
<body>
	

<div id = "threadpreview"class ="w3-panel w3-border w3-round-small w3-padding-large" style="width:60%" >
	<h1>placeholder thread title</h1>
	<h6>by : placeholder user</h6>
	<p> paragraph paragraph paragraph paragraph paragraph paragraph paragraph paragraph paragraph paragraph.paragraph paragraph paragraph paragraph paragraph paragraph paragraph paragraph paragraph paragraph paragraph paragraph </p>
	<a href="threadview.php">Read more...</a>
</div>

<!-- debug -->
userID: <?php session_start(); echo $_SESSION["userID"];?> <br>

</body>
</html>

