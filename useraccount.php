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

	//login check
	if(isset($_SESSION["userID"])){
		$userID = $_SESSION["userID"];
	}
	else{
		echo '<meta http-equiv="Refresh" content="0; url=login.php" />';
	}
	
	$sql = "SELECT * FROM user WHERE userID=" . $userID;
	$result = mysqli_query($conn, $sql);
	$row = mysqli_fetch_assoc($result);
	$Age = $row['age'];
	$Email = $row['email'];
	$Institution = $row['institution'];
	$Occupation = $row['occupation'];
	$Country = $row['country'];
	$PhoneNumber = $row['phonenum'];
	$currImageID = $row['imageID'];
	
	?>

	<div class = "settings">
		<div class = "types">
			<h3 class="categories">Settings Categories</h3>

			<div id="general" onclick="generalsetting()"style="background-color: #E1E1E1">
				<h4 class="gen">General</h4>
			</div>
			
			<div id="password" onclick="passwordsetting()">
				<h4 class="pass">Password</h4>
			</div>

			<div id="privacy" onclick="privacysetting()">
				<h4 class="priv">Privacy</h4>
			</div>
			<a class="logoutbtn" href="login.php">logout</a>
		</div>

		<div class = "specifics">
<?php
echo '
			<div id = "generalsetting">
				<form method="post" enctype="multipart/form-data">
					<p style="padding-left: 10px; font-size: 18px;">
					Change Profile Picture <br/>
					<input type="file" name="profilepic" id="profilepic"><br/>
					<button name="deletepic" type="submit" onclick="return confirm(\'Confirm delete?\')">Delete Profile Picture</button><br/><br/>
					
					Change Age(?) : <input type="integer" name="Age" value="' . $Age . '"><br/>
					Change Email : <input type="text" name="Email" value="' . $Email . '"><br/>
					Change Institution : <input type="text" name= "Institution" value="' . $Institution . '"><br/>
					Change Occupation : <input type="text" name= "Occupation" value="' . $Occupation . '"><br/>
					Change Country : <input type="text" name= "Country" value="' . $Country . '"><br/>
					Change Phone Number : <input type="integer" name= "PhoneNumber" value="' . $PhoneNumber . '"><br/>
					</p>
					
					<input type="submit" class="savechanges" value="Save Changes" name="gensavebtn">
					<input type="submit" class="cancelchanges" value="Cancel" name="cancelbtn">
					
				</form>
			</div>
			
';
?>
			
			<div id = "passwordsetting"  style="display: none;">
				<form method="post">
					<p style="padding-left: 10px; font-size: 18px;">
					
					Change Password <br/>
					Old Password 	: <input type="password" name="oldpw"><br/>
					New Password 	: <input type="password" name="newpw"><br/>
					Confirm New Password 	: <input type="password" name="cfmpw"><br/><br/>
					</p>
					
					<input type="submit" class="savechanges" value="Save Changes" name="passsavebtn">
					<input type="submit" class="cancelchanges" value="Cancel" name="cancelbtn">
				</form>
			</div>

			<div id = "privacysetting" style="display: none;">
				<p style ="font-size: 25px; padding-left:10px;">
					Show Age <label class="switch"><input type="checkbox" checked><span class="slider round"></span></label> <br/><br/>
					Show Email <label class="switch"><input type="checkbox" checked><span class="slider round"></span></label> <br/><br/>
					Show Institution <label class="switch"><input type="checkbox" checked><span class="slider round"></span></label> <br/><br/>
					Show Occupation <label class="switch"><input type="checkbox" checked><span class="slider round"></span></label> <br/><br/>
					Show Country <label class="switch"><input type="checkbox" checked><span class="slider round"></span></label> <br/><br/>
					Show Phone Number <label class="switch"><input type="checkbox" checked><span class="slider round"></span></label>
				</p>
			</div>
			
		</div>	
	</div>
	
