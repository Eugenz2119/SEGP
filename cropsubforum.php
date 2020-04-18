<!DOCTYPE html>
<html>
<head>
	<link href="./resources/css/index.css" type="text/css" rel ="stylesheet">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>AgriTalk</title>
	<script src="https://code.jquery.com/jquery-1.10.2.js"></script>

</head>
<body style="background-color: #E1E1E1";>
	
<?php include 'header.php';?>
<?php include 'cropinfo.php';?>


<?php
$threadLim = 5;

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

if(isset($_GET['cropID'])){
	$cropID = $_GET['cropID'];
}
else{
	echo '<meta http-equiv="Refresh" content="0; url=subforumlist.php" />';
}

//thread create button
echo '<a href="threadcreate.php?cropID=' . $cropID . '">' . '<button>Create Thread</button></a>';

//find first result thread number for current page
if(!isset($_GET['page'])){
	$page=1;
}
else{
	$page = $_GET['page'];
}
$this_page_first_result = ($page-1)*$threadLim;

$sql ='SELECT * FROM post WHERE cropID=' . $cropID . ' ORDER BY postTime DESC LIMIT ' . $this_page_first_result . ',' . $threadLim;
$result=mysqli_query($conn,$sql);

while($row = mysqli_fetch_array($result)) {
	
	$postID = $row['postID'];
	$title = $row['title'];
	$posterID = $row['userID'];
	$query = "SELECT username FROM user WHERE userID=" . $posterID;
	$postUsername = mysqli_fetch_assoc(mysqli_query($conn, $query))['username'];
	$content = $row['text'];
	
	echo '
		<div class="w3-panel w3-border w3-round-small w3-padding-large" style="width:60%; background-color: white;" >
			<h1><a href="threadview.php?postID=' . $postID . '">' . $title . '</a></h1>
			<div style = "font-size : 13px;">
				<a>by : <a href ="userprofile.php?userID=' . $posterID . '">' . $postUsername . '</a></a>
			</div>
			<p>' . $content . '</p>
		</div>
	';
}

//page number buttons
$sql = "SELECT COUNT(postID) FROM post";//total number of threads
$number_of_results = mysqli_fetch_assoc(mysqli_query($conn, $sql))['COUNT(postID)'];
$number_of_pages = ceil($number_of_results/$threadLim);

// Pagination
$prev = $page - 1;
$next = $page + 1;
$currentPage = $page;

echo '
<div class="page">
';

//First and Previous buttons
if($currentPage>=3){
	echo '<a href="cropsubforum.php?page=' . '1' . '">' ."<button><<</button>" .'</a>';
}
if($currentPage > 1){
	echo '<a href="cropsubforum.php?page=' . $prev . '">' ."<button><</button>" .'</a>';
}

//Page number buttons	
if($currentPage<=3){
	for ($page=1;$page<=7;$page++) {
		// if page==currentPage, change colour
		if($page>=1){
			if($page==$currentPage){
				echo '<a href="cropsubforum.php?page=' . $page . '">' . '<button style="background-color:#4CAF50">' . $page . '</button></a>';
			}
			else if ($page > 0 && $page <= $number_of_pages){
				echo '<a href="cropsubforum.php?page=' . $page . '">' . '<button>' . $page . '</button></a>';
			}				
		}
	}	
}
else if($currentPage > $number_of_pages - 3){
	for($page = $number_of_pages - 6; $page <= $number_of_pages; $page++){
		// if page==currentPage, change colour
		if($page>=1){
			if($page==$currentPage){
				echo '<a href="cropsubforum.php?page=' . $page . '">' . '<button style="background-color:#4CAF50">' . $page . '</button></a>';
			}
			else if ($page > 0 && $page <= $number_of_pages){
				echo '<a href="cropsubforum.php?page=' . $page . '">' . '<button>' . $page . '</button></a>';
			}				
		}
	}
}
else{	
	for ($page=$currentPage-3;$page<=$currentPage+3;$page++) {
		// if page==currentPage, change colour
		if($page>=1){
			if($page==$currentPage){
				echo '<a href="cropsubforum.php?page=' . $page . '">' . '<button style="background-color:#4CAF50">' . $page . '</button></a>';
			}
			else if ($page > 0 && $page <= $number_of_pages){
				echo '<a href="cropsubforum.php?page=' . $page . '">' . '<button>' . $page . '</button></a>';
			}
		
		}
	}
}

//Next and Last buttons
if($currentPage < $number_of_pages){
	echo '<a href="cropsubforum.php?page=' . $next . '">' .'<button>></button></a>';
}
if($currentPage <= $number_of_pages - 2){
	echo '<a href="cropsubforum.php?page=' . $number_of_pages . '">' .'<button>>></button></a>';

}

echo '
</div>
';

?>







</body>
</html>