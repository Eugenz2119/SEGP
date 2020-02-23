<!DOCTYPE html>
<html>
<head>
	<link href="./resources/css/useracc.css" type="text/css" rel ="stylesheet">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>AgriTalk</title>
	<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
</head>
<body>
	<?php include "header.php"; ?>

	<!-- user's profile avatar -->
	<img src = "resources/placeholderimage.jpg" alt= "user avatar" class="center" style="width: 250px; height:250px; border-style: solid;">


	<!--account management -->
	<div id="accountmanagement"class="w3-container w3-padding-small w3-round-small" style="width: 250px;border-style: solid; text-align: center;">
		<a href="login.php">logout</a><br>
		<a href="#">change password</a><br>
		<a href="#">change avatar</a>
	</div>



</body>
</html>