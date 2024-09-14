<!doctype html>
<html>
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
		
		
		
		
?>
<head>
<meta charset="utf-8">
<title>Manage Order</title>
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

function deleteOrder(){
	var r = confirm("Do you sure delete the order(s)?");
	if (r == true) {
		document.getElementById('modifyOrderID').value = "";
		document.getElementById('cancel').value = "1";
		document.getElementById('f').submit();
	}
	
	
}

function cancelOrder(id){
	
	document.getElementById('modifyOrderID').value = id;
	document.getElementById('cancel').value = "1";
	document.getElementById('f').submit();
}

function closeAndUpdate(){
	var popup = document.getElementById("popup");
	document.getElementById('cancel').value = "0";
	popup.style.display= "none";
	document.getElementById('ModifyOrder').value = "";
	document.getElementById('f').submit();
}

function fnPopup(id,dealerid,name,date,address,partno,qty,price,status,stockprice){
	
	document.getElementById('cancel').value = "0";
	var popup = document.getElementById("popup");
	popup.style.display= "block";
	
	var ModifyOrder = document.getElementById('ModifyOrder');
	if(id == null){
		id = document.getElementById('newrderID').value;
		ModifyOrder.contentDocument.getElementById('orderID').value = id;
		ModifyOrder.contentDocument.getElementById('orderID').disabled = true;
		ModifyOrder.contentDocument.getElementById('dealerName').disabled = true;
		ModifyOrder.contentDocument.getElementById('orderDate').disabled = true;
		var d = new Date();
		var date = d.getFullYear()+"-"+month(d)+"-"+day_of_the_month(d);
		ModifyOrder.contentDocument.getElementById('orderDate').value = date;
		ModifyOrder.contentDocument.getElementById('price').disabled = true;
		ModifyOrder.contentDocument.getElementById('price').hidden = true;
		ModifyOrder.contentDocument.getElementById('priceT').hidden = true;
		ModifyOrder.contentDocument.getElementById('dealerName').hidden = true;
		ModifyOrder.contentDocument.getElementById('dealerNameT').hidden = true;
	}else{
		ModifyOrder.contentDocument.getElementById('orderID').disabled = true;
		ModifyOrder.contentDocument.getElementById('orderID').value = id;
		ModifyOrder.contentDocument.getElementById('dealerID').disabled = true;
		ModifyOrder.contentDocument.getElementById('dealerID').value = dealerid;
		ModifyOrder.contentDocument.getElementById('dealerName').disabled = true;
		ModifyOrder.contentDocument.getElementById('dealerName').value = name;
		ModifyOrder.contentDocument.getElementById('orderDate').disabled = true;
		ModifyOrder.contentDocument.getElementById('orderDate').value = date;
		ModifyOrder.contentDocument.getElementById('address').value = address;
		ModifyOrder.contentDocument.getElementById('partID').disabled = true;
		ModifyOrder.contentDocument.getElementById('partID').value = partno;
		ModifyOrder.contentDocument.getElementById('qty').value = qty;
		ModifyOrder.contentDocument.getElementById('price').disabled = true;
		ModifyOrder.contentDocument.getElementById('price').value = price;
		ModifyOrder.contentDocument.getElementById('stockprice').value = stockprice;
		ModifyOrder.contentDocument.getElementById('oldqty').value = qty;
		switch(status){
			case "0":
				ModifyOrder.contentDocument.getElementById('status').selectedIndex = 0;
				break;
			case "1":
				ModifyOrder.contentDocument.getElementById('status').selectedIndex = 1;
				break;
			case "2":
				ModifyOrder.contentDocument.getElementById('status').disabled = true;
				ModifyOrder.contentDocument.getElementById('status').selectedIndex = 2;
				break;
			case "3":
				ModifyOrder.contentDocument.getElementById('status').disabled = true;
				ModifyOrder.contentDocument.getElementById('status').selectedIndex = 3;
				break;
			default:
		}
	}	
}

function month(d)
{ 
  return (d.getMonth() < 10 ? '0' : '') + (d.getMonth() + 1);
}

