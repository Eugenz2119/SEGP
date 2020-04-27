<?php
session_start();
session_unset();

?>

<!DOCTYPE html>
<html>
<head>

<link rel="stylesheet" type="text/css" href="login.css">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>AgriTalk</title>

</head>

<body>

	<div class="loginbox">
  
	<img src="./resources/placeholderimage.jpg" class="avatar" style="width: 30px; height:30px;">
  
		<h1>Login</h1>
    
		<form method="post">
    
			<p>Username</p>
      
			<input type="text" name="Username" placeholder="Enter Username">
      
			<p>Password</p>
      
			<input type="password" name="Password" placeholder="Enter Password">
      
			<input type="submit" value="Login">
      
			<a href="#">Lost your password?</a><br>
      
			<a href="accountcreation.php">Don't have an account?</a>
      
		</form>
    
	</div>
	 
</body>
</html>
<!- php ->
<?php
if(isset($_POST['Username']) && isset($_POST['Password'])){
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

	$username = $_POST['Username'];
	$password = $_POST['Password'];
	
	//debug
	echo "<br>";
	echo $username;
	echo "<br>";
	echo $password;
	echo "<br>";

	$sql = "SELECT userID FROM user WHERE username='$username' AND password='$password'";
	$result = mysqli_query($conn, $sql);

	$_SESSION["userID"] = mysqli_fetch_assoc($result)['userID'];

	if($_SESSION["userID"] != ''){ //valid login
		//debug
		echo "ACCOUNT FOUND";
		echo "<br>";
		echo "userID:" . $_SESSION["userID"];
		echo "<br>";
		
		//go to homepage
		echo '<meta http-equiv="Refresh" content="0; url=homepage.php" />';
	}
	else{ //invalid login
		echo '
		<script language="javascript">
			alert("Invalid login")
		</script>
		';
	}
	mysqli_close($conn);
}
?>
