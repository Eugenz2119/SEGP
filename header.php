<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
<link href="./resources/css/index.css" type="text/css" rel ="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

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
//login check
if(isset($_SESSION["userID"])){
	$userID = $_SESSION["userID"];
}
?>

<body>
<div id="topbar" style="background-color: #73E600; height: 20%">

	<!-- search bar -->
	<input type="text" id="mySearch" placeholder="Search Crop..." title="type in the crop" style= "width:200px; left: 300px; top: 0px; position: absolute;">

	<!-- all subforums button -->
		<a href= "subforumlist.php" style ="position: absolute; top: 10px; right: 600px; font-size: 25px; color: white;">All Subforums</a>

	<!-- homepage shortcut -->
		<a href="homepage.php" style= "font-size: 30px; text-decoration: none; color: white;">AgriTalk</a>

	<?php
	if(isset($_SESSION["userID"])){
		//profile and account settings buttons
		echo '

			<a href="useraccount.php" class="fa fa-cogs" style = "float: right; font-size: 30px;color: white;"></a>

			<a href="userprofile.php?userID=' . $userID . '" style = "float: right; font-size: 20px; color: white;">username</a> 
		';
		
		//profile picture preview
		$sql = "SELECT imageID FROM user WHERE userID=" . $userID;
		$result = mysqli_query($conn, $sql);
		$imageID = mysqli_fetch_assoc($result)['imageID'];
		
		//display profile image
		if($imageID != NULL){
			$sql="SELECT format FROM image WHERE imageID =" . $imageID;
			$result = mysqli_query($conn, $sql);
			$imageFormat = mysqli_fetch_assoc($result)['format'];
			$image_dir = "uploads/" . $imageID . '.' . $imageFormat;
			
			echo '
			<img src = "'. $image_dir .'" alt= "avatarpreview" style="width: 30px; height:30px; float: right;">
			';
		}
		else{
			echo '
			<img src = "resources/placeholderimage.jpg" alt= "avatarpreview" style="width: 30px; height:30px; float: right;">
			';
		}
	}
	else{
		echo '
		<!--login-->
		  <div style = "float: right; font-size: 30px;color: white;">
			<a href="login.php">Login</a>
		  </div>
		';
	}

	mysqli_close($conn);
	?> 

	<!-- notification -->
	<div class="dropdown">
		<button onclick="drop()" class="dropbtn" style= "background-color: #73E600;"><i class="fa fa-bell"></i></button>
		<div id="notifs" class="dropdown-content">
			<a href="threadview.php">placeholder notification</a>
		</div>
	</div>
	
</div>

<script>
function drop() {
  document.getElementById("notifs").classList.toggle("show");
}

window.onclick = function(event) {
  if (!event.target.matches(".dropbtn")) {
    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains("show")) {
        openDropdown.classList.remove("show");
      }
    }
  }
}

</script>

</body>
</html>