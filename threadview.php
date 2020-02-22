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
		<div>
			<form method="post">
			<input type = "text" id="threadcomment" name ="threadcomment" placeholder ="New Comment..." size = "50"><br>
			
			<!--image uploading-->
			<label for="img">Select image:</label>
  			<input type="file" id="img" name="img" accept="image/*">

  			<!--submit button-->
  			<div style = "right : 30px;">
			<input type="submit" value="Comment">
			</div>
			</form>
		</div>



		<div class="comments" id="existing">
			<a>by : <a href ="otherusers.php">placeholder user A</a>
			<p>Comments Comments Comments Comments Comments Comments Comments Comments Comments Comments Comments Comments Comments Comments Comments Comments Comments Comments Comments</p>
		</div>
		<div class ="commenttocomment" >
			<form action = "/threadview.php">
			<input type = "text" id="comment" name ="comment" placeholder ="New Comment..." size = "50"><br>
			<!--image uploading-->
			<label for="img">Select image:</label>
  			<input type="file" id="img" name="img" accept="image/*">

  			<!--submit button-->
			<input type="submit" value="Comment">
			</form>
		</div>

		<div class="comments" id="existing">
			<a>by : <a href ="otherusers.php">placeholder user B</a>
			<p>Comments Comments Comments Comments Comments Comments Comments Comments Comments Comments Comments Comments Comments Comments Comments Comments Comments Comments Comments</p>
		</div>
		<div class ="commenttocomment" >
			<form action = "/threadview.php">
			<input type = "text" id="comment" name ="comment" placeholder ="New Comment..." size = "50"><br>
			<!--image uploading-->
			<label for="img">Select image:</label>
  			<input type="file" id="img" name="img" accept="image/*">

  			<!--submit button-->
			<input type="submit" value="Comment">
			</form>
		</div>

		<div class="comments" id="existing">
			<a>by : <a href ="otherusers.php">placeholder user C</a>
			<p>Comments Comments Comments Comments Comments Comments Comments Comments Comments Comments Comments Comments Comments Comments Comments Comments Comments Comments Comments</p>
		</div>
		<div class ="commenttocomment" >
			<form action = "/threadview.php">
			<input type = "text" id="comment" name ="comment" placeholder ="New Comment..." size = "50"><br>
			<!--image uploading-->
			<label for="img">Select image:</label>
  			<input type="file" id="img" name="img" accept="image/*">

  			<!--submit button-->
			<input type="submit" value="Comment">
			</form>
		</div>
	</section>
	
<?php

if(isset($_POST["threadcomment"])){
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
	$userID = $_SESSION["userID"];
	$content = $_POST["threadcomment"];
	
	//generate current time
	$result = mysqli_query($conn, "SELECT CURRENT_TIMESTAMP()");
	$time = mysqli_fetch_assoc($result)['CURRENT_TIMESTAMP()'];
	
	//add to comment table
	$AddQuery = "INSERT INTO comment (postID, userID, text, commentTime)
				 VALUES ('$postID', '$userID', '$content', '$time')";
	
	if (mysqli_query($conn, $AddQuery)) {
		$sql = "SELECT commentID FROM comment WHERE userID='$userID' AND commentTime='$time'";
		$result = mysqli_query($conn, $sql);
		$commentID = mysqli_fetch_assoc($result)['commentID'];
		
		//add to post_comment table
		$AddQuery = "INSERT INTO post_comment (postID, commentID)
					 VALUES ('$postID', '$commentID')";
		if (mysqli_query($conn, $AddQuery)) {
			echo '<meta http-equiv="Refresh" content="0; url=threadview.php?postID=' . $postID . '" />';
		} else {
			echo "Error: " . $AddQuery . "<br>" . mysqli_error($conn);
		}
		
	} else {
		echo "Error: " . $AddQuery . "<br>" . mysqli_error($conn);
	}
	
	mysqli_close($conn);
}
?>
	
</body>
</html>