<!DOCTYPE html>
<html>
<head>
	<link href="./resources/css/index.css" type="text/css" rel ="stylesheet">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>AgriTalk</title>
	<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
</head>
<body>
	<?php include "header.php"; ?>

	<div id = "threadpreview" class ="w3-panel w3-border w3-round-small w3-padding-large"style ="width:65%">
	<h1>placeholder thread title</h1>
	<h6>by : placeholder user</h6>
	<p> paragraph paragraph paragraph paragraph paragraph paragraph paragraph paragraph paragraph paragraph.paragraph paragraph paragraph paragraph paragraph paragraph paragraph paragraph paragraph paragraph paragraph paragraph </p>
	<a href="threadview.php">Read more...</a>
</div>

	<!--account management -->
	<div id="accountmanagement"class="w3-container w3-padding-small w3-round-small" style="position: absolute; top: 90px; right:-10px; width: 250px; height: 100px ; border-style: solid;">
		<a href="login.php">logout</a><br>
		<a href="#">change password</a><br>
		<a href="#">change avatar</a>
	</div>

	<!-- user's profile avatar -->
	<img src = "resources/placeholderimage.jpg" alt= "user avatar" style="position: absolute; width: 250px; height:250px; right:-10px;top: 190px; border-style: solid;">


</body>
</html>