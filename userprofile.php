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

$userID = $_GET['userID'];

//user information 
echo '
<div style="position: absolute; top: 90px; right:-10px; width: 250px;">
';

//username
$sql="SELECT username FROM user WHERE userID =" . $userID;
$result = mysqli_query($conn, $sql);
$username = mysqli_fetch_assoc($result)['username'];

echo '
	<div id="username"class="w3-container w3-round-small" style="background-color : white; width: 250px; height: 100px; border-style: solid;">
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
	<img src = "' . $image_dir . '" alt="avatar" style="width: 250px; height:auto; border-style: solid;">
	';
}
else{
	echo '
	<img src = "resources/placeholderimage.jpg" alt="avatar" style="width: 250px; height:auto; border-style: solid;">
	';
}

//user details
$sql = "SELECT * FROM user WHERE userID=" . $userID;
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$permissions = $row['permissions'];

echo '
	<div class="w3-container w3-round-small" style="background-color : white; width: 250px; border-style: solid;">
';

	//age
	if(floor($permissions / 100000) == 1){
		$Age = $row['age'];
		echo '
		Age: ' . $Age . '<br>
		';
	}
	$permissions = $permissions % 100000;

	//email
	if(floor($permissions / 10000) == 1){
		$Email = $row['email'];
		echo '
		Email: ' . $Email . '<br>
		';
	}
	$permissions = $permissions % 10000;

	//institution
	if(floor($permissions / 1000) == 1){
		$Institution = $row['institution'];
		echo '
		Institution: ' . $Institution . '<br>
		';
	}
	$permissions = $permissions % 1000;

	//occupation
	if(floor($permissions / 100) == 1){
		$Occupation = $row['occupation'];
		echo '
		Occupation: ' . $Occupation . '<br>
		';
	}
	$permissions = $permissions % 100;

	//country
	if(floor($permissions / 10) == 1){
		$Country = $row['country'];
		echo '
		Country: ' . $Country . '<br>
		';
	}
	$permissions = $permissions % 10;

	//phonenum
	if(floor($permissions / 1) == 1){
		$PhoneNum = $row['phonenum'];
		echo '
		PhoneNum: ' . $PhoneNum . '<br>
		';
	}
	
echo '
	</div>
</div>
';

//display limit
$threadLim = 5;

//find page numbers
if(!isset($_GET['postpage'])){
	$postpage=1;
}
else{
	$postpage = $_GET['postpage'];
}

if(!isset($_GET['commentpage'])){
	$commentpage=1;
}
else{
	$commentpage = $_GET['commentpage'];
}

//posts display
//page number buttons
$sql = "SELECT COUNT(postID) FROM post WHERE userID=" . $userID;//total number of threads
$number_of_results = mysqli_fetch_assoc(mysqli_query($conn, $sql))['COUNT(postID)'];
$number_of_pages = ceil($number_of_results/$threadLim);

$prev = $postpage - 1;
$next = $postpage + 1;
$currentPage = $postpage;

$this_page_first_result = ($currentPage-1)*$threadLim;

$sql="SELECT * FROM post WHERE userID=" . $userID . " ORDER BY postTime DESC LIMIT " . $this_page_first_result . ',' . $threadLim;
$result = mysqli_query($conn, $sql);

echo '
<div style="position:absolute; left:20px; width:30%;">
<h2>Most recent posts</h2><br>
';

//////////PAGE NUMBER BUTTONS DISPLAY START//////////
echo '
<div>
';

//First and Previous buttons
if($currentPage>=3){
	echo '<a href="userprofile.php?userID=' . $userID . '&postpage=' . '1' . '&commentpage=' . $commentpage . '">' ."<button><<</button>" .'</a>';
}
if($currentPage > 1){
	echo '<a href="userprofile.php?userID=' . $userID . '&postpage=' . $prev . '&commentpage=' . $commentpage . '">' ."<button><</button>" .'</a>';
}

