<!DOCTYPE html>
<html>
<head>
	<link href="./resources/css/useracc.css" type="text/css" rel ="stylesheet">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>AgriTalk</title>
	<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
</head>
<body style="background-color: #E1E1E1";>
	<?php include "header.php"; ?>

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
	else{
		echo '<meta http-equiv="Refresh" content="0; url=login.php" />';
	}
	
	?>

	<div class = "settings">
		<div class = "types">
			<h3 class="categories">Settings Categories</h3>

			<div id="general" onclick="generalsetting()"style="background-color: #E1E1E1">
				<h4 class="gen">General</h4>
			</div>

			<div id="privacy" onclick="privacysetting()">
				<h4 class="priv">Privacy</h4>
			</div>
			<a class="logoutbtn" href="login.php">logout</a>
		</div>

		<div class = "specifics">
			<div id = "generalsetting">
				<p style="padding-left: 10px; font-size: 18px;">
				Change Password <br/>
				Old Password 	: <input type="password" name="oldpw"><br/>
				New Password 	: <input type="password" name="newpw"><br/><br/>
				Change Profile Picture <br/>
				<input type="file" name="profilepic" id="profilepic"><br/><br/>
				Change Age(?) : <input type="integer" name="Age"><br/>
				Change Email : <input type="text" name="Email"><br/>
				Change Institution : <input type="text" name= "Institution"><br/>
				Change Occupation : <input type="text" name= "Occupation"><br/>
				Change Country : <input type="text" name= "Country"><br/>
				Change Phone Number : <input type="integer" name= "PhoneNumber"><br/>
				</p>
			</div>


			<div id = "privacysetting" style="display: none;">
				<p style ="font-size: 25px; padding-left:10px;">
					Show Age <label class="switch"><input type="checkbox" checked><span class="slider round"></span></label> <br/><br/>
					Show Email <label class="switch"><input type="checkbox" checked><span class="slider round"></span></label> <br/><br/>
					Show Institution <label class="switch"><input type="checkbox" checked><span class="slider round"></span></label> <br/><br/>
					Show Occupation <label class="switch"><input type="checkbox" checked><span class="slider round"></span></label> <br/><br/>
					Show Country <label class="switch"><input type="checkbox" checked><span class="slider round"></span></label> <br/><br/>
					Show Phone Number <label class="switch"><input type="checkbox" checked><span class="slider round"></span></label> </p>
			</div>
		</div>

		<a class="cancelchanges"href="#">Cancel</a>
		<a class="savechanges"href="#">Save Changes</a>

	<!--profile picture
	$sql="SELECT imageID FROM user WHERE userID =" . $userID;
	$result = mysqli_query($conn, $sql);
	$imageID = mysqli_fetch_assoc($result)['imageID'];
	if($imageID != NULL){
		$sql="SELECT format FROM image WHERE imageID =" . $imageID;
		$result = mysqli_query($conn, $sql);
		$imageFormat = mysqli_fetch_assoc($result)['format'];
		$image_dir = "uploads/" . $imageID . '.' . $imageFormat;
	
		echo '
		<img src = "' . $image_dir . '" alt="user avatar" class="center" style="width: 250px; height:250px; border-style: solid;">
		';
	}
	else{
		echo '
		<img src = "resources/placeholderimage.jpg" alt="user avatar" class="center" style="width: 250px; height:250px; border-style: solid;">
		';
	}
	?>-->

	<!--account management 
	<div id="accountmanagement"class="w3-container w3-padding-small w3-round-small" style="width: 250px;border-style: solid; text-align: center;">
		<a href="login.php">logout</a><br>
		<a href="#">change password</a><br>
		<a href="#">change avatar</a>
	</div>-->
	
</div>

<script type="text/javascript">
function generalsetting(){
	document.getElementById("generalsetting").style.display = "block";
	document.getElementById("privacysetting").style.display = "none";
	document.getElementById("general").style.backgroundColor = "#E1E1E1";
	document.getElementById("privacy").style.backgroundColor = "white";
}	

function privacysetting(){
	document.getElementById("privacysetting").style.display = "block" ;
	document.getElementById("generalsetting").style.display = "none"
	document.getElementById("privacy").style.backgroundColor = "#E1E1E1";
	document.getElementById("general").style.backgroundColor = "white";
}


</script>
</body>
</html>