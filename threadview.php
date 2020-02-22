<!DOCTYPE html>
<html>
<head>
	<link href="./resources/css/threadview.css" type="text/css" rel ="stylesheet">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>AgriTalk</title>
	<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
</head>

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
		echo "DB CONNECTED";
	}

	session_start();
	
	$postID = $_GET["postID"];
	
	$sql = "SELECT title, text FROM post WHERE postID='$postID'";
	$result = mysqli_query($conn, $sql);
	
	$post = mysqli_fetch_assoc($result);
	$title = $post['title'];
	$content = $post['text'];
	
	mysqli_close($conn);
	
?>

<body>
	<?php include 'header.php';?>
	<?php include 'cropinfo.php';?>

	<header id="threadtitle">
	<h1><?php echo $title;?></h1>
	</header>
	
	<div id="threadcontents" style="width:70%">
		<p style="padding-left: 40px;"><?php echo $content;?></p>
		<button id="sharebutton">Share</button>
	</div>
	
	

	<section id="threadcomments" style="width:70%">
		<div 
			<form action = "/threadview.php">
		<input type = "text" id="threadcomment" name ="threadcomment" placeholder ="New Comment..." size = "50"><br>
		<label for="img">Select image:</label>
  		<input type="file" id="img" name="img" accept="image/*">
		<input type="submit" value="Submit">
		</div>


		<div class="comments" id="existing">
			<p>Existing Comments</p>
		</div>
	</section>
	



</body>
</html>