<!DOCTYPE html>
<html>
<head>
	<link href="./resources/css/index.css" type="text/css" rel ="stylesheet">
	<link href="./resources/css/subforum.css" type="text/css" rel ="stylesheet">
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
echo '<a style ="font-size:20px;text-decoration:none; position: absolute; top:60px; left:10px; background-color: white;"href="threadcreate.php?cropID=' . $cropID . '">' . '&nbspCreate Thread&nbsp</a>';

//find first result thread number for current page
if(!isset($_GET['page'])){
	$page=1;
}
else{
	$page = $_GET['page'];
}

//page number buttons
$sql = "SELECT COUNT(postID) FROM post WHERE cropID=" . $cropID;//total number of threads
$number_of_results = mysqli_fetch_assoc(mysqli_query($conn, $sql))['COUNT(postID)'];
$number_of_pages = ceil($number_of_results/$threadLim);

$prev = $page - 1;
$next = $page + 1;
$currentPage = $page;

//////////PAGE NUMBER BUTTONS DISPLAY START//////////
echo '
<div style="position: absolute; top:100px;">
';

//First and Previous buttons
if($currentPage>=3){
	echo '<a href="cropsubforum.php?cropID=' . $cropID . '&page=' . '1' . '">' ."<button><<</button>" .'</a>';
}
if($currentPage > 1){
	echo '<a href="cropsubforum.php?cropID=' . $cropID . '&page=' . $prev . '">' ."<button><</button>" .'</a>';
}

//Page number buttons	
if($currentPage<=3){
	for ($page=1;$page<=7;$page++) {
		// if page==currentPage, change colour
		if($page>=1){
			if($page==$currentPage){
				echo '<a href="cropsubforum.php?cropID=' . $cropID . '&page=' . $page . '">' . '<button style="background-color:#4CAF50">' . $page . '</button></a>';
			}
			else if ($page > 0 && $page <= $number_of_pages){
				echo '<a href="cropsubforum.php?cropID=' . $cropID . '&page=' . $page . '">' . '<button>' . $page . '</button></a>';
			}				
		}
	}	
}
else if($currentPage > $number_of_pages - 3){
	for($page = $number_of_pages - 6; $page <= $number_of_pages; $page++){
		// if page==currentPage, change colour
		if($page>=1){
			if($page==$currentPage){
				echo '<a href="cropsubforum.php?cropID=' . $cropID . '&page=' . $page . '">' . '<button style="background-color:#4CAF50">' . $page . '</button></a>';
			}
			else if ($page > 0 && $page <= $number_of_pages){
				echo '<a href="cropsubforum.php?cropID=' . $cropID . '&page=' . $page . '">' . '<button>' . $page . '</button></a>';
			}				
		}
	}
}
else{	
	for ($page=$currentPage-3;$page<=$currentPage+3;$page++) {
		// if page==currentPage, change colour
		if($page>=1){
			if($page==$currentPage){
				echo '<a href="cropsubforum.php?cropID=' . $cropID . '&page=' . $page . '">' . '<button style="background-color:#4CAF50">' . $page . '</button></a>';
			}
			else if ($page > 0 && $page <= $number_of_pages){
				echo '<a href="cropsubforum.php?cropID=' . $cropID . '&page=' . $page . '">' . '<button>' . $page . '</button></a>';
			}
		
		}
	}
}

//Next and Last buttons
if($currentPage < $number_of_pages){
	echo '<a href="cropsubforum.php?cropID=' . $cropID . '&page=' . $next . '">' .'<button>></button></a>';
}
if($currentPage <= $number_of_pages - 2){
	echo '<a href="cropsubforum.php?cropID=' . $cropID . '&page=' . $number_of_pages . '">' .'<button>>></button></a>';

}

echo '
</div>
';
//////////PAGE NUMBER BUTTONS DISPLAY END//////////

echo'</br></br></br></br>';

$this_page_first_result = ($currentPage-1)*$threadLim;

$sql ='SELECT * FROM post WHERE cropID=' . $cropID . ' ORDER BY postTime DESC LIMIT ' . $this_page_first_result . ',' . $threadLim;
$result=mysqli_query($conn,$sql);

