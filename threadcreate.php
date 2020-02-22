<!DOCTYPE html>
<html>
<head>
	<link href="./resources/css/threadcreate.css" type="text/css" rel ="stylesheet">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>AgriTalk</title>
	<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
</head>

<body>
	<?php include 'header.php';?>
	<?php include 'cropinfo.php';?>
	
	<header id="threadcreaterheader">
	<h1>Create Thread</h1>
	</header>
	
	<div id = "threadcreate">
		<form method="post">
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
if(isset($_POST['SUBMIT'])){
	
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
		echo "DB CONNECTED";
	}

	session_start();
	
	$userID = $_SESSION["userID"];
	$title = $_POST['TITLE'];
	$content = $_POST['CONTENT'];
	
	//Deal with images later
	/*
	// image uploading
	$target_dir = "uploads/";
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	$uploadOk = 1;
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	// Check if image file is a actual image or fake image
	if(isset($_GET["submit"])) {
		$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
		if($check !== false) {
			echo "File is an image - " . $check["mime"] . ".";
			$uploadOk = 1;
		} else {
			echo "File is not an image.";
			$uploadOk = 0;
		}
	}
	*/
	
	if(strlen($title) > 0 && strlen($content) > 0){
		
		$result = mysqli_query($conn, "SELECT CURRENT_TIMESTAMP()");
		$time = mysqli_fetch_assoc($result)['CURRENT_TIMESTAMP()'];
		$AddQuery = "INSERT INTO post (userID, title, text, postTime)
					 VALUES ('$userID', '$title', '$content', '$time')";
		
		if (mysqli_query($conn, $AddQuery)) {
			$sql = "SELECT postID FROM post WHERE userID='$userID' AND postTime='$time'";
			$result = mysqli_query($conn, $sql);

			$_SESSION["postID"] = mysqli_fetch_assoc($result)['postID'];
			echo '<meta http-equiv="Refresh" content="0; url=threadview.php" />';
		} else {
			echo "Error: " . $AddQuery . "<br>" . mysqli_error($conn);
		}
	}
	else{
		echo '
		<script language="javascript">
			alert("One or more field empty")
		</script>
		';
	}
	mysqli_close($conn);
}	
?>
</body>
</html>