//Page number buttons	
if($currentPage<=3){
	for ($page=1;$page<=7;$page++) {
		// if page==currentPage, change colour
		if($page>=1){
			if($page==$currentPage){
				echo '<a href="userprofile.php?userID=' . $userID . '&postpage=' . $page . '&commentpage=' . $commentpage . '">' . '<button style="background-color:#4CAF50">' . $page . '</button></a>';
			}
			else if ($page > 0 && $page <= $number_of_pages){
				echo '<a href="userprofile.php?userID=' . $userID . '&postpage=' . $page . '&commentpage=' . $commentpage . '">' . '<button>' . $page . '</button></a>';
			}				
		}
	}	
}
else if($currentPage > $number_of_pages - 3){
	for($page = $number_of_pages - 6; $page <= $number_of_pages; $page++){
		// if page==currentPage, change colour
		if($page>=1){
			if($page==$currentPage){
				echo '<a href="userprofile.php?userID=' . $userID . '&postpage=' . $page . '&commentpage=' . $commentpage . '">' . '<button style="background-color:#4CAF50">' . $page . '</button></a>';
			}
			else if ($page > 0 && $page <= $number_of_pages){
				echo '<a href="userprofile.php?userID=' . $userID . '&postpage=' . $page . '&commentpage=' . $commentpage . '">' . '<button>' . $page . '</button></a>';
			}				
		}
	}
}
else{	
	for ($page=$currentPage-3;$page<=$currentPage+3;$page++) {
		// if page==currentPage, change colour
		if($page>=1){
			if($page==$currentPage){
				echo '<a href="userprofile.php?userID=' . $userID . '&postpage=' . $page . '&commentpage=' . $commentpage . '">' . '<button style="background-color:#4CAF50">' . $page . '</button></a>';
			}
			else if ($page > 0 && $page <= $number_of_pages){
				echo '<a href="userprofile.php?userID=' . $userID . '&postpage=' . $page . '&commentpage=' . $commentpage . '">' . '<button>' . $page . '</button></a>';
			}
		
		}
	}
}

//Next and Last buttons
if($currentPage < $number_of_pages){
	echo '<a href="userprofile.php?userID=' . $userID . '&postpage=' . $next . '&commentpage=' . $commentpage . '">' .'<button>></button></a>';
}
if($currentPage <= $number_of_pages - 2){
	echo '<a href="userprofile.php?userID=' . $userID . '&postpage=' . $number_of_pages . '&commentpage=' . $commentpage . '">' .'<button>>></button></a>';
}


echo '
</div>
';
//////////PAGE NUMBER BUTTONS DISPLAY END//////////

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
	$content = $row['text'];
	
	echo '
		<div class="w3-panel w3-border w3-round-small w3-padding-large" style=" background-color: white; height:208px;" >
			<a>in : <a href ="cropsubforum.php?cropID=' . $cropID . '">' . $cropname . '</a>
			<h1><a href="threadview.php?postID=' . $postID . '">' . $title . '</a></h1>
			<div style = "font-size : 13px;">
				<a>by : <a href ="userprofile.php?userID=' . $posterID . '">' . $postUsername . '</a></a><br>
				<a>' . $commentCount . ' comment(s)</a>
				<a>Last Comment: ' . $lastCommentTime . '</a>
			</div>
			<p>' . $content . '</p>
		</div>
	';
}

//////////PAGE NUMBER BUTTONS DISPLAY START//////////
echo '
<div>
';

//First and Previous buttons
if($currentPage>=3){
	echo '<a href="userprofile.php?userID=' . $userID . '&postpage=' . '1' . '&commentpage=' . $commentpage . '">' ."<button><<</button>" .'</a>';
}
if($currentPage > 1){
	echo '<a href="userprofile.php?userID=' . $userID . '&postpage=' . $prev . '&commentpage=' . $commentpage . '">' ."<button><</button>" .'</a>';
}

//Page number buttons	
if($currentPage<=3){
	for ($page=1;$page<=7;$page++) {
		// if page==currentPage, change colour
		if($page>=1){
			if($page==$currentPage){
				echo '<a href="userprofile.php?userID=' . $userID . '&postpage=' . $page . '&commentpage=' . $commentpage . '">' . '<button style="background-color:#4CAF50">' . $page . '</button></a>';
			}
			else if ($page > 0 && $page <= $number_of_pages){
				echo '<a href="userprofile.php?userID=' . $userID . '&postpage=' . $page . '&commentpage=' . $commentpage . '">' . '<button>' . $page . '</button></a>';
			}				
		}
	}	
}
else if($currentPage > $number_of_pages - 3){
	for($page = $number_of_pages - 6; $page <= $number_of_pages; $page++){
		// if page==currentPage, change colour
		if($page>=1){
			if($page==$currentPage){
				echo '<a href="userprofile.php?userID=' . $userID . '&postpage=' . $page . '&commentpage=' . $commentpage . '">' . '<button style="background-color:#4CAF50">' . $page . '</button></a>';
			}
			else if ($page > 0 && $page <= $number_of_pages){
				echo '<a href="userprofile.php?userID=' . $userID . '&postpage=' . $page . '&commentpage=' . $commentpage . '">' . '<button>' . $page . '</button></a>';
			}				
		}
	}
}
else{	
	for ($page=$currentPage-3;$page<=$currentPage+3;$page++) {
		// if page==currentPage, change colour
		if($page>=1){
			if($page==$currentPage){
				echo '<a href="userprofile.php?userID=' . $userID . '&postpage=' . $page . '&commentpage=' . $commentpage . '">' . '<button style="background-color:#4CAF50">' . $page . '</button></a>';
			}
			else if ($page > 0 && $page <= $number_of_pages){
				echo '<a href="userprofile.php?userID=' . $userID . '&postpage=' . $page . '&commentpage=' . $commentpage . '">' . '<button>' . $page . '</button></a>';
			}
		
		}
	}
}

