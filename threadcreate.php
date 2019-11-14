<!DOCTYPE html>
<html>
<head>
	<link href="./resources/css/index.css" type="text/css" rel ="stylesheet">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>AgriTalk</title>
	<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
</head>

<body>
	<?php include 'header.php';?>
	
	<header>
	<h1>Create Thread</h1>
	</header>
	
	<div>
		<form method="GET">
		  <div>
		  	<label>TITLE:</label>
		  </div>
		  <div>
		  	<input type="text" name="TITLE" >
		  </div>
		  <div> 
		  	<label>CONTENT:</label>
		  </div>
		  <div>
		  	<textarea> id = "myTextArea" rows = "3" cols = "80">Your text here</textarea>
		  </div>
		  <div>
		  	<input type="file" name="fileToUpload" id="fileToUpload">
    	  	<input type="submit" value="Submit" name="submit">
    	  </div>
		</form>


	</div>

<?php
	if(isset($_GET['submit'])){

	$code = $_GET['TITLE'];
	$language = $_GET['CONTENT'];
	// image uploading
	$target_dir = "uploads/";
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	$uploadOk = 1;
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	// Check if image file is a actual image or fake image
	if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}
}	
?>
</body>
</html>