<?php
if(isset($_POST['gensavebtn'])){
	$completeField = 0;
	$Age = $_POST['Age'];
	if(strlen($Age) != 0){
		$completeField += 1;
	}
	else{
		echo '
		<script language="javascript">
			alert("Age cannot be empty")
		</script>
		';
	}
	$Email = $_POST['Email'];
	if(strlen($Email) != 0){
		$completeField += 1;
	}
	else{
		echo '
		<script language="javascript">
			alert("Email cannot be empty")
		</script>
		';
	}
	$Institution = $_POST['Institution'];
	$Occupation = $_POST['Occupation'];
	$Country = $_POST['Country'];
	$PhoneNumber = $_POST['PhoneNumber'];
	
	if($completeField == 2){
		$UpdateQuery = "UPDATE user SET 
		age=$Age, 
		email='$Email', 
		institution='$Institution', 
		occupation='$Occupation', 
		country='$Country', 
		phonenum='$PhoneNumber' 
		WHERE userID=$userID
		";
		
		//Profile Picture
		if($_FILES["profilepic"]["name"] == ''){ //no file uploaded
			$fileSelected = 0;
			echo "notselected";
		}
		else{
			//generate current time
			$result = mysqli_query($conn, "SELECT CURRENT_TIMESTAMP()");
			$time = mysqli_fetch_assoc($result)['CURRENT_TIMESTAMP()'];
			
			$imageDone = 0;
			$fileSelected = 1;
			$uploadOk = 1;;
			$imageFileType = strtolower(pathinfo($_FILES["profilepic"]["name"],PATHINFO_EXTENSION));
			
			// Check if image file is a actual image or fake image
			$check = getimagesize($_FILES["profilepic"]["tmp_name"]);
			if($check !== false) {
				echo "File is an image - " . $check["mime"] . ".";
				$uploadOk = 1;
			} else {
				echo "File is not an image.";
				$uploadOk = 0;
			}
			// Check file size
			if ($_FILES["profilepic"]["size"] > 500000) {
				echo "Sorry, your file is too large.";
				$uploadOk = 0;
			}
			// Allow certain file formats
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
			&& $imageFileType != "gif" ) {
				echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
				$uploadOk = 0;
			}
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) {
				echo "Sorry, your file was not uploaded.";
			// if everything is ok, try to upload file
			} else {
				//create record in image table
				$ImageQuery = "INSERT INTO image (format, userID, uploadTime)
							 VALUES ('$imageFileType', '$userID' , '$time')";
				if(!mysqli_query($conn, $ImageQuery)){
					$imageDone = 0;
				}
				$sql = "SELECT imageID FROM image WHERE userID='$userID' AND uploadTime='$time'";
				$result = mysqli_query($conn, $sql);
				$imageID = mysqli_fetch_assoc($result)['imageID'];
				
				//image storage location
				$target_dir = "uploads/";
				$target_file = $target_dir . $imageID . '.' . $imageFileType;
				
				if (move_uploaded_file($_FILES["profilepic"]["tmp_name"], $target_file)) {
					$imageDone = 1;
					echo "The file ". basename($_FILES["profilepic"]["name"]). " has been uploaded.";
				} else {
					echo "Sorry, there was an error uploading your file.";
				}
			}
		}
		if($fileSelected == 1 && $imageDone == 1){
			$sql = "UPDATE user SET imageID=$imageID WHERE userID=$userID";
			mysqli_query($conn, $sql);
			
			if($currImageID != NULL){
				//Delete previous picture
				$result = mysqli_query($conn, "SELECT * FROM image WHERE imageID=$currImageID");
				$prevImg = mysqli_fetch_assoc($result);
				$delete_dir = "uploads/";
				$delete_file = $delete_dir . ($prevImg['imageID']) . '.' . ($prevImg['format']);
				unlink($delete_file);
				
				$DeleteQuery = "DELETE FROM image WHERE imageID=$currImageID";
				if(!mysqli_query($conn, $DeleteQuery)){
					echo "Error: " . $DeleteQuery . "<br>" . mysqli_error($conn);
				}
			}
		}
		
		
		if (mysqli_query($conn, $UpdateQuery)) {
			echo '
			<script language="javascript">
				alert("Account details updated successfully")
			</script>
			';
			echo '<meta http-equiv="Refresh" content="0; url=useraccount.php" />';
		} else {
			echo "Error: " . $UpdateQuery . "<br>" . mysqli_error($conn);
		}
	}
}