//Next and Last buttons
if($currentPage < $number_of_pages){
	echo '<a href="userprofile.php?userID=' . $userID . '&postpage=' . $next . '&commentpage=' . $commentpage . '">' .'<button>></button></a>';
}
if($currentPage <= $number_of_pages - 2){
	echo '<a href="userprofile.php?userID=' . $userID . '&postpage=' . $number_of_pages . '&commentpage=' . $commentpage . '">' .'<button>>></button></a>';
}


echo '
</div>
';
//////////PAGE NUMBER BUTTONS DISPLAY END//////////

echo '
</div>
';

//comments display
//page number buttons
$sql = "SELECT COUNT(commentID) FROM comment WHERE userID=" . $userID;//total number of threads
$number_of_results = mysqli_fetch_assoc(mysqli_query($conn, $sql))['COUNT(commentID)'];
$number_of_pages = ceil($number_of_results/$threadLim);

$prev = $commentpage - 1;
$next = $commentpage + 1;
$currentPage = $commentpage;

$this_page_first_result = ($currentPage-1)*$threadLim;

$sql="SELECT * FROM comment WHERE userID=" . $userID . " ORDER BY commentTime DESC LIMIT " . $this_page_first_result . ',' . $threadLim;
$result = mysqli_query($conn, $sql);

echo '
<div style="position:absolute; left: 1000px; width:30%;">
<h2>Most recent comments</h2><br>
';

//////////PAGE NUMBER BUTTONS DISPLAY START//////////
echo '
<div>
';

//First and Previous buttons
if($currentPage>=3){
	echo '<a href="userprofile.php?userID=' . $userID . '&commentpage=' . '1' . '">' ."<button><<</button>" .'</a>';
}
if($currentPage > 1){
	echo '<a href="userprofile.php?userID=' . $userID . '&commentpage=' . $prev . '">' ."<button><</button>" .'</a>';
}

//Page number buttons	
if($currentPage<=3){
	for ($page=1;$page<=7;$page++) {
		// if page==currentPage, change colour
		if($page>=1){
			if($page==$currentPage){
				echo '<a href="userprofile.php?userID=' . $userID . '&postpage=' . $postpage . '&commentpage=' . $page . '">' . '<button style="background-color:#4CAF50">' . $page . '</button></a>';
			}
			else if ($page > 0 && $page <= $number_of_pages){
				echo '<a href="userprofile.php?userID=' . $userID . '&postpage=' . $postpage . '&commentpage=' . $page . '">' . '<button>' . $page . '</button></a>';
			}				
		}
	}	
}
else if($currentPage > $number_of_pages - 3){
	for($page = $number_of_pages - 6; $page <= $number_of_pages; $page++){
		// if page==currentPage, change colour
		if($page>=1){
			if($page==$currentPage){
				echo '<a href="userprofile.php?userID=' . $userID . '&postpage=' . $postpage . '&commentpage=' . $page . '">' . '<button style="background-color:#4CAF50">' . $page . '</button></a>';
			}
			else if ($page > 0 && $page <= $number_of_pages){
				echo '<a href="userprofile.php?userID=' . $userID . '&postpage=' . $postpage . '&commentpage=' . $page . '">' . '<button>' . $page . '</button></a>';
			}				
		}
	}
}
else{	
	for ($page=$currentPage-3;$page<=$currentPage+3;$page++) {
		// if page==currentPage, change colour
		if($page>=1){
			if($page==$currentPage){
				echo '<a href="userprofile.php?userID=' . $userID . '&postpage=' . $postpage . '&commentpage=' . $page . '">' . '<button style="background-color:#4CAF50">' . $page . '</button></a>';
			}
			else if ($page > 0 && $page <= $number_of_pages){
				echo '<a href="userprofile.php?userID=' . $userID . '&postpage=' . $postpage . '&commentpage=' . $page . '">' . '<button>' . $page . '</button></a>';
			}
		
		}
	}
}

//Next and Last buttons
if($currentPage < $number_of_pages){
	echo '<a href="userprofile.php?userID=' . $userID . '&postpage=' . $postpage . '&commentpage=' . $next . '">' .'<button>></button></a>';
}
if($currentPage <= $number_of_pages - 2){
	echo '<a href="userprofile.php?userID=' . $userID . '&postpage=' . $postpage . '&commentpage=' . $number_of_pages . '">' .'<button>>></button></a>';
}


