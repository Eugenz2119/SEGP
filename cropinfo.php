<!DOCTYPE html>
<html>
<head>
	<link href="./resources/css/index.css" type="text/css" rel ="stylesheet">
</head>
<body>

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

if(isset($_GET['cropID'])){ //in cropsubforum
	$cropID = $_GET['cropID'];
}
else{
	echo '<meta http-equiv="Refresh" content="0; url=subforumlist.php" />';
}

$sql ='SELECT * FROM crop WHERE cropID=' . $cropID;
$result=mysqli_query($conn,$sql);
$row = mysqli_fetch_array($result);

$cropname = $row['cropname'];
$scname = $row['scname'];
$description = $row['description'];

//crop image
$imgname = $row['imgname'];
$image_dir = "cropimg/" . $imgname;

echo '
<div class ="info;" style="position: absolute; left: 80%; top: 90px; width:20%;height:80%">
	<img src = "' . $image_dir . '" alt= "crop image" style="border:solid; width: 100%; height:40%;">
		<div style= "border:solid;">
			<h3>'. $cropname .'</h3>
			<h5>'. $scname .'</h5>
			<p style="border-top:solid;">'. $description .'</p>
		</div>
</div>
';

?>

</body>
</html>