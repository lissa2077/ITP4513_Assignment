<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Manage Account</title>
<link href="Manage.css" rel="stylesheet" type="text/css">
<script>
function showMenu(){
	var div1 = document.getElementById("nav");
	if(div1.style.display == "none"){
		div1.style.display = "block";
	}else{
		div1.style.display = "none";
	}
}

function submitform(){
	if(document.getElementById("newpwd").value == ""){
		alert('You must input the new password to change password');
		document.getElementById("newpwd").focus();
		return;
	}
	
	if(document.getElementById("oldpwd").value != ""&&document.getElementById("oldpwd2").value != ""){
		var pwd1 = document.getElementById("oldpwd").value;
		var pwd2 = document.getElementById("oldpwd2").value;
		var newpwd = document.getElementById("newpwd").value;
		if(pwd1 != pwd2){
			alert('the repeat password do not match');
			return;
		}
		if(pwd2 == newpwd || pwd1 == newpwd){
			alert('Please input new password');
			return;
		}else{
			document.getElementById('account').submit();
		}
	}else{
		alert('You must input the password to change account information');
	}
}
</script>
<?php
	session_start();
	$sql = "SELECT * FROM `administrator` WHERE email = '".$_SESSION['Account']."';";
	require_once('Connections/conn.php');
	$rs = mysqli_query($conn,$sql);
	$rc = mysqli_fetch_assoc($rs);
	if($rc){

		$firstName = $rc['firstName'];
		$lastName = $rc['lastName'];
	}else{
		echo "<script type='text/javascript'>alert('login fail');</script>";
		header('Location: Login.php');
	}
	if(isset($_POST['newpwd'])&&isset($_POST['oldpwd'])){
		if($rc['password'] == $_POST['oldpwd']){
			if(isset($_POST['newpwd'])&&$_POST['newpwd'] != ""){
				$sql = "Update `administrator` SET `password` = '".$_POST['newpwd']."' WHERE email = '".$_SESSION['Account']."';";
				$rs = mysqli_query($conn,$sql);
				header('Location: Login.php');
			}
		}else{
			"<script type='text/javascript'>alert('password wrong');</script>";
		}
	}
	
?>
</head>

<body>
	<div class="main">
		<div id="nav">
			<div style="height: 50px;"></div>
			<div class="menu">
				<div class="user">
					<div class="icon">
						<a href="ManageAccount.php"><img src="pic/icon.png" height="50" width="50"/></a>
					</div>
					<div class="username"><a href="ManageAccount.php"><?php echo $lastName." ".$firstName; ?></a></div>
				</div>
				<ul>
					<a href="Main.php"><li>Main</li></a>
					<a href="ManagePart.php"><li>Part</li></a>
					<a href="ManageOrder.php"><li>Order</li></a>
				</ul>
			</div>
			<div class="logout">
				<a href="Login.php"><img src="pic/logout.gif" height="35px" width="35px">LogOut</a>
			</div>
		</div>

		<div id="content">
			<div class="menubutton" onclick="showMenu()">
				<img src="pic/menu.gif" width="35px" height="35px">
			</div>
			<h1>Account Profile</h1>
			<fieldset>
				<form name="account" id="account" action="ManageAccount.php" method="post">
					<table>
						<tr>
							<td>Name:</td>
							<td><?php echo $lastName." ".$firstName; ?></td>
						</tr>
						<tr>
							<td>Email:</td>
							<td><?php echo $rc['email']; ?></td>
						</tr>
						<tr>
							<td>Last Login in:</td>
							<td><?php echo $_SESSION['oldLogin'];?></td>
						</tr>
						<tr>
							<td>Last modified part record:</td>
							<td><?php echo $_SESSION['modifyTime'] .", ".$_SESSION['modifyPerson'];?></td>
						</tr>
					</table>
					<br>

					</table>
					<br>
					<u>Modify Password:</u>
					<table>
						<tr>
							<td>New Password:</td>
							<td><input type="password" id="newpwd" name="newpwd"></td>
						</tr>
					</table>
					<br>
					Input password to confirm above modifications.
					<table>
						<tr>
							<td>Password:</td>
							<td><input type="password" id="oldpwd" name="oldpwd" required="required"></td>
						</tr>
						<tr>
							<td>Repeart Password:</td>
							<td><input type="password" id="oldpwd2" name="oldpwd2" required="required"></td>
						</tr>
					</table>
					<input type="button" onclick="javascript:submitform()" value="Submit" >
				</form>
			</fieldset>

		</div>
	</div>
</body>
</html>
