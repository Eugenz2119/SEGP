<!DOCTYPE html>
<html>
<?php 
 include 'header.php'
?>
<head>
	<link href="./resources/css/index.css" type="text/css" rel ="stylesheet">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>AgriTalk</title>
	<script src="https://code.jquery.com/jquery-1.10.2.js"></script>

</head>
<body>
	
<?php
$threadLim = 10;

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

$sql="SELECT * FROM post ORDER BY postTime DESC LIMIT " . $threadLim;
$result = mysqli_query($conn, $sql);

while($row = mysqli_fetch_assoc($result)) {
	
	$postID = $row['postID'];
	$title = $row['title'];
	$posterID = $row['userID'];
	$query = "SELECT username FROM user WHERE userID=" . $posterID;
	$postUsername = mysqli_fetch_assoc(mysqli_query($conn, $query))['username'];
	$content = $row['text'];
	
	echo '
		<div class="w3-panel w3-border w3-round-small w3-padding-large" style="width:60%" >
		
			<h1>' . $title . '</h1>
			<h6>by : <a href ="userprofile.php?userID=' . $posterID . '">' . $postUsername . '</a></h6>
			<p>' . $content . '</p>
			<a href="threadview.php?postID=' . $postID . '">Read more...</a>
		</div>
	';
}
?>	

<!-- debug -->
userID: <?php echo $_SESSION["userID"];?> <br>

</body>
</html>

