<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Spares Order System - Administrator</title>
<style type="text/css">
</style>
<link href="Login.css" rel="stylesheet" type="text/css">


	<?php

	if (isset($_POST['email'])) {
	    login();
  	}
	function login(){
			session_start();
			$sql = "SELECT `password` FROM `administrator` WHERE email = '".$_POST['email']."';";
			require_once('Connections/conn.php');
			$rs = mysqli_query($conn,$sql);
			$rc = mysqli_fetch_assoc($rs);
			if($rc){
				if($_POST['pwd'] != ""){
					if($rc['password'] == $_POST['pwd']){
						$_SESSION['Account'] = $_POST['email'];
						$date = strtotime("+1 day");
						if(!isset($_SESSION['lastLogin'])){
							$_SESSION['oldLogin'] = $_SESSION['lastLogin'];
							$_SESSION['lastLogin'] = date("Y-m-d",$date);
						}else{
							$_SESSION['oldLogin'] =  date("Y-m-d",$date);
							$_SESSION['lastLogin'] = date("Y-m-d",$date);
						}
						
						if(!isset($_SESSION['modifyTime'])){
							$_SESSION['modifyTime'] = "never modify";
						}
						if(!isset($_SESSION['modifyPerson'])){
							$_SESSION['modifyPerson'] = "never modify";
						}
						header('Location: Main.php');
					}else{
						echo "<script type='text/javascript'>alert('Password or email is wrong.');</script>";
					}
				}else{
					echo "<script type='text/javascript'>alert('please input password.');</script>";
				}
			}else{
				echo "<script type='text/javascript'>alert('Password or email is wrong.');</script>";
			}
	}
	?>

</head>


<body>


	<div class="main">
		<div id="top"><div class="left">Spares Order System</div><div class="right" id="right"></div></div>

		<div class="container">
			<div class="leftBox">
				<img src="pic/logo2.png">
			</div>
			<div class="rightBox">

				<form method="POST">
					Email*<br>
					<input type="text" name="email" placeholder="Email"><br></span>
					Password*<br>
					<input type="password" name="pwd" placeholder="Password"><br></span><br><br>
					<input type="Submit" value="Login"> <a href="Login_forget.html">Forgot password</a>
				</form>
			</div>
		</div>

		<div class="footer">Copyright © 2019 SOS Ltd. All Rights Reserved.</div>
	</div>


<script>
	var d = new Date();
	var date = d.getFullYear()+"/"+month(d)+"/"+two(d.getDate())+" "+two(d.getHours())+":"+two(d.getMinutes());
	document.getElementById("right").innerHTML = date;

function month(d)
{ 
  return (d.getMonth() < 10 ? '0' : '') + (d.getMonth() + 1);
}

function two(d)
{ 
  return (d < 10 ? '0' : '') + d ;
}
</script>

</body>
</html>
