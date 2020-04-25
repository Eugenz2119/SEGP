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
<div style="position: absolute; right:-10px; top: 90px;">
	<img src = "' . $image_dir . '" alt= "crop image" style="width: 250px; height:auto; border-style: solid;">

	<div class="w3-container w3-padding-small w3-round-small" style ="width: 250px; border-style: solid;">
		<h3>'. $cropname .'</h3>
		<h5>'. $scname .'</h5>
	</div>

	<div class="w3-container w3-padding-small w3-round-small" style ="width: 250px; border-style: solid;">
		<p>'. $description .'</p>
	</div>
</div>
';

?>

</body>
</html>