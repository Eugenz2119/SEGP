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
<body style="background-color: #E1E1E1";>

	
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

$userID = $_GET['userID'];

//username
$sql="SELECT username FROM user WHERE userID =" . $userID;
$result = mysqli_query($conn, $sql);
$username = mysqli_fetch_assoc($result)['username'];

echo '
<div id="username"class="w3-container w3-round-small" style="position: absolute; top: 90px; right:-10px; width: 250px; height: 100px ; border-style: solid;">
	<h3>' . $username .'</h3>
</div>
';

//profile picture
$sql="SELECT imageID FROM user WHERE userID =" . $userID;
$result = mysqli_query($conn, $sql);
$imageID = mysqli_fetch_assoc($result)['imageID'];
if($imageID != NULL){
	$sql="SELECT format FROM image WHERE imageID =" . $imageID;
	$result = mysqli_query($conn, $sql);
	$imageFormat = mysqli_fetch_assoc($result)['format'];
	$image_dir = "uploads/" . $imageID . '.' . $imageFormat;

	echo '
	<img src = "' . $image_dir . '" alt="avatar" style="position: absolute; width: 250px; height:250px; right:-10px;top: 190px; border-style: solid;">
	';
}
else{
	echo '
	<img src = "resources/placeholderimage.jpg" alt="avatar" style="position: absolute; width: 250px; height:250px; right:-10px;top: 190px; border-style: solid;">
	';
}

//user's posts
$threadLim = 5;

//find first result thread number for current page
if(!isset($_GET['page'])){
	$page=1;
}
else{
	$page = $_GET['page'];
}
$this_page_first_result = ($page-1)*$threadLim;

$sql="SELECT * FROM post WHERE userID=" . $userID . " ORDER BY postTime DESC LIMIT " . $this_page_first_result . ',' . $threadLim;
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
		
			<h1><a href="threadview.php?postID=' . $postID . '">' . $title . '</a></h1>
			<h6>by : <a href ="userprofile.php?userID=' . $posterID . '">' . $postUsername . '</a></h6>
			<p>' . $content . '</p>
		</div>
	';
}

//page number buttons
$sql = "SELECT COUNT(postID) FROM post WHERE userID=" . $userID;//total number of threads
$number_of_results = mysqli_fetch_assoc(mysqli_query($conn, $sql))['COUNT(postID)'];
$number_of_pages = ceil($number_of_results/$threadLim);

// Pagination
$prev = $page - 1;
$next = $page + 1;
$currentPage = $page;

//First and Previous buttons
if($currentPage>=3){
	echo '<a href="userprofile.php?userID=' . $userID . '&page=' . '1' . '">' ."<button><<</button>" .'</a>';
}
if($currentPage > 1){
	echo '<a href="userprofile.php?userID=' . $userID . '&page=' . $prev . '">' ."<button><</button>" .'</a>';
}

//Page number buttons	
if($currentPage<=3){
	for ($page=1;$page<=7;$page++) {
		// if page==currentPage, change colour
		if($page>=1){
			if($page==$currentPage){
				echo '<a href="userprofile.php?userID=' . $userID . '&page=' . $page . '">' . '<button style="background-color:#4CAF50">' . $page . '</button></a>';
			}
			else if ($page > 0 && $page <= $number_of_pages){
				echo '<a href="userprofile.php?userID=' . $userID . '&page=' . $page . '">' . '<button>' . $page . '</button></a>';
			}				
		}
	}	
}
else if($currentPage > $number_of_pages - 3){
	for($page = $number_of_pages - 6; $page <= $number_of_pages; $page++){
		// if page==currentPage, change colour
		if($page>=1){
			if($page==$currentPage){
				echo '<a href="userprofile.php?userID=' . $userID . '&page=' . $page . '">' . '<button style="background-color:#4CAF50">' . $page . '</button></a>';
			}
			else if ($page > 0 && $page <= $number_of_pages){
				echo '<a href="userprofile.php?userID=' . $userID . '&page=' . $page . '">' . '<button>' . $page . '</button></a>';
			}				
		}
	}
}
else{	
	for ($page=$currentPage-3;$page<=$currentPage+3;$page++) {
		// if page==currentPage, change colour
		if($page>=1){
			if($page==$currentPage){
				echo '<a href="userprofile.php?userID=' . $userID . '&page=' . $page . '">' . '<button style="background-color:#4CAF50">' . $page . '</button></a>';
			}
			else if ($page > 0 && $page <= $number_of_pages){
				echo '<a href="userprofile.php?userID=' . $userID . '&page=' . $page . '">' . '<button>' . $page . '</button></a>';
			}
		
		}
	}
}

//Next and Last buttons
if($currentPage < $number_of_pages){
	echo '<a href="userprofile.php?userID=' . $userID . '&page=' . $next . '">' .'<button>></button></a>';
}
if($currentPage <= $number_of_pages - 2){
	echo '<a href="userprofile.php?userID=' . $userID . '&page=' . $number_of_pages . '">' .'<button>>></button></a>';
}
?>	

</body>
</html>