if(isset($_POST['deletepic'])){
	if($currImageID != NULL){
		$sql = "UPDATE user SET imageID=NULL WHERE userID=$userID";
		mysqli_query($conn, $sql);
		
		$result = mysqli_query($conn, "SELECT * FROM image WHERE imageID=$currImageID");
		$prevImg = mysqli_fetch_assoc($result);
		$delete_dir = "uploads/";
		$delete_file = $delete_dir . ($prevImg['imageID']) . '.' . ($prevImg['format']);
		unlink($delete_file);
		
		$DeleteQuery = "DELETE FROM image WHERE imageID=$currImageID";
		if(!mysqli_query($conn, $DeleteQuery)){
			echo "Error: " . $DeleteQuery . "<br>" . mysqli_error($conn);
		}
	}
	echo '<meta http-equiv="Refresh" content="0; url=useraccount.php" />';
}

if(isset($_POST['passsavebtn'])){
	$oldpw = $_POST['oldpw'];
	$newpw = $_POST['newpw'];
	$cfmpw = $_POST['cfmpw'];
	
	//fetch current password
	$sql = "SELECT password FROM user WHERE userID=$userID";
	$result = mysqli_query($conn, $sql);
	$currpw = mysqli_fetch_assoc($result)['password'];
	
	//compare current password and oldpw entered
	if($oldpw == $currpw){
		//compare both new passwords
		if($cfmpw == $newpw){
			//update password
			$sql = "UPDATE user SET password='$newpw' WHERE userID=$userID";
			if(mysqli_query($conn, $sql)){
				echo '
				<script language="javascript">
					alert("Password updated successfully")
				</script>
				';
				echo '<meta http-equiv="Refresh" content="0; url=useraccount.php" />';
			}
		}
		else{
			echo '
			<script language="javascript">
				alert("New passwords do not match")
			</script>
			';
		}
	}
	else{
		echo '
		<script language="javascript">
			alert("Old password incorrect")
		</script>
		';
	}
}
		

if(isset($_POST['cancelbtn'])){
	echo '<meta http-equiv="Refresh" content="0; url=useraccount.php" />';
}
?>
<script type="text/javascript">
function generalsetting(){
	document.getElementById("generalsetting").style.display = "block";
	document.getElementById("passwordsetting").style.display = "none";
	document.getElementById("privacysetting").style.display = "none";
	document.getElementById("general").style.backgroundColor = "#E1E1E1";
	document.getElementById("password").style.backgroundColor = "white";
	document.getElementById("privacy").style.backgroundColor = "white";
}	

function passwordsetting(){
	document.getElementById("generalsetting").style.display = "none"
	document.getElementById("passwordsetting").style.display = "block";
	document.getElementById("privacysetting").style.display = "none";
	document.getElementById("general").style.backgroundColor = "white";
	document.getElementById("password").style.backgroundColor = "#E1E1E1";
	document.getElementById("privacy").style.backgroundColor = "white";
}

function privacysetting(){
	document.getElementById("generalsetting").style.display = "none"
	document.getElementById("passwordsetting").style.display = "none";
	document.getElementById("privacysetting").style.display = "block";
	document.getElementById("general").style.backgroundColor = "white";
	document.getElementById("password").style.backgroundColor = "white";
	document.getElementById("privacy").style.backgroundColor = "#E1E1E1";
}


</script>
</body>
</html>