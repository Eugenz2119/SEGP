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

	<?php
	//Connection details
	$servername = "localhost";
	$dbUsername 	= "hcyko1";
	$dbPassword 	= "3QXBfTmKAccZ0BNO";
	$dbname 	= "agritalk-wip";

	// Create connection
	$conn = mysqli_connect($servername, $dbUsername, $dbPassword, $dbname);
	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}
	//debug
	else{
		//echo "DB CONNECTED";
	}

	//login check
	if(isset($_SESSION["userID"])){
		$userID = $_SESSION["userID"];
	}
	else{
		echo '<meta http-equiv="Refresh" content="0; url=login.php" />';
	}
	
	//profile picture
	$sql="SELECT imageID FROM user WHERE userID =" . $userID;
	$result = mysqli_query($conn, $sql);
	$imageID = mysqli_fetch_assoc($result)['imageID'];
	if($imageID != NULL){
		$sql="SELECT format FROM image WHERE imageID =" . $imageID;
		$result = mysqli_query($conn, $sql);
		$imageFormat = mysqli_fetch_assoc($result)['format'];
		$image_dir = "uploads/" . $imageID . '.' . $imageFormat;
	
		echo '
		<img src = "' . $image_dir . '" alt="user avatar" class="center" style="width: 250px; height:250px; border-style: solid;">
		';
	}
	else{
		echo '
		<img src = "resources/placeholderimage.jpg" alt="user avatar" class="center" style="width: 250px; height:250px; border-style: solid;">
		';
	}
	?>

	<!--account management -->
	<div id="accountmanagement"class="w3-container w3-padding-small w3-round-small" style="width: 250px;border-style: solid; text-align: center;">
		<a href="login.php">logout</a><br>
		<a href="#">change password</a><br>
		<a href="#">change avatar</a>
	</div>



</body>
</html>