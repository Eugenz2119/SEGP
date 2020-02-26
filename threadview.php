<!DOCTYPE html>
<html>
<head>
	<link href="./resources/css/threadview.css" type="text/css" rel ="stylesheet">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>AgriTalk</title>
	<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
</head>



<body style="background-color: #E1E1E1";>

<?php
include 'header.php';
include 'cropinfo.php';
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

if(isset($_SESSION['userID'])){
	$userID = $_SESSION['userID'];
}
else{
	$userID = NULL;
}

$postID = $_GET["postID"];

$sql = "SELECT title, text, userID, imageID FROM post WHERE postID='$postID'";
$result = mysqli_query($conn, $sql);

$post = mysqli_fetch_assoc($result);
$title = $post['title'];
$content = $post['text'];

$authorID = $post['userID'];
$sql = "SELECT username FROM user WHERE userID=" . $authorID;
$authorName = mysqli_fetch_assoc(mysqli_query($conn, $sql))['username'];

$imageID = $post['imageID'];
$sql = "SELECT format from image WHERE imageID='$imageID'";
$imageFormat = mysqli_fetch_assoc(mysqli_query($conn, $sql))['format'];
$imagePath = "uploads/$imageID.$imageFormat";

?>

	<header id="threadtitle">
	<h1><?php echo $title;?></h1>
	<h6>by : <a href="userprofile.php?userID=<?php echo $authorID; ?>"><?php echo $authorName; ?></a></h6>
	</header>
	
	<!--comment creation code-->
	<div>
		<form method="post">
		<input type = "text" id="threadcomment" name ="threadcomment" placeholder ="New Comment..." size = "50"><br>

		<!--submit button-->
		<div style = "right : 30px;">
		<input type="submit" value="Comment">
		</div>
		</form>
	</div>	
	
	<!--first post of the thread-->
	<div name="firstPost" class="comments" style="background-color: white; width: 80%;">
		<p><?php echo $content; ?></p>
		
		<?php
		if($imageID != NULL){
			echo '
				<!-- image display -->
				<img src=' . $imagePath . ' alt="image" style="width:20%; height:20%;">
			';
		}
		?>
		
		<button id="sharebutton">Share</button>

		<!--up/downvote buttons
		<form method="post">
			<input name="postVoteID" type="hidden" value="' . $postID . '">
			<button class="upvote" name="postUpvote"><i class="fa fa-thumbs-up"></i></button>
			<button class="downvote" name="postDownvote"><i class="fa fa-thumbs-down"></i></button>
		</form>
		-->
		
		<!--edit/delete-->
			<?php
			if($userID != NULL && $authorID == $userID){
				echo '
				<form method="post">
					<input name="postModifyID" type="hidden" value="' . $postID . '">
					<button name="editbutton" type="submit">Edit</button>
				</form>
				';
			}
			?>
	</div>


	<!-- text field for editing comments
	<div id = "editcontents">
		<form method="post">
		<input type = "text" id="threadedit" name ="threadedit" placeholder ="Edited Comment..." size = "50">	
	</div>
	-->


	
	<?php
	//display existing comments
	$sql="SELECT * FROM comment WHERE commentID IN (SELECT commentID FROM post_comment where postID=" . $postID . ")";
	$result = mysqli_query($conn, $sql);

	while($row = mysqli_fetch_assoc($result)) {
		
		$commentID = $row['commentID'];
		$commenterID = $row['userID'];
		$content = $row['text'];
		
		$sql = "SELECT username FROM user WHERE userID=" . $commenterID;
		$commenterName = mysqli_fetch_assoc(mysqli_query($conn, $sql))['username'];
		
		echo '
		<div class="comments" style = "width: 80%; background-color: white;">
			<a>by : <a href ="userprofile.php?userID=' . $commenterID . '">' . $commenterName . '</a>

			<!--div for quoted comment-->
			<div class="quote" style = "width: 60%; height: 20%; background-color: #E1E1E1;">
				<a>placeholder quoted comment placeholder quoted comment placeholder quoted comment placeholder quoted comment placeholder quoted comment placeholder quoted comment placeholder quoted comment placeholder quoted comment placeholder quoted comment </a>
			</div>
			<p>' . $content . '</p>';
			
			/*
			//up/downvote buttons
			echo '
			<form method="post">
				<input name="commentVoteID" type="hidden" value="' . $commentID . '">
				<button class="upvote" name="commentUpvote"><i class="fa fa-thumbs-up"></i></button>
				<button class="downvote" name="commentDownvote"><i class="fa fa-thumbs-down"></i></button>
			</form>
			';
			*/
			
			//edit/delete
			if($userID != NULL && $commenterID == $userID){
				echo '
				<form method="post">
					<input name="commentModifyID" type="hidden" value="' . $commentID . '">
					<button name="editbutton" type="submit">Edit</button>
					<button name="deletebutton" type="submit" onclick="return confirm(\'Confirm delete?\')">Delete</button>
				</form>
				';
			}
		echo '
		</div>
		';
		
			//nested comments, ignore for now
			/*
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
			*/
	}
	mysqli_close($conn);
	?>



<?php
//creating new comment
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

if(isset($_POST["threadcomment"])){
	
	//login check
	if(isset($_SESSION["userID"])){
		$userID = $_SESSION["userID"];
	}
	else{
		echo '<meta http-equiv="Refresh" content="0; url=login.php" />';
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
}

/*
//edit comment
if(isset($_POST["editbutton"])){
	echo "commentID=" . $_POST["commentModifyID"];
	echo "edit pressed";
}
*/

//delete comment
if(isset($_POST["deletebutton"])){
	$DeleteQuery = "DELETE FROM post_comment WHERE commentID=" . $_POST["commentModifyID"];
	if (mysqli_query($conn, $DeleteQuery)) {
		$DeleteQuery = "DELETE FROM comment WHERE commentID=" . $_POST["commentModifyID"];
		if (mysqli_query($conn, $DeleteQuery)) {
			echo '<meta http-equiv="Refresh" content="0; url=threadview.php?postID=' . $postID . '" />';
		} else {
			echo "Error: " . $AddQuery . "<br>" . mysqli_error($conn);
		}
	}
}

mysqli_close($conn);
?>
	
</body>
</html>