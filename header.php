<?php 
echo '<!DOCTYPE html>
<html>
<head>
<link href="./resources/css/index.css" type="text/css" rel ="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<!-- sidebar -->	
<div id="mySidenav" class="sidenav">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
  <a href="#">AgriTalk</a>
  <!-- search menu -->
  <input type="text" id="mySearch" onkeyup="myFunction()" placeholder="Search..." title="type in the crop">
  <ul id="cropMenu">
  	<li><a href="#">placeholder</a></li>
  	</ul>
  <span style="font-size:30px; position: absolute; left: 10px; cursor:pointer" onclick="openNav()">&#9776; </span>
 
</div>

<!-- notification -->
<div class="dropdown" style= "position: absolute; right:100px; top: 10px;">
		<button onclick="drop()" class="dropbtn"><i class="fa fa-bell"></i></button>
		<div id="notifs" class="dropdown-content">
			<a href="notif-placeholder">placeholder notification</a>
		</div>
</div>

<!-- account link -->
	<div style = "position: absolute; right :20px; top: 30px; font-size: 20px; color: #4CAF50;" id="useracc">
	<a href="user.php">Account</a> 
	</div>




<script>
function openNav() {
  document.getElementById("mySidenav").style.width = "250px";
}

function closeNav() {
  document.getElementById("mySidenav").style.width = "0";
}

function search(){

}

function drop() {
  document.getElementById("notifs").classList.toggle("show");
}

window.onclick = function(event) {
  if (!event.target.matches(".dropbtn")) {
    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains("show")) {
        openDropdown.classList.remove("show");
      }
    }
  }
}

</script>

</body>
</html>';?>