<!DOCTYPE html>
<html>
<head>

<link rel="stylesheet" type="text/css" href="login.css">

<title>AgriTalk</title>

</head>

<body>

	<div class="loginbox">
  
	<img src="placeholder.png" class="avatar">
  
		<h1>Login</h1>
    
		<!- change php file name ->
    
		<form action="??.php" method:"post">
    
			<p>Username</p>
      
			<input type="text" name="Username" placeholder="Enter Username">
      
			<p>Password</p>
      
			<input type="password" name="Password" placeholder="Enter Password">
      
			<input type="submit" value="Login">
      
			<a href="#">Lost your password?</a><br>
      
			<a href="#">Don't have an account?</a>
      
		</form>
    
	</div>
	 
</body>
</html>
<!- php ->
<?php
	$Username = $_POST['Username'];
	$Password = $_POST['Password'];
?>
