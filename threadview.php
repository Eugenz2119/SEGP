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
	
	<!--reply-->
	<?php
	echo '
	<form action="createcomment.php" method="get">
		<input name="postID" type="hidden" value="' . $postID . '">
		<input name="postReplyID" type="hidden" value="' . $postID . '">
		<button type="submit">Reply</button>
	</form>
	';
	?>
	
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
		
		//text processing for html format output
		$txt = nl2br($row['text']);
		$txt2 = str_replace("[QUOTE]",
			'<!--div for quoted comment-->
			<div class="quote" style = "width: 60%; height: 20%; background-color: #E1E1E1;">
				<a>',
			$txt);
		$txt3 = str_replace("[/QUOTE]",
			'	</a>
			</div>
			<p>',
			$txt2);
		$content = $txt3 . '</p>';
		
		
		$sql = "SELECT username FROM user WHERE userID=" . $commenterID;
		$commenterName = mysqli_fetch_assoc(mysqli_query($conn, $sql))['username'];
		
		echo '
		<div class="comments" style = "width: 80%; background-color: white;">
			<a>by : <a href ="userprofile.php?userID=' . $commenterID . '">' . $commenterName . '</a><br>';

			echo $content;
			
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
			
			//reply(quote)
			echo '
			<form action="createcomment.php" method="get">
				<input name="postID" type="hidden" value="' . $postID . '">
				<input name="commentReplyID" type="hidden" value="' . $commentID . '">
				<button type="submit">Reply</button>
			</form>
			';
			
		echo '
		</div>
		';
		
	}
	mysqli_close($conn);
	?>



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