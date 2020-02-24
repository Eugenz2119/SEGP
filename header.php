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

  <!-- search bar -->
  <input type="text" id="mySearch" placeholder="Search Crop..." title="type in the crop" style= "width:200px; left: 300px; top: 0px; position: absolute;">

<!-- create thread button -->
<div style = "position: absolute; left: 10px; top: 10px; font-size: 20px; color: #008000;" id="createthread">
<a href="threadcreate.php">Create Thread</a>
</div>

<!-- homepage shortcut -->

<div style= "text-align: center; font-size: 30px; text-decoration: none; color: #008000;">
<a href="homepage.php">AgriTalk</a>
</div>

<!-- notification -->
<div class="dropdown" style= "position: absolute; right:150px; top: 10px;">
		<button onclick="drop()" class="dropbtn"><i class="fa fa-bell"></i></button>
		<div id="notifs" class="dropdown-content">
			<a href="threadview.php">placeholder notification</a>
		</div>
</div>

<?php
if(isset($_SESSION["userID"])){
	//profile picture preview
	$sql = "SELECT imageID FROM user WHERE userID=" . $userID;
	$result = mysqli_query($conn, $sql);
	$imageID = mysqli_fetch_assoc($result)['imageID'];
	if($imageID != NULL){
		$sql="SELECT format FROM image WHERE imageID =" . $imageID;
		$result = mysqli_query($conn, $sql);
		$imageFormat = mysqli_fetch_assoc($result)['format'];
		$image_dir = "uploads/" . $imageID . '.' . $imageFormat;
		
		echo '
			<img src = "'. $image_dir .'" alt= "avatarpreview" style="position: absolute; width: 30px; height:30px; right:110px;top: 30px;">
		';
	}
	else{
		echo '
			<img src = "resources/placeholderimage.jpg" alt= "avatarpreview" style="position: absolute; width: 30px; height:30px; right:110px;top: 30px;">
		';
	}
	
	//profile and account settings buttons
	echo '
			<div style = "position: absolute; right :40px; top: 30px; font-size: 20px; color: #4CAF50;" id="useracc">
			<a href="userprofile.php?userID=' . $userID . '">Profile</a> 
			</div>

		  <div style = "position:absolute; top: 20px; right:0px; font-size: 30px;color: #4CAF50;">
			<a href="useraccount.php" class="fa fa-cogs"></a>
		  </div>
	';
}
else{
	echo '
		<!--login-->
		  <div style = "position:absolute; top: 20px; right:0px; font-size: 30px;color: #4CAF50;">
			<a href="login.php">Login</a>
		  </div>
	';
}

mysqli_close($conn);
?>

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