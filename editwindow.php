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

	//determine editing post or comment
	if(isset($_GET['postID'])){
		echo "edit post";
		$replyType = "post";
		$postID = $_GET['postID'];
	}

	if(isset($_GET['commentID'])){
		echo "edit comment";
		$replyType = "comment";
		$commentID = $_GET['commentID'];
	}

	if($replyType == "post"){
		$sql = "SELECT text FROM post WHERE postID=" . $postID;
	}
	if($replyType == "comment"){
		$sql = "SELECT text FROM comment WHERE commentID=" . $commentID;
	}
	
	$result = mysqli_query($conn, $sql);
	$oriText = mysqli_fetch_assoc($result)['text'];
	
	?>
	
	<!--editing window-->
	<div>
		<form method="post">
			<?php
			echo '
			<!--text field-->
			<textarea name="editText" placeholder="Enter edited text here..." rows=6 cols=200>' . $oriText . '</textarea>
			';
			?>
			
			<!--submit button-->
			<div style = "right : 30px;">
				<input type="submit" value="Save" name="submit">
				<input type="submit" value="Cancel" name="cancel">
			</div>
		</form>
	</div>	

<?php
//creating new comment
if(isset($_POST["submit"])){	
	//new text entered by user
	$newText = $_POST["editText"];

	//remove errors from inverted comma(s)
	$newText = str_replace("'", "\'", $newText);
	
	if(strlen($newText) > 0){
		//generate current time
		$result = mysqli_query($conn, "SELECT CURRENT_TIMESTAMP()");
		$time = mysqli_fetch_assoc($result)['CURRENT_TIMESTAMP()'];
		
		if($replyType == "post"){
			$UpdateQuery = "UPDATE post SET text='$newText'";
		}
		if($replyType == "comment"){
			$UpdateQuery = "UPDATE comment SET text='$newText' WHERE commentID=$commentID";
		}
		
		if (mysqli_query($conn, $UpdateQuery)) {
			
			//obtain postID that the comment belongs to
			if($replyType == "comment"){
				$sql = "SELECT postID FROM comment WHERE commentID=$commentID";
				$result = mysqli_query($conn, $sql);
				$postID = mysqli_fetch_assoc($result)['postID'];
			}
			
			echo '<meta http-equiv="Refresh" content="0; url=threadview.php?postID=' . $postID . '" />';
		}
		else{
			echo "Error: " . $UpdateQuery . "<br>" . mysqli_error($conn);
		}
	}
	else{
		echo '
		<script language="javascript">
			alert("Text is empty")
		</script>
		';
	}
}

if(isset($_POST["cancel"])){
	//obtain postID that the comment belongs to
	if($replyType == "comment"){
		$sql = "SELECT postID FROM comment WHERE commentID=$commentID";
		$result = mysqli_query($conn, $sql);
		$postID = mysqli_fetch_assoc($result)['postID'];
	}
	
	echo '<meta http-equiv="Refresh" content="0; url=threadview.php?postID=' . $postID . '" />';
}

mysqli_close($conn);
?>
</body>
</html>