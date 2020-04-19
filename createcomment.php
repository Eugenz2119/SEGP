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
	<!--<?php include 'cropinfo.php';?>-->
	
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
	$dbUsername 	= "hcyko1_admin";
	$dbPassword 	= "3QXBfTmKAccZ0BNO";
	$dbname 	= "hcyko1_agritalk";

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

	if($replyType == "comment"){
		$sql = "SELECT userID, text FROM comment WHERE commentID=" . $commentID;
		$result = mysqli_query($conn, $sql);
		$row = mysqli_fetch_assoc($result);
		$quoteuserID = $row['userID'];
		$txt = $row['text'];
		$sql = "SELECT username FROM user WHERE userID=" . $quoteuserID;
		$result = mysqli_query($conn, $sql);
		$row = mysqli_fetch_assoc($result);
		$quoteusername = $row['username'];
		
		//remove quoted text from the comment being replied to
		if(strrpos($txt, "[/QUOTE]") != FALSE){
			$quotedText = substr($txt, strrpos($txt, "[/QUOTE]") + strlen("[/QUOTE]") +1); //+2 for \n
		}
		else{
			$quotedText = $txt;
		}
		
		$prefix = "[QUOTE]by : " . $quoteusername . "\n" . $quotedText . "[/QUOTE]\n";
		
		echo '
		<!--div for quoted comment-->
		<div class="quote" style = "width: 60%; height: 20%; background-color: white;">
			<a>' . $quotedText . '</a>
		</div>
		';
	}

	mysqli_close($conn);
	?>
	
	<!--comment creation code-->
	<div>
		<form method="post">
			<?php
			echo '
			<!--text field-->
			<textarea name="threadcomment" placeholder="New Comment..." rows=6 cols=200></textarea>
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
	$dbUsername 	= "hcyko1_admin";
	$dbPassword 	= "3QXBfTmKAccZ0BNO";
	$dbname 	= "hcyko1_agritalk";

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
	
	//new text entered by user
	$commText = $_POST["threadcomment"];
	
	$content = $prefix . $commText;
	
	//remove errors from inverted comma(s)
	$content = str_replace("'", "\'", $commText);

	if(strlen($commText) > 0){
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
				//increment comment count
				$sql = 'SELECT commentCount FROM post WHERE postID=' . $postID;
				$result = mysqli_query($conn, $sql);
				$commentCount = mysqli_fetch_assoc($result)['commentCount'];
				$sql = 'UPDATE post SET commentCount=' . ($commentCount + 1) . ' WHERE postID=' . $postID;
				mysqli_query($conn, $sql);
				
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