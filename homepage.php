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
$threadLim = 2;

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

$sql="SELECT * FROM post ORDER BY postTime DESC LIMIT 0,5";
$result = mysqli_query($conn, $sql);
$number_of_results = mysqli_num_rows($result);

while($row = mysqli_fetch_array($result)) {
	
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
$number_of_pages = ceil($number_of_results/$threadLim);


for($page = 1 ;$page<=$number_of_pages;$page++){
	echo '<a href="homepage.php?page=' . $page . '">' . $page . '</a>';
}
?>
</body>
</html>