function day_of_the_month(d)
{ 
  return (d.getDate() < 10 ? '0' : '') + d.getDate() ;
}
</script>

</head>
<?php
		if((isset($_POST['modifyOrderID'])&& $_POST['modifyOrderID'] != "")&& $_POST['cancel'] == "1" ){
			$sql = "SELECT `orders`.`orderID`,`orders`.`status`,`orderpart`.`quantity`,`orderpart`.`partNumber`,`part`.`stockQuantity` FROM `orders`,`orderpart`,`part` WHERE `orders`.`orderID` = `orderpart`.`orderID` AND `orderpart`.`partNumber` = `part`.`partNumber` AND `orders`.`orderID` = ".$_POST['modifyOrderID'].";";
			$rs = mysqli_query($conn,$sql);
			$rc = mysqli_fetch_assoc($rs);
			if($rc['status'] == 0){
				$sql = "Update `orders` SET `status`= 1 WHERE `orderID` = ".$_POST['modifyOrderID'];
				$rs1 = mysqli_query($conn,$sql);
				$date = strtotime("+1 day");
				$_SESSION['modifyTime'] = date("Y-m-d",$date);
				$_SESSION['modifyPerson'] = $lastName." ".$firstName;
			}else{
				echo "<script type='text/javascript'>alert('Only \"In Processing\" can be changed to \"Delivery\". ');</script>";
			}
		}else{
			if(isset($_POST['cancel'])&& $_POST['cancel'] == "1"){
				$sql = "SELECT `orders`.`orderID`,`orders`.`status`,`orderpart`.`quantity`,`orderpart`.`partNumber`,`part`.`stockQuantity` FROM `orders`,`orderpart`,`part` WHERE `orders`.`orderID` = `orderpart`.`orderID` AND `orderpart`.`partNumber` = `part`.`partNumber`;";
				$rs = mysqli_query($conn,$sql);
				while($rc = mysqli_fetch_assoc($rs)){
					$key = "order".$rc['orderID'];
					$returnqty = $rc['quantity'];
					$qty = $rc['stockQuantity'];
					
					if(isset($_POST[$key])){
						$sql = "DELETE FROM `orderpart` WHERE `orderID` = ".$rc['orderID'];
						$rs1 = mysqli_query($conn,$sql);
						$sql = "DELETE FROM `orders` WHERE `orderID` = ".$rc['orderID'];
						$rs1 = mysqli_query($conn,$sql);
						if($rc['status'] != "3"){
							$sql = "Update `part` SET `stockQuantity` = ".($qty+$returnqty)." WHERE `partNumber` = ".$rc['partNumber'];
							$rs1 = mysqli_query($conn,$sql);
						}
						$date = strtotime("+1 day");
						$_SESSION['modifyTime'] = date("Y-m-d",$date);
						$_SESSION['modifyPerson'] = $lastName." ".$firstName;
					}
				}
			}
		}
