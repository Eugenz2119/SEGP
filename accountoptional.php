<html>
<head>
<link rel="stylesheet" type="text/css" href="CreateAccount.css">
<title>AgriTalk</title>
</head>
<body>
	</div>
	<div class="Accbox">
	<img src="somethinglogo.png" class="avatar" alt="put agritalk logo">
		<h1>Create Account</h1><br>
		<form method="post" enctype="multipart/form-data">
			<p>Institution</p>
			<input type="text" name="Institution" placeholder="Enter Institution">
			<p>Occupation</p>
			<input type="text" name="Occupation" placeholder="Enter Occupation">
			<p>Country</p>
			<input type="text" name="Country" placeholder="Enter Country">
			<p>Phone Number</p>
			<input type="integer" name="PhoneNumber" placeholder="Enter Phone Number">
			<p>Select Profile Picture</p><br>
				<input type="file" name="profilepic" id="profilepic">
			<input type="submit" value="Skip" name="skip">
			<input type="submit" value="Submit" name="submit">
		</form>
	</div>

<?php

if(isset($_POST['submit'])){
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
	
	//create addquery
	$commaBefore = FALSE;
	$AddQuery = "UPDATE user SET ";
	//Institution
	if(isset($_POST['Institution'])){
		$commaBefore = TRUE;
		$Institution = $_POST['Institution'];
		$AddQuery = $AddQuery . "institution='$Institution'";
	}
	//Occupation
	if(isset($_POST['Occupation'])){
		if($commaBefore){
			$AddQuery = $AddQuery . ", ";
		}
		$commaBefore = TRUE;
		$Occupation = $_POST['Occupation'];
		$AddQuery = $AddQuery . "occupation='$Occupation'";
	}
	//Country
	if(isset($_POST['Country'])){
		if($commaBefore){
			$AddQuery = $AddQuery . ", ";
		}
		$commaBefore = TRUE;
		$Country = $_POST['Country'];
		$AddQuery = $AddQuery . "country='$Country'";
	}
	//Phone Number
	if(isset($_POST['PhoneNumber'])){
		if($commaBefore){
			$AddQuery = $AddQuery . ", ";
		}
		$commaBefore = TRUE;
		$PhoneNumber = $_POST['PhoneNumber'];
		$AddQuery = $AddQuery . "phonenum='$PhoneNumber'";
	}
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
		if($commaBefore){
			$AddQuery = $AddQuery . ", ";
		}
		$commaBefore = TRUE;
		$AddQuery = $AddQuery . "imageID='$imageID'";
	}
		
	$AddQuery = $AddQuery . " WHERE userID=" . $userID;
	
	//record to database
	if (mysqli_query($conn, $AddQuery)) {
		echo '
		<script language="javascript">
			alert("Details saved")
		</script>
		';
		
		echo '<meta http-equiv="Refresh" content="0; url=homepage.php" />';
	} else {
		echo "Error: " . $AddQuery . "<br>" . mysqli_error($conn);
	}

	mysqli_close($conn);
}

if(isset($_POST['skip'])){
	echo '<meta http-equiv="Refresh" content="0; url=homepage.php" />';
}

?>

</body>
</html>