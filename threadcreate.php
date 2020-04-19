<!DOCTYPE html>
<html>
<head>
	<link href="./resources/css/threadcreate.css" type="text/css" rel ="stylesheet">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>AgriTalk</title>
	<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
</head>

<body style="background-color: #E1E1E1";>
	<?php include 'header.php';?>
	<?php include 'cropinfo.php';?>
	
	<header id="threadcreaterheader">
	<h1>Create Thread</h1>
	</header>
	
	<div id = "threadcreate">
		<form method="post" enctype="multipart/form-data">
		  <div>
		  	<label>TITLE:</label>
		  </div>
		  	<input type="text" name="TITLE" >
		  <div> 
		  	<label>CONTENT:</label>
		  </div>
		  <textarea  name="CONTENT" id = " CONTENT" rows = "3" cols = "80" placeholder="Your Thread Here"></textarea>
		  <div>
		  	<input type="file" name="fileToUpload" id="fileToUpload">
    	  	<input type="submit" value="Submit" name="SUBMIT">
    	  </div>
		</form>


	</div>

<?php
//login check
if(isset($_SESSION["userID"])){
	$userID = $_SESSION["userID"];
}
else{
	echo '<meta http-equiv="Refresh" content="0; url=login.php" />';
}

if(isset($_POST['SUBMIT'])){
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
	
	$title = $_POST['TITLE'];
	$content = $_POST['CONTENT'];
	
	//generate current time
	$result = mysqli_query($conn, "SELECT CURRENT_TIMESTAMP()");
	$time = mysqli_fetch_assoc($result)['CURRENT_TIMESTAMP()'];
	
	// images upload
	if($_FILES["fileToUpload"]["name"] == ''){ //no file uploaded
		$fileSelected = 0;
		$imageDone = 0;
	}
	else{
		$fileSelected = 1;
		$imageDone = 0;
		$uploadOk = 1;;
		$imageFileType = strtolower(pathinfo($_FILES["fileToUpload"]["name"],PATHINFO_EXTENSION));
		
		// Check if image file is a actual image or fake image
		$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
		if($check !== false) {
			echo "File is an image - " . $check["mime"] . ".";
			$uploadOk = 1;
		} else {
			echo "File is not an image.";
			$uploadOk = 0;
		}
		// Check file size
		if ($_FILES["fileToUpload"]["size"] > 500000) {
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
						 VALUES ('$imageFileType', '$userID', '$time')";
			if(!mysqli_query($conn, $AddQuery)){
				$imageDone = 0;
			}
			$sql = "SELECT imageID FROM image WHERE userID='$userID' AND uploadTime='$time'";
			$result = mysqli_query($conn, $sql);
			$imageID = mysqli_fetch_assoc($result)['imageID'];
			
			//image storage location
			$target_dir = "uploads/";
			$target_file = $target_dir . $imageID . '.' . $imageFileType;
			
			if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
				$imageDone = 1;
				echo "The file ". basename($_FILES["fileToUpload"]["name"]). " has been uploaded.";
			} else {
				echo "Sorry, there was an error uploading your file.";
			}
		}
	}
	
	//text upload
	$textDone = 0;
	if(strlen($title) > 0 && strlen($content) > 0){
		if($imageDone == 0){
			$AddQuery = "INSERT INTO post (userID, cropID, title, text, imageID, postTime, lastCommentTime)
						 VALUES ('$userID', '$cropID', '$title', '$content', NULL, '$time', '$time')";
		}
		else{
			$AddQuery = "INSERT INTO post (userID, cropID, title, text, imageID, postTime, lastCommentTime)
						 VALUES ('$userID', '$cropID', '$title', '$content', '$imageID', '$time', '$time')";
		}
	}
	else{
		echo '
		<script language="javascript">
			alert("One or more field empty")
		</script>
		';
	}
	
	if (mysqli_query($conn, $AddQuery)) {
		$textDone = 1;
		$sql = "SELECT postID FROM post WHERE userID='$userID' AND postTime='$time'";
		$result = mysqli_query($conn, $sql);
		$postID = mysqli_fetch_assoc($result)['postID'];
	} else {
		echo "Error: " . $AddQuery . "<br>" . mysqli_error($conn);
	}
	
	
	
	if($textDone == 1 && ($fileSelected == 0 || $imageDone == 1)){ //go to thread created		
		echo '<meta http-equiv="Refresh" content="0; url=threadview.php?postID=' . $postID . '" />';
	}
	else if($textDone == 0 && $imageDone == 1){ //text upload failed
		unlink($target_file); //delete image
	}
	else if($textDone == 1 && $fileSelected == 1 && $imageDone == 0){ //image upload failed
		if($uploadOk == 1){
			$DeleteQuery = "DELETE FROM image WHERE imageID=" . $imageID; //delete image record from db
			mysqli_query($conn, $DeleteQuery);
		}
		$DeleteQuery = "DELETE FROM post WHERE postID=" . $postID; //delete post from db
		mysqli_query($conn, $DeleteQuery);
	}
	
	mysqli_close($conn);
}	
?>
</body>
</html>