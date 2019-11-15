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
		<form action="??.php" method="post">
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
				<select>
					<option value="male">Male</option>
					<option value="female">Female</option>
					<option value="other">Other</option>
				</select>
			<p>Select Profile Picture</p><br>
				<input type="file" name="picture" accept="image/*">
			<input type="submit" value="Create Account">
		</form>
	</div>
	 
</body>
</html>

<!- php ->
<?php
	$Username = $_POST['Username'];
	$Password = $_POST['Password'];
	$RepeatPassword = $_POST['RepeatPassword'];
	$Email = $_POST['Email'];
	$Age = $_POST['Age'];
	$Gender = $_POST['Gender'];
?>
