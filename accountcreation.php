<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="CreateAccount.css">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>AgriTalk</title>
</head>
<body>
	</div>
	<div class="Accbox">
	<img src="somethinglogo.png" class="avatar" alt="put agritalk logo">
		<h1>Create Account</h1><br>
		<form method="post">
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
			<p>Gender</p>
				<select name="Gender">
					<option value="male" selected>Male</option>
					<option value="female">Female</option>
					<option value="other">Other</option>
				</select>
			<input type="submit" value="Create Account" name="submit">
		</form>
	</div>

<!- php ->
<?php

if(isset($_POST['submit'])){
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
		//check if email already exist
		$sql = "SELECT COUNT(userID) FROM user WHERE email='$Email'";
		$result = mysqli_query($conn, $sql);
		if(strlen($Email) != 0){
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
		else{
			echo '
			<script language="javascript">
				alert("Email cannot be blank")
			</script>
			';
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
	
	//Gender
	if(isset($_POST['Gender'])){
		$Gender = $_POST['Gender'];
		$completeField += 1;
		echo 'gender complete';
		echo '<br>';
	}
	
	//all fields complete
	if($completeField == 6){
		$AddQuery = "INSERT INTO user (username, password, email, age, gender)
					 VALUES ('$Username', '$Password', '$Email', '$Age', '$Gender')";

		if (mysqli_query($conn, $AddQuery)) {
			echo '
			<script language="javascript">
				alert("Account created successfully")
			</script>
			';
			
			$sql = "SELECT userID FROM user WHERE username='$Username' AND password='$Password'";
			$result = mysqli_query($conn, $sql);
			$userID = mysqli_fetch_assoc($result)['userID'];
			
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
