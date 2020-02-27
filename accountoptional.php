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
		<form method="post">
			<p>Institution</p>
			<input type="text" name="Institution" placeholder="Enter Institution">
			<p>Occupation</p>
			<input type="text" name="Occupation" placeholder="Enter Occupation">
			<p>Country</p>
			<input type="text" name="Country" placeholder="Enter Country">
			<p>Phone Number</p>
			<input type="integer" name="PhoneNumber" placeholder="Enter Phone Number">
			<p>Gender</p>
				<select name="Gender">
					<option value="male" selected>Male</option>
					<option value="female">Female</option>
					<option value="other">Other</option>
				</select>
			<p>Select Profile Picture</p><br>
				<input type="file" name="picture" accept="image/*">
			<input type="hidden" name="submit_pressed" value="True">
			<input type="submit" value="Create Account">
		</form>
	</div>
	 
</body>
</html>

<!- php ->
<?php

if(isset($_POST['submit_pressed'])){
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

	//Institution
	if(isset($_POST['Institution'])){
		$Institution = $_POST['Institution'];
		if(strlen($Age) != 0){
			$completeField += 1;
			echo 'Institution complete';
			echo '<br>';
		}
	}
	
	//Occupation
	if(isset($_POST['Occupation'])){
		$Occupation = $_POST['Occupation'];
		if(strlen($Occupation) != 0){
			$completeField += 1;
			echo 'Occupation complete';
			echo '<br>';
		}
	}
	
	//Country
	if(isset($_POST['Country'])){
		$Country = $_POST['Country'];
		if(strlen($Country) != 0){
			$completeField += 1;
			echo 'age complete';
			echo '<br>';
		}
	}
	
	//Phone Number
	if(isset($_POST['PhoneNumber'])){
		$PhoneNumber = $_POST['PhoneNumber'];
		if(strlen($PhoneNumber) != 0){
			$completeField += 1;
			echo 'Phone Number complete';
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
	if($completeField == 5){
		
		$AddQuery = "INSERT INTO user (institution, occupation, country, phonenumber, gender)
					 VALUES ('$Institution', '$Occupation', '$Country', '$PhoneNumber',  '$Gender')";
		
		if (mysqli_query($conn, $AddQuery)) {
			echo '
			<script language="javascript">
				alert("Account created successfully")
			</script>
			';
			
			$sql = "SELECT userID FROM user WHERE username='$Username' AND password='$Password'";
			$result = mysqli_query($conn, $sql);

			$_SESSION["userID"] = mysqli_fetch_assoc($result)['userID'];
			echo '<meta http-equiv="Refresh" content="0; url=homepage.php" />';
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