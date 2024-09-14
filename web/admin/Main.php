<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Manage Main</title>
<link href="Manage.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
	function menuclick() {
		var div1 = document.getElementById("nav");
		var div2 = document.getElementById("content");
		if(div1.style.display == "none"){
			div1.style.display = "block";
			div2.style.width = "75%";
		}else{
			div1.style.display = "none";
			div2.style.width = "100%";
		}
	}
</script>
<?php
		require_once('Connections/conn.php');
		session_start();
			$sql = "SELECT `firstName`,`lastName` FROM `administrator` WHERE email = '".$_SESSION['Account']."';";
			$rs = mysqli_query($conn,$sql);
			$rc = mysqli_fetch_assoc($rs);
			if($rc){

				$firstName = $rc['firstName'];
				$lastName = $rc['lastName'];
			}else{
				echo "<script type='text/javascript'>alert('login fail');</script>";
				header('Location: Login.php');
		}
		$sql = "SELECT count(`partNumber`),stockStatus FROM `part` group by `stockStatus`;";
		$rs = mysqli_query($conn,$sql);


		$availablePart = 0;
		$unavailablePart = 0;
		while($rc = mysqli_fetch_assoc($rs)){
			if($rc['stockStatus'] == 0){
				$availablePart = $rc['count(`partNumber`)'];
			}
			if($rc['stockStatus'] == 1){
				$unavailablePart = $rc['count(`partNumber`)'];
			}
		}

		$sql = "SELECT count(`orderID`),`status` FROM `orders`group by `status`;";
		$rs1 = mysqli_query($conn,$sql);
		$processOrder = 0;
		$deliveryOrder = 0;
		$completedOrder = 0;
		while($rc1 = mysqli_fetch_assoc($rs1)){
			if($rc1['status'] == 0){
				$processOrder = $rc1['count(`orderID`)'];
			}
			if($rc1['status'] == 1){
				$deliveryOrder = $rc1['count(`orderID`)'];
			}
			if($rc1['status'] == 2){
				$completedOrder = $rc1['count(`orderID`)'];
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
			<div class="menubutton" onclick="menuclick()">
				<img src="pic/menu.gif" width="35px" height="35px">
			</div>
			<h1>Main</h1>

			<fieldset>
				Last modified part record: <?php echo $_SESSION['modifyTime'] .", ".$_SESSION['modifyPerson'];?>
			</fieldset>
			<br>
			<h2>Part</h2>
			<ul>
				<li><?php echo $availablePart; ?> Available part,</li>
				<li><?php echo $unavailablePart; ?> Unavailable part</li>
			</ul>

			<h2>Order</h2>
			<ul>
				<li><?php echo $processOrder; ?> In Processing Order, </li>
				<li><?php echo $deliveryOrder; ?> Delivery Order,</li>
				<li><?php echo $completedOrder; ?> Completed Order</li>
			</ul>
		</div>
	</div>
</body>
</html>
