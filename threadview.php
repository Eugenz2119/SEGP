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

function getQuote($str) {
	$strcheck = " " . $str;
	if(strpos($strcheck, "[QUOTE]")){
		$start = strpos($strcheck, "[QUOTE]") - 1 + strlen("[QUOTE]");
		if(strpos($strcheck, "[/QUOTE]")){
			$end = strpos($strcheck, "[/QUOTE]") - 1;
			$length = $end - $start;
		}
		else{
			return FALSE;
		}
		return substr($str, $start, $length);
	}
	else{
		return FALSE;
	}
}

function getMain($str){
	$start = strpos($str, "[/QUOTE]") + strlen("[/QUOTE]");
	return substr($str, $start);
}

include 'header.php';
include 'popularpost.php';
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

if(isset($_SESSION['userID'])){
	$userID = $_SESSION['userID'];
}
else{
	$userID = NULL;
}

$postID = $_GET["postID"];

$sql = "SELECT cropID, title, text, userID, imageID FROM post WHERE postID='$postID'";
$result = mysqli_query($conn, $sql);

$post = mysqli_fetch_assoc($result);
$cropID = $post['cropID'];
$title = $post['title'];
$content = $post['text'];

$authorID = $post['userID'];
$sql = "SELECT username FROM user WHERE userID=" . $authorID;
$authorName = mysqli_fetch_assoc(mysqli_query($conn, $sql))['username'];

$imageID = $post['imageID'];
$sql = "SELECT format from image WHERE imageID='$imageID'";
$imageFormat = mysqli_fetch_assoc(mysqli_query($conn, $sql))['format'];
$imagePath = "uploads/$imageID.$imageFormat";

//profilepic
$sql = "SELECT imageID FROM user WHERE userID=" . $authorID;
$result = mysqli_query($conn, $sql);
$profileimageID = mysqli_fetch_assoc($result)['imageID'];

//crop name
$sql = "SELECT cropname FROM crop WHERE cropID=" . $cropID;
$result = mysqli_query($conn, $sql);
$cropname = mysqli_fetch_assoc($result)['cropname'];

?>
	<div>
	<?php
	echo '
		<a href = "cropsubforum.php?cropID='. $cropID . '">' . $cropname . '</a><br>
	';
	?>
	
	<div class= "topbuttons">
	<!--reply-->
	<?php
	echo '
	<form action="createcomment.php" method="get">
		<input name="postID" type="hidden" value="' . $postID . '">
		<input name="postReplyID" type="hidden" value="' . $postID . '">
		<button id ="replybutton" type="submit">Reply</button>
	</form>
	';
	?>
	</div>
	
	<div id="threadtitle" style="overflow: hidden; overflow-wrap: break-word;">
	<?php 
	if($profileimageID != NULL){
		$sql="SELECT format FROM image WHERE imageID =" . $profileimageID;
		$result = mysqli_query($conn, $sql);
		$imageFormat = mysqli_fetch_assoc($result)['format'];
		$image_dir = "uploads/" . $profileimageID . '.' . $imageFormat;
		
		echo '
			<img src = "'. $image_dir .'" alt= "avatarpreview" style="width: 80px; height:auto; float: left;">
		';
	}
	else{
		echo '
			<img src = "resources/placeholderimage.jpg" alt= "avatarpreview" style="width: 80px; height:80px; float: left;margin-left:10px;margin-top:10px;">
		';
	}
	?>
	<h1 id = "title"><?php echo $title;?></h1>
	<h6 id = "authorname">by : <a  href="userprofile.php?userID=<?php echo $authorID; ?>"><?php echo $authorName; ?></a></h6>
	</div>
	

	<!--first post of the thread-->
	<div name="firstPost" class="comments" style="background-color: white; width: 70%; overflow: hidden; overflow-wrap: break-word;">
		<p><?php echo $content; ?></p>
		
		<?php
		if($imageID != NULL){
			echo '
				<!-- image display -->
				<img src=' . $imagePath . ' alt="image" style="width:30%; height:auto">
			';
		}
		?>
		

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
				<button name="postedit" type="submit">Edit</button>
			</form>
			';
		}
		?>
		
	</div>
	
	<?php
	//display existing comments
	$sql="SELECT * FROM comment WHERE commentID IN (SELECT commentID FROM post_comment where postID=" . $postID . ")";
	$result = mysqli_query($conn, $sql);

	while($row = mysqli_fetch_assoc($result)) {
		
		$commentID = $row['commentID'];
		$commenterID = $row['userID'];
		
		//text processing for html format output
		$txt = nl2br($row['text']);
		
		//separate components
		$quote = getQuote($txt);
		
		if($quote != FALSE){
			$main = getMain($txt);	
		}
		else{
			$main = $txt;
		}
		
		if($quote != FALSE){
			$content = '
			<!--div for quoted comment-->
			<div class="quote" style = "background-color: #E1E1E1; overflow: hidden; overflow-wrap: break-word;">
				<a>' . $quote . '</a>
			</div>
			<p>' . $main . '</p>';
		}
		else{
			$content = $main;
		}
		
		$sql = "SELECT username FROM user WHERE userID=" . $commenterID;
		$commenterName = mysqli_fetch_assoc(mysqli_query($conn, $sql))['username'];
		
		echo '
		<div class="comments" style = "width: 70%; background-color: white; overflow: hidden; overflow-wrap: break-word;">
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
					<button name="commentedit" type="submit">Edit</button>
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
	?>



<?php

//edit post
if(isset($_POST["postedit"])){
	$postModifyID = $_POST["postModifyID"];
	echo '<meta http-equiv="Refresh" content="0; url=editwindow.php?postID=' . $postModifyID . '" />';
}

//edit comment
if(isset($_POST["commentedit"])){
	$commentModifyID = $_POST["commentModifyID"];
	echo '<meta http-equiv="Refresh" content="0; url=editwindow.php?commentID=' . $commentModifyID . '" />';
}

//delete comment
if(isset($_POST["deletebutton"])){
	$DeleteQuery = "DELETE FROM post_comment WHERE commentID=" . $_POST["commentModifyID"];
	if (mysqli_query($conn, $DeleteQuery)) {
		$DeleteQuery = "DELETE FROM comment WHERE commentID=" . $_POST["commentModifyID"];
		if (mysqli_query($conn, $DeleteQuery)) {
			//decrement comment count
			$sql = 'SELECT commentCount FROM post WHERE postID=' . $postID;
			$result = mysqli_query($conn, $sql);
			$commentCount = mysqli_fetch_assoc($result)['commentCount'];
			$sql = 'UPDATE post SET commentCount=' . ($commentCount - 1) . ' WHERE postID=' . $postID;
			if(!mysqli_query($conn, $sql)){
				echo "Error: " . $AddQuery . "<br>" . mysqli_error($conn);
			}
			
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