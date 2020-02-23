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

session_start();

$userID = $_SESSION['userID'];

mysqli_close($conn);
?>

<body>
<!-- sidebar -->	
<div id="mySidenav" class="sidenav">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
  <a href="homepage.php">AgriTalk</a>

  <!-- search menu -->
  <input type="text" id="mySearch" onkeyup="myFunction()" placeholder="Search..." title="type in the crop">
  <ul id="cropMenu">
  	<li><a href="cropsubforum.php">placeholder</a></li>
  	</ul>
 
</div>
<span style="font-size:30px; position: absolute; left: 10px; cursor:pointer" onclick="openNav()">&#9776; </span>

<!-- create thread button -->
<div style = "position: absolute; left :100px; top: 10px; font-size: 20px; color: #008000;" id="createthread">
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

<!-- avatar preview-->
<img src = "resources/placeholderimage.jpg" alt= "avatarpreview" style="position: absolute; width: 30px; height:30px; right:110px;top: 30px;">

<!-- profile link -->
	<div style = "position: absolute; right :40px; top: 30px; font-size: 20px; color: #4CAF50;" id="useracc">
	<a href=<?php echo "userprofile.php?userID=" . $userID;?>>Profile</a> 
	</div>

<!--account link-->
  <div style = "position:absolute; top: 20px; right:0px; font-size: 30px;color: #4CAF50;">
    <a href="useraccount.php" class="fa fa-cogs"></a>
  </div>




<script>
function openNav() {
  document.getElementById("mySidenav").style.width = "250px";
}

function closeNav() {
  document.getElementById("mySidenav").style.width = "0";
}

function search(){

}

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