while($row = mysqli_fetch_array($result)) {
	
	$postID = $row['postID'];
	$title = $row['title'];
	$posterID = $row['userID'];
	$commentCount = $row['commentCount'];
	$lastCommentTime = $row['lastCommentTime'];
	$query = "SELECT username FROM user WHERE userID=" . $posterID;
	$postUsername = mysqli_fetch_assoc(mysqli_query($conn, $query))['username'];
	$content = $row['text'];
	
	echo '
		<table style="width:70%; border: 1px solid black;">
  		<tr>
    		
    		<th style="max-width: 600px; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; text-align: left; font-size: 30px; padding:10px;">
				<a href="threadview.php?postID=' . $postID . '" >' . $title . '</a>
				</th>
   			<th style="text-align:right; font-weight: 400; border-right: 1px solid black;"></br>number of comments: ' . $commentCount . '</br>latest comment: '. $lastCommentTime .'</th> 

  		</tr>
  		<tr>
    		<td colspan=2 style= "font-size:13px; border-right: 1px solid black;">
				by : <a href ="userprofile.php?userID=' . $posterID . '">' . $postUsername . '
			</td>
   		</tr>
  		<tr>
   			<td colspan =2 style="max-width: 600px; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; font-size: 16px; padding:16px; border: 1px solid black">
				' . $content . '
			</td>
  		</tr>
		</table></br>
	';
}

//////////PAGE NUMBER BUTTONS DISPLAY START//////////
echo '
<div style="position: absolute; top:100px;">
';

//First and Previous buttons
if($currentPage>=3){
	echo '<a href="cropsubforum.php?cropID=' . $cropID . '&page=' . '1' . '">' ."<button><<</button>" .'</a>';
}
if($currentPage > 1){
	echo '<a href="cropsubforum.php?cropID=' . $cropID . '&page=' . $prev . '">' ."<button><</button>" .'</a>';
}

//Page number buttons	
if($currentPage<=3){
	for ($page=1;$page<=7;$page++) {
		// if page==currentPage, change colour
		if($page>=1){
			if($page==$currentPage){
				echo '<a href="cropsubforum.php?cropID=' . $cropID . '&page=' . $page . '">' . '<button style="background-color:#4CAF50">' . $page . '</button></a>';
			}
			else if ($page > 0 && $page <= $number_of_pages){
				echo '<a href="cropsubforum.php?cropID=' . $cropID . '&page=' . $page . '">' . '<button>' . $page . '</button></a>';
			}				
		}
	}	
}
else if($currentPage > $number_of_pages - 3){
	for($page = $number_of_pages - 6; $page <= $number_of_pages; $page++){
		// if page==currentPage, change colour
		if($page>=1){
			if($page==$currentPage){
				echo '<a href="cropsubforum.php?cropID=' . $cropID . '&page=' . $page . '">' . '<button style="background-color:#4CAF50">' . $page . '</button></a>';
			}
			else if ($page > 0 && $page <= $number_of_pages){
				echo '<a href="cropsubforum.php?cropID=' . $cropID . '&page=' . $page . '">' . '<button>' . $page . '</button></a>';
			}				
		}
	}
}
else{	
	for ($page=$currentPage-3;$page<=$currentPage+3;$page++) {
		// if page==currentPage, change colour
		if($page>=1){
			if($page==$currentPage){
				echo '<a href="cropsubforum.php?cropID=' . $cropID . '&page=' . $page . '">' . '<button style="background-color:#4CAF50">' . $page . '</button></a>';
			}
			else if ($page > 0 && $page <= $number_of_pages){
				echo '<a href="cropsubforum.php?cropID=' . $cropID . '&page=' . $page . '">' . '<button>' . $page . '</button></a>';
			}
		
		}
	}
}

//Next and Last buttons
if($currentPage < $number_of_pages){
	echo '<a href="cropsubforum.php?cropID=' . $cropID . '&page=' . $next . '">' .'<button>></button></a>';
}
if($currentPage <= $number_of_pages - 2){
	echo '<a href="cropsubforum.php?cropID=' . $cropID . '&page=' . $number_of_pages . '">' .'<button>>></button></a>';

}

echo '
</div>
';
//////////PAGE NUMBER BUTTONS DISPLAY END//////////

?>







</body>
</html>