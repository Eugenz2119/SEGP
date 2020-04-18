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

<?php
$threadLim = 5;

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

//find first result thread number for current page
if(!isset($_GET['page'])){
	$page=1;
}
else{
	$page = $_GET['page'];
}
$this_page_first_result = ($page-1)*$threadLim;

$sql ='SELECT * FROM crop ORDER BY cropname LIMIT ' . $this_page_first_result . ',' . $threadLim;
$result=mysqli_query($conn,$sql);

//crop subforum panel
while($row = mysqli_fetch_array($result)) {
	
	$cropID = $row['cropID'];
	$cropname = $row['cropname'];
	$scname = $row['scname'];
	
	echo '
		<div class="w3-panel w3-border w3-round-small w3-padding-large" style="width:60%; background-color: white;" >
			<h1><a href ="cropsubforum.php?cropID='. $cropID . '">'. $cropname . '</a></h1>
			<p>' . $scname . '</p>
		</div>
	';
}

//page number buttons
$sql = "SELECT COUNT(cropID) FROM crop";//total number of threads
$number_of_results = mysqli_fetch_assoc(mysqli_query($conn, $sql))['COUNT(cropID)'];
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
	echo '<a href="subforumlist.php?page=' . '1' . '">' ."<button><<</button>" .'</a>';
}
if($currentPage > 1){
	echo '<a href="subforumlist.php?page=' . $prev . '">' ."<button><</button>" .'</a>';
}

//Page number buttons	
if($currentPage<=3){
	for ($page=1;$page<=7;$page++) {
		// if page==currentPage, change colour
		if($page>=1){
			if($page==$currentPage){
				echo '<a href="subforumlist.php?page=' . $page . '">' . '<button style="background-color:#4CAF50">' . $page . '</button></a>';
			}
			else if ($page > 0 && $page <= $number_of_pages){
				echo '<a href="subforumlist.php?page=' . $page . '">' . '<button>' . $page . '</button></a>';
			}				
		}
	}	
}
else if($currentPage > $number_of_pages - 3){
	for($page = $number_of_pages - 6; $page <= $number_of_pages; $page++){
		// if page==currentPage, change colour
		if($page>=1){
			if($page==$currentPage){
				echo '<a href="subforumlist.php?page=' . $page . '">' . '<button style="background-color:#4CAF50">' . $page . '</button></a>';
			}
			else if ($page > 0 && $page <= $number_of_pages){
				echo '<a href="subforumlist.php?page=' . $page . '">' . '<button>' . $page . '</button></a>';
			}				
		}
	}
}
else{	
	for ($page=$currentPage-3;$page<=$currentPage+3;$page++) {
		// if page==currentPage, change colour
		if($page>=1){
			if($page==$currentPage){
				echo '<a href="subforumlist.php?page=' . $page . '">' . '<button style="background-color:#4CAF50">' . $page . '</button></a>';
			}
			else if ($page > 0 && $page <= $number_of_pages){
				echo '<a href="subforumlist.php?page=' . $page . '">' . '<button>' . $page . '</button></a>';
			}
		
		}
	}
}

//Next and Last buttons
if($currentPage < $number_of_pages){
	echo '<a href="subforumlist.php?page=' . $next . '">' .'<button>></button></a>';
}
if($currentPage <= $number_of_pages - 2){
	echo '<a href="subforumlist.php?page=' . $number_of_pages . '">' .'<button>>></button></a>';

}

echo '
</div>
';

?>






</body>
</html>
