<!DOCTYPE html>
<html>
<head>
	<link href="./resources/css/threadcreate.css" type="text/css" rel ="stylesheet">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>AgriTalk</title>
	<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
</head>

<body style="background-color: #E1E1E1";>
	<?php include 'header.php';?>
	<?php include 'cropinfo.php';?>
	
	<?php
	//login check
	if(isset($_SESSION["userID"])){
		$userID = $_SESSION["userID"];
	}
	else{
		echo '<meta http-equiv="Refresh" content="0; url=login.php" />';
	}

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
	
	$postID = $_GET['postID'];

	//determine reply to thread or comment
	if(isset($_GET['postReplyID'])){
		echo "reply post";
		$replyType = "post";
	}

	if(isset($_GET['commentReplyID'])){
		echo "reply comment";
		$replyType = "comment";
		$commentID = $_GET['commentReplyID'];
	}

	if($replyType == "post"){
		$defaultText = "";
	}
	else{//replyType == "comment"	
		$sql = "SELECT text FROM comment WHERE commentID=" . $commentID;
		$result = mysqli_query($conn, $sql);
		$quotedText = mysqli_fetch_assoc($result)['text'];
		
		$defaultText = "[QUOTE]" . $quotedText . "[/QUOTE]\n";
	}

	mysqli_close($conn);
	?>
	
	<!--comment creation code-->
	<div>
		<form method="post">
			<?php
			echo '
			<!--text field-->
			<textarea name="threadcomment" placeholder="New Comment...">' . $defaultText . '</textarea>
			';
			?>
			
			<!--submit button-->
			<div style = "right : 30px;">
			<input type="submit" value="Comment">
			</div>
		</form>
	</div>	

<?php
//creating new comment
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
		//echo "DB CONNECTED";
	}
	
	$content = $_POST["threadcomment"];
	
	if(strlen($content) > 0){
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
	}
	else{
		echo '
		<script language="javascript">
			alert("Comment is empty")
		</script>
		';
	}
	mysqli_close($conn);
}
?>
</body>
</html>