echo '
</div>
';
//////////PAGE NUMBER BUTTONS DISPLAY END//////////

while($row = mysqli_fetch_assoc($result)) {
	
	$commentID = $row['postID'];
	$postID = $row['postID'];
	$query = "SELECT title FROM post WHERE postID=" . $postID;
	$title = mysqli_fetch_assoc(mysqli_query($conn, $query))['title'];
	$posterID = $row['userID'];
	$commentTime = $row['commentTime'];
	$query = "SELECT username FROM user WHERE userID=" . $posterID;
	$postUsername = mysqli_fetch_assoc(mysqli_query($conn, $query))['username'];
	//text processing for html format output
	$txt = nl2br($row['text']);
	//$txt = $row['text'];
	$txt2 = str_replace("[QUOTE]",
		'<!--div for quoted comment-->
		<div class="quote" style = "background-color: #E1E1E1;">
			<a>',
		$txt);
	$txt3 = str_replace("[/QUOTE]",
		'	</a>
		</div>
		<p>',
		$txt2);
	$content = $txt3 . '</p>';
	
	echo '
		<div class="w3-panel w3-border w3-round-small w3-padding-large" style="background-color: white; height: 208px;" >
			<a>in : <a href ="threadview.php?postID=' . $postID . '">' . $title . '</a>
			<div style = "font-size : 13px;">
				<a>by : <a href ="userprofile.php?userID=' . $posterID . '">' . $postUsername . '</a></a><br>
				<a>Comment Time: ' . $commentTime . '</a>
			</div>
	';
	
	echo $content;
	
	echo '
		</div>
	';
}

//////////PAGE NUMBER BUTTONS DISPLAY START//////////
echo '
<div>
';

//First and Previous buttons
if($currentPage>=3){
	echo '<a href="userprofile.php?userID=' . $userID . '&commentpage=' . '1' . '">' ."<button><<</button>" .'</a>';
}
if($currentPage > 1){
	echo '<a href="userprofile.php?userID=' . $userID . '&commentpage=' . $prev . '">' ."<button><</button>" .'</a>';
}

//Page number buttons	
if($currentPage<=3){
	for ($page=1;$page<=7;$page++) {
		// if page==currentPage, change colour
		if($page>=1){
			if($page==$currentPage){
				echo '<a href="userprofile.php?userID=' . $userID . '&postpage=' . $postpage . '&commentpage=' . $page . '">' . '<button style="background-color:#4CAF50">' . $page . '</button></a>';
			}
			else if ($page > 0 && $page <= $number_of_pages){
				echo '<a href="userprofile.php?userID=' . $userID . '&postpage=' . $postpage . '&commentpage=' . $page . '">' . '<button>' . $page . '</button></a>';
			}				
		}
	}	
}
else if($currentPage > $number_of_pages - 3){
	for($page = $number_of_pages - 6; $page <= $number_of_pages; $page++){
		// if page==currentPage, change colour
		if($page>=1){
			if($page==$currentPage){
				echo '<a href="userprofile.php?userID=' . $userID . '&postpage=' . $postpage . '&commentpage=' . $page . '">' . '<button style="background-color:#4CAF50">' . $page . '</button></a>';
			}
			else if ($page > 0 && $page <= $number_of_pages){
				echo '<a href="userprofile.php?userID=' . $userID . '&postpage=' . $postpage . '&commentpage=' . $page . '">' . '<button>' . $page . '</button></a>';
			}				
		}
	}
}
else{	
	for ($page=$currentPage-3;$page<=$currentPage+3;$page++) {
		// if page==currentPage, change colour
		if($page>=1){
			if($page==$currentPage){
				echo '<a href="userprofile.php?userID=' . $userID . '&postpage=' . $postpage . '&commentpage=' . $page . '">' . '<button style="background-color:#4CAF50">' . $page . '</button></a>';
			}
			else if ($page > 0 && $page <= $number_of_pages){
				echo '<a href="userprofile.php?userID=' . $userID . '&postpage=' . $postpage . '&commentpage=' . $page . '">' . '<button>' . $page . '</button></a>';
			}
		
		}
	}
}

//Next and Last buttons
if($currentPage < $number_of_pages){
	echo '<a href="userprofile.php?userID=' . $userID . '&postpage=' . $postpage . '&commentpage=' . $next . '">' .'<button>></button></a>';
}
if($currentPage <= $number_of_pages - 2){
	echo '<a href="userprofile.php?userID=' . $userID . '&postpage=' . $postpage . '&commentpage=' . $number_of_pages . '">' .'<button>>></button></a>';
}


echo '
</div>
';
//////////PAGE NUMBER BUTTONS DISPLAY END//////////

echo '
</div>
';

?>	

</body>
</html>