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
	<!--<?php include 'cropinfo.php';?>-->
	
	<?php
	//login check
	if(isset($_SESSION["userID"])){
		$userID = $_SESSION["userID"];
	}
	else{
		echo '<meta http-equiv="Refresh" content="0; url=login.php" />';
	}

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

	//determine editing post or comment
	if(isset($_GET['postID'])){
		echo "edit post";
		$replyType = "post";
		$postID = $_GET['postID'];
	}

	if(isset($_GET['commentID'])){
		echo "edit comment";
		$replyType = "comment";
		$commentID = $_GET['commentID'];
	}

	if($replyType == "post"){
		$sql = "SELECT text FROM post WHERE postID=" . $postID;
	}
	if($replyType == "comment"){
		$sql = "SELECT text FROM comment WHERE commentID=" . $commentID;
		$imageID = NULL;
	}
	
	$result = mysqli_query($conn, $sql);
	$oriText = mysqli_fetch_assoc($result)['text'];
	
	//post image
	if($replyType == "post"){
		$sql = "SELECT imageID FROM post WHERE postID=" . $postID;
		$result = mysqli_query($conn, $sql);
		$imageID = mysqli_fetch_assoc($result)['imageID'];
		$sql = "SELECT format from image WHERE imageID='$imageID'";
		$imageFormat = mysqli_fetch_assoc(mysqli_query($conn, $sql))['format'];
		$imagePath = "uploads/$imageID.$imageFormat";
		
		if(isset($_GET['deletepic'])){
			$deletepic = $_GET['deletepic'];
		}
		else{
			$deletepic = false;
		}
	}
	
	?>
	
	<!--editing window-->
	<div>
<?php
if($imageID != NULL){
	if(!$deletepic){
		echo '
		<!-- image display -->
		<img src=' . $imagePath . ' alt="image" style="width:20%; height:auto">
		';
	}
}
?>
		<form method="post" enctype="multipart/form-data">
			<input type="file" name="postpic" id="postpic"><br/>
			<button name="deletepostpic" type="submit">Delete Picture</button><br/><br/>
		
			<?php
			echo '
			<!--text field-->
			<textarea name="editText" placeholder="Enter edited text here..." rows=6 cols=200>' . $oriText . '</textarea>
			';
			?>
			
			<!--submit button-->
			<div style = "right : 30px;">
				<input type="submit" value="Save" name="submit">
				<input type="submit" value="Cancel" name="cancel">
			</div>
		</form>
	</div>	

<?php
//creating new comment
if(isset($_POST["submit"])){
	
	//Post Picture
	if($_FILES["postpic"]["name"] == ''){ //no file uploaded
		$fileSelected = 0;
		echo "notselected";
		
		if($imageID != NULL){
			$sql = "UPDATE post SET imageID=NULL WHERE postID=$postID";
			mysqli_query($conn, $sql);
			
			$result = mysqli_query($conn, "SELECT * FROM image WHERE imageID=$imageID");
			$prevImg = mysqli_fetch_assoc($result);
			$delete_dir = "uploads/";
			$delete_file = $delete_dir . ($prevImg['imageID']) . '.' . ($prevImg['format']);
			unlink($delete_file);
			
			$DeleteQuery = "DELETE FROM image WHERE imageID=$imageID";
			if(!mysqli_query($conn, $DeleteQuery)){
				echo "Error: " . $DeleteQuery . "<br>" . mysqli_error($conn);
			}
		}
	}
	else{
		$currImageID = $imageID;
		
		//generate current time
		$result = mysqli_query($conn, "SELECT CURRENT_TIMESTAMP()");
		$time = mysqli_fetch_assoc($result)['CURRENT_TIMESTAMP()'];
		
		$imageDone = 0;
		$fileSelected = 1;
		$uploadOk = 1;;
		$imageFileType = strtolower(pathinfo($_FILES["postpic"]["name"],PATHINFO_EXTENSION));
		
		// Check if image file is a actual image or fake image
		$check = getimagesize($_FILES["postpic"]["tmp_name"]);
		if($check !== false) {
			echo "File is an image - " . $check["mime"] . ".";
			$uploadOk = 1;
		} else {
			echo "File is not an image.";
			$uploadOk = 0;
		}
		// Check file size
		if ($_FILES["postpic"]["size"] > 500000) {
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
			
			if (move_uploaded_file($_FILES["postpic"]["tmp_name"], $target_file)) {
				$imageDone = 1;
				echo "The file ". basename($_FILES["postpic"]["name"]). " has been uploaded.";
			} else {
				echo "Sorry, there was an error uploading your file.";
			}
		}
	}
	if($fileSelected == 1 && $imageDone == 1){
		$sql = "UPDATE post SET imageID=$imageID WHERE postID=$postID";
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
	
	//new text entered by user
	$newText = $_POST["editText"];

	//remove errors from inverted comma(s)
	$newText = str_replace("'", "\'", $newText);
	
	if(strlen($newText) > 0){
		//generate current time
		$result = mysqli_query($conn, "SELECT CURRENT_TIMESTAMP()");
		$time = mysqli_fetch_assoc($result)['CURRENT_TIMESTAMP()'];
		
		if($replyType == "post"){
			$UpdateQuery = "UPDATE post SET text='$newText' WHERE postID=$postID";
		}
		if($replyType == "comment"){
			$UpdateQuery = "UPDATE comment SET text='$newText' WHERE commentID=$commentID";
		}
		
		if (mysqli_query($conn, $UpdateQuery)) {
			
			//obtain postID that the comment belongs to
			if($replyType == "comment"){
				$sql = "SELECT postID FROM comment WHERE commentID=$commentID";
				$result = mysqli_query($conn, $sql);
				$postID = mysqli_fetch_assoc($result)['postID'];
			}
			
			echo '<meta http-equiv="Refresh" content="0; url=threadview.php?postID=' . $postID . '" />';
		}
		else{
			echo "Error: " . $UpdateQuery . "<br>" . mysqli_error($conn);
		}
	}
	else{
		echo '
		<script language="javascript">
			alert("Text is empty")
		</script>
		';
	}
}

if(isset($_POST["cancel"])){
	//obtain postID that the comment belongs to
	if($replyType == "comment"){
		$sql = "SELECT postID FROM comment WHERE commentID=$commentID";
		$result = mysqli_query($conn, $sql);
		$postID = mysqli_fetch_assoc($result)['postID'];
	}
	
	echo '<meta http-equiv="Refresh" content="0; url=threadview.php?postID=' . $postID . '" />';
}

if(isset($_POST['deletepostpic'])){
	echo '<meta http-equiv="Refresh" content="0; url=editwindow.php?postID=' . $postID . '&deletepic=true" />';
}

mysqli_close($conn);
?>
</body>
</html>