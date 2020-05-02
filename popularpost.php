<!DOCTYPE html>
<html>
<head>
	<link href="./resources/css/index.css" type="text/css" rel ="stylesheet">
	<link href="./resources/css/popularpost.css" type="text/css" rel ="stylesheet">
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
	<div class="post-wrapper">
		<div>
			<h3>Popular Posts This Week</h3>
		</div>
<?php
$postLim = 5;

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

//generate current time
$result = mysqli_query($conn, "SELECT CURRENT_TIMESTAMP()");
$time = mysqli_fetch_assoc($result)['CURRENT_TIMESTAMP()'];

$sql ="SELECT * FROM post WHERE (SELECT DATEDIFF('$time', lastCommentTime))<7 ORDER BY commentCount DESC, lastCommentTime DESC LIMIT " . $postLim;
$result=mysqli_query($conn,$sql);

while($row = mysqli_fetch_assoc($result)) {
	$postID = $row['postID'];
	$cropID = $row['cropID'];
	$query = "SELECT cropname FROM crop WHERE cropID=" . $cropID;
	$cropname = mysqli_fetch_assoc(mysqli_query($conn, $query))['cropname'];
	$title = $row['title'];
	$posterID = $row['userID'];
	$commentCount = $row['commentCount'];
	$lastCommentTime = $row['lastCommentTime'];
	$query = "SELECT username FROM user WHERE userID=" . $posterID;
	$postUsername = mysqli_fetch_assoc(mysqli_query($conn, $query))['username'];
	echo '
		<div class="poppreview" style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
			<a>in : <a href ="cropsubforum.php?cropID=' . $cropID . '">' . $cropname . '</a>
			<h5 style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;"><a href="threadview.php?postID=' . $postID . '">' . $title . '</a></h5>
			<div class="postuser">
				<a>by : <a href ="userprofile.php?userID=' . $posterID . '">' . $postUsername . '</a></a>
			</div>
			<a>' . $commentCount . ' comment(s)</a><br>
			<a>Last Comment: ' . $lastCommentTime . '</a>
		</div>
	';
}

?>
	</div>








</body>
</html>