?>
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
			<h1>Order</h1>
			<div class="tablenav">
				<table>
					<tr>
						<td id="bordertd"><a href="ManageOrder.php?fitter=0">All Order</a></td>
						<td id="bordertd"><a href="ManageOrder.php?fitter=1">In Processing Order</a></td>
						<td id="bordertd"><a href="ManageOrder.php?fitter=2">Delivery Order</a></td>
						<td id="bordertd"><a href="ManageOrder.php?fitter=3">Completed Order</a></td>
						<td ><a href="ManageOrder.php?fitter=4">Cancel Order</a></td>
					</tr>
				</table>
			</div>
			<a><button onclick="fnPopup()">Add Order</button></a><a><button onclick="deleteOrder()">delete Order</button></a>
			<br>
			<div class="tabledb">
			<form id="f" method="post" action="ManageOrder.php<?php if(isset($_GET['fitter'])){ echo "?fitter=".$_GET['fitter'] ; }?>">

				<table>
					<tr>
						<th></th>
						<th>Order ID</th>
						<th>Dealer ID</th>
						<th>Dealer Name</th>
						<th>Order Date</th>
						<th>Address</th>
						<th>Part Number</th>
						<th>Quantity</th>
						<th>Total Price</th>
						<th>Status</th>
						<th></th>
					</tr>
					<?php 
						$sql = "SELECT `orders`.*,`part`.`stockPrice`,`dealer`.`name`,`orderpart`.`price`,`orderpart`.`partNumber`,`orderpart`.`quantity` FROM `orders`,`dealer`,`orderpart`,`part` WHERE `orders`.`dealerID` = `dealer`.`dealerID` AND `orderpart`.`orderID`=`orders`.`orderID` AND `part`.`partNumber`=`orderpart`.`partNumber` ";
						if(isset($_GET['fitter'])){
							switch($_GET['fitter']){
								case 1:
								$sql = $sql." AND `orders`.`status` = 0 ";
								break;
								case 2:
								$sql = $sql." AND `orders`.`status` = 1 ";
								break;
								case 3:
								$sql = $sql." AND `orders`.`status` = 2 ";
								break;
								case 4:
								$sql = $sql." AND `orders`.`status` = 3 ";
								break;
							}
						}
						$sql = $sql."Order by `orders`.`orderID` ASC ";
						$rs = mysqli_query($conn,$sql);
						$count = 1;
						while($rc = mysqli_fetch_assoc($rs)){
							if($rc['orderID'] == $count){
								$count++;
							}
							echo "<tr>";
							echo "<td><input type=\"checkbox\" id=\"order".$rc['orderID']."\" name=\"order".$rc['orderID']."\"></td>";
							echo "<td>".$rc['orderID']."</td>";
							echo "<td>".$rc['dealerID']."</td>";
							echo "<td>".$rc['name']."</td>";
							echo "<td>".$rc['orderDate']."</td>";
							echo "<td>".$rc['deliveryAddress']."</td>";
							echo "<td>".$rc['partNumber']."</td>";
							echo "<td>".$rc['quantity']."</td>";
							echo "<td>\$".$rc['price']."</td>";
							switch($rc['status']){
								case 0:
									echo "<td>In Processing</td>";
								break;
								case 1:
									echo "<td>Delivery</td>";
								break;
								case 2:
									echo "<td>Completed</td>";
								break;
								case 3:
									echo "<td>Cancelled</td>";
								break;
							}
							echo "<td><a href=\"javascript:fnPopup(".$rc['orderID'].",'".$rc['dealerID']."','".$rc['name']."','".$rc['orderDate']."','".$rc['deliveryAddress']."','".$rc['partNumber']."','".$rc['quantity']."','".$rc['price']."','".$rc['status']."','".$rc['stockPrice']."');\">Modify</a>　<a href=\"javascript:cancelOrder(".$rc['orderID'].")\">Delivery</a></td>";
							echo "</tr>";
						}
					?>
					<input type="hidden" id="modifyOrderID" name="modifyOrderID">
					<input type="hidden" id="cancel" name="cancel" value="0">
					<input type="hidden" id="newrderID" name="newrderID" value="<?php echo $count; ?>">
				</table>
				</form>
				<?php
					$sql = "SELECT count(`orderID`) FROM `orders`;";
					$rs = mysqli_query($conn,$sql);
					$rc = mysqli_fetch_assoc($rs);
				?>
				1-3 of <?php echo $rc['count(`orderID`)']?> order | Page 1
			</div>
		</div>
		<div id="popup" style="background-color:rgba(0, 0, 0, 0.7);top:0px; left:0px;position: absolute;border:1px solid #606060; width:99%; height:100%; display:none;">
		<iframe id="ModifyOrder" src="ManageModifyOrder.php<?php if(isset($_GET['fitter'])){ echo "?fitter=".$_GET['fitter'] ; }?>" style="position: absolute;top:calc(50vh - 222px); left:calc(50vw - 305px);background-color:#FFFFFF;margin:0px 5px;width:620px;height:445px;"></iframe>

		</div>
	</div>
	
	

</body>
</html>
