<!DOCTYPE html>
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
		<!- change php file name ->
		<form method="post"  enctype="multipart/form-data">
			<p>Username</p>
			<input type="text" name="Username" placeholder="Enter Username">
			<p>Password</p>
			<input type="password" name="Password" placeholder="Enter Password">
			<p>Repeat Password</p>
			<input type="password" name="RepeatPassword" placeholder="Repeat Password">
			<p>Email</p>
			<input type="text" name="Email" placeholder="Enter Email">
			<p>Age</p>
			<input type="integer" name="Age" placeholder="Enter Age">
			<p>Select Profile Picture</p><br>
				<input type="file" name="picture" accept="image/*">
			<input type="submit" value="Create Account" name="submit">
		</form>
	</div>

<!- php ->
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

	//details completion check
	$completeField = 0;

	//username and password need at least 3 characters, age cant be empty as a temporary check
	//Username
	if(isset($_POST['Username'])){
		$Username = $_POST['Username'];
		
		if(strlen($Username) >= 3){
			//check if username already exist
			$sql = "SELECT COUNT(userID) FROM user WHERE username='$Username'";
			$result = mysqli_query($conn, $sql);
			if(mysqli_fetch_assoc($result)['COUNT(userID)'] != 0){
				echo '
				<script language="javascript">
					alert("Username already exist")
				</script>
				';
			}
			else{
				$completeField += 1;
				echo 'username complete';
				echo '<br>';
			}
		}
	}

	//Password
	if(isset($_POST['Password'])){
		$Password = $_POST['Password'];
		if(strlen($Password) >= 3){
			$completeField += 1;
			echo 'password complete';
			echo '<br>';
		}
	}

	//RepeatPassword
	if(isset($_POST['RepeatPassword'])){
		$RepeatPassword = $_POST['RepeatPassword'];
		if($RepeatPassword == $Password){
			$completeField += 1;
			echo 'rppass complete';
			echo '<br>';
		}
		else{
			echo '
			<script language="javascript">
				alert("Password do not match")
			</script>
			';
		}
	}

	//Email
	if(isset($_POST['Email'])){
		$Email = $_POST['Email'];
		//check if username already exist
		$sql = "SELECT COUNT(userID) FROM user WHERE email='$Email'";
		$result = mysqli_query($conn, $sql);
		if(mysqli_fetch_assoc($result)['COUNT(userID)'] != 0){
			echo '
			<script language="javascript">
				alert("Email already in use")
			</script>
			';
		}
		else{
			$completeField += 1;
			echo 'email complete';
			echo '<br>';
		}
	}

	//Age
	if(isset($_POST['Age'])){
		$Age = $_POST['Age'];
		if(strlen($Age) != 0){
			$completeField += 1;
			echo 'age complete';
			echo '<br>';
		}
	}
	
	//all fields complete
	if($completeField == 5){
		
		//generate current time
		$result = mysqli_query($conn, "SELECT CURRENT_TIMESTAMP()");
		$time = mysqli_fetch_assoc($result)['CURRENT_TIMESTAMP()'];
		
		//picture upload
		if($_FILES["profilepic"]["name"] == ''){ //no file uploaded
			$fileSelected = 0;
		}
		else{
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
				$AddQuery = "INSERT INTO image (format, userID, uploadTime)
							 VALUES ('$imageFileType', 0, '$time')"; //userID is temporarily 0 as userID not yet generated
				if(!mysqli_query($conn, $AddQuery)){
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
		
		if($imageDone == 0){
			$AddQuery = "INSERT INTO user (username, password, email, age, gender, imageID)
						 VALUES ('$Username', '$Password', '$Email', '$Age', '$Gender', NULL)";
		}
		else{
			$AddQuery = "INSERT INTO user (username, password, email, age, gender, imageID)
						 VALUES ('$Username', '$Password', '$Email', '$Age', '$Gender', '$imageID')";
		}
		
		if (mysqli_query($conn, $AddQuery)) {
			echo '
			<script language="javascript">
				alert("Account created successfully")
			</script>
			';
			
			$sql = "SELECT userID FROM user WHERE username='$Username' AND password='$Password'";
			$result = mysqli_query($conn, $sql);
			$userID = mysqli_fetch_assoc($result)['userID'];
			
			if($imageDone == 1){
				//update image record with userID
				$UpdateQuery = "UPDATE image SET userID=" . $userID . ' WHERE imageID=' . $imageID;
				mysqli_query($conn, $UpdateQuery);
			}			
			
			$_SESSION["userID"] = $userID;
			echo '<meta http-equiv="Refresh" content="0; url=accountoptional.php" />';
		} else {
			echo "Error: " . $AddQuery . "<br>" . mysqli_error($conn);
		}
	}
	else{
		echo '
		<script language="javascript">
			alert("Details incomplete")
		</script>
		';
	}

	mysqli_close($conn);

}
?>

</body>
</html>
