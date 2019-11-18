<!DOCTYPE html>
<html>
<head>
	<link href="./resources/css/threadview.css" type="text/css" rel ="stylesheet">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>AgriTalk</title>
	<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
</head>

<body>
	<?php include 'header.php';?>
	<?php include 'cropinfo.php';?>

	<header id="threadtitle">
	<h1>Title</h1>
	</header>
	
	<div id="threadcontents" style="width:70%">
		<p style="padding-left: 40px;">Contents</p>
	</div>
	<button id="sharebutton">Share</button>
	

	<section id="threadcomments" style="width:70%">
		<div class="comments">
			<p>New Comment Inputs</p>
		</div>
		<div class="comments" id="existing">
			<p>Existing Comments</p>
		</div>
	</section>
	



</body>
</html>