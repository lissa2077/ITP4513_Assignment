<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Manage Order</title>
<link href="Manage.css" rel="stylesheet" type="text/css">
<script>
function calPrice(){
	var qty = document.getElementById('qty').value;
	var stockprice = document.getElementById('stockprice').value;
	var total = qty * stockprice;
	document.getElementById('price').value = total.toFixed(2);
}

function submit(){
	if((document.getElementById('dealerID').value != ""&&document.getElementById('address').value != "")&&(document.getElementById('dealerID').value != ""&&document.getElementById('qty').value != "")){
		document.getElementById('orderID').disabled = false;
		document.getElementById('partID').disabled = false;
		document.getElementById('price').disabled = false;
		document.getElementById('status').disabled = false;
		document.getElementById('orderDate').disabled = false;
		document.getElementById('status').options["2"].disabled = false;
		document.getElementById('order').submit();
	}else{
		alert('You must input all order information');
	}
}

function close(){
	parent.closeAndUpdate();
}
</script>
</head>
<?php
	require_once('Connections/conn.php');
	session_start();
	$sql = "SELECT `password`,`firstName`,`lastName` FROM `administrator` WHERE email = '".$_SESSION['Account']."';";
	require_once('Connections/conn.php');
	$rs = mysqli_query($conn,$sql);
	$rc = mysqli_fetch_assoc($rs);
	if($rc){

		$firstName = $rc['firstName'];
		$lastName = $rc['lastName'];
	}
		if(isset($_GET['update'])&&$_GET['update'] == 1){
			$sql = "SELECT * FROM `orders` WHERE `orderID` = '".$_POST['orderID']."';";
			$rs = mysqli_query($conn,$sql);
			$rc = mysqli_fetch_assoc($rs);
			if($rc){
				$sql = "SELECT `stockQuantity` FROM `part` WHERE `partNumber` = '".$_POST['partID']."';";
				$rs = mysqli_query($conn,$sql);
				$rc = mysqli_fetch_assoc($rs);
				$partqty = ($rc['stockQuantity'] + $_POST['oldqty'] - $_POST['qty']);
				if($partqty >= 0){
				
					$sql = "Update `orderpart` SET `quantity` = ".$_POST['qty'].",`price` = " .$_POST['price']." WHERE `orderID` = ".$_POST['orderID'];
					$rs = mysqli_query($conn,$sql);
					$status = $_POST['status'];
					$sql = "Update `orders` SET `status` = ".$status." WHERE `orderID` = ".$_POST['orderID'];
					$rs = mysqli_query($conn,$sql);
					if($status == 3){
						$sql = "Update `part` SET `stockQuantity` = ".($partqty+$_POST['qty'])." WHERE `partNumber` = ".$_POST['partID'];
						$rs = mysqli_query($conn,$sql);
					}
					echo "<script type='text/javascript'>alert('Order is updated');</script>";
					echo "<script type='text/javascript'>close()</script>";
					$date = strtotime("+1 day");
					$_SESSION['modifyTime'] = date("Y-m-d",$date);
					$_SESSION['modifyPerson'] = $lastName." ".$firstName;
				}else{
					echo "<script type='text/javascript'>alert('Parts do not have enough numbers.');</script>";
				}
			}else{
				$sql = "SELECT `stockPrice`,`stockQuantity` FROM `part` WHERE `partNumber` = '".$_POST['partID']."';";
				$rs = mysqli_query($conn,$sql);
				if(mysqli_num_rows($rs)>0){
					$rc = mysqli_fetch_assoc($rs);
					$price = $rc['stockPrice'];
					$partqty = $rc['stockQuantity'] - $_POST['qty'];
					if($partqty >= 0){
						$sql = "SELECT * FROM `dealer` WHERE `dealerID` = '".$_POST['dealerID']."';";
						$rs = mysqli_query($conn,$sql);
						if(mysqli_num_rows($rs)>0){
							$sql = "INSERT INTO `orders` VALUES (".$_POST['orderID'].",	'".$_POST['dealerID']."','".$_POST['orderDate']."', '".$_POST['address']."', '".$_POST['status']."');";
							$rs = mysqli_query($conn,$sql);
							$sql = "INSERT INTO `orderpart` VALUES ('".$_POST['orderID']."', '".$_POST['partID']."', '".$_POST['qty']."', '".($_POST['qty']*$price)."');";
							$rs = mysqli_query($conn,$sql);
							$sql = "Update `part` SET `stockQuantity` = ".$partqty." WHERE `partNumber` = ".$_POST['partID'];
							$rs = mysqli_query($conn,$sql);
							echo "<script type='text/javascript'>alert('New Order has been added.');</script>";
							$date = strtotime("+1 day");
							$_SESSION['modifyTime'] = date("Y-m-d",$date);
							$_SESSION['modifyPerson'] = $lastName." ".$firstName;
						}else{
							echo "<script type='text/javascript'>alert('dealerID do not exist.');</script>";
						}
					}else{
						echo "<script type='text/javascript'>alert('Parts do not have enough numbers.');</script>";
					}
				}else{
					echo "<script type='text/javascript'>alert('Part number do not exist.');</script>";

				}
				echo "<script type='text/javascript'>close()</script>";
			}
		}
?>
<body>
		<div id="content">
			<h1>Add/Modify Order</h1>
				<fieldset>
				<form id="order" name="order" action="ManageModifyOrder.php?update=1" method="post">
				<table>
					<tr>
						<th>Order ID</th>
						<td><input type="text" id="orderID" name="orderID"></td>
					</tr>
					<tr>
						<th>Dealer ID</th>
						<td><input type="text" id="dealerID" name="dealerID"></td>
					</tr>
					<tr>
						<th id="dealerNameT">Dealer Name</th>
						<td><input type="text" id="dealerName" name="dealerName"></td>
					</tr>
					<tr>
						<th>Order Date</th>
						<td><input type="text" id="orderDate" name="orderDate"></td>
					</tr>
					<tr>
						<th>Address</th>
						<td><input type="text" id="address" name="address"></td>
					</tr>
					<tr>
						<th>Part Number</th>
						<td><input type="text" id="partID" name="partID"></td>
					</tr>
					<tr>
						<th>Quantity</th>
						<td><input type="text" id="qty" name="qty" onchange="calPrice()"></td>
					</tr>
					<tr>
						<th id="priceT">Total Price</th>
						<td><input type="text" id="price" name="price"><td>
					</tr>
					<tr>
						<th>Status</th>
						<td><select id="status" name="status">
							  <option value="0">In Processing</option>
							  <option value="1">Delivery</option>
							  <option value="2" disabled="disabled">Completed</option>
							  <option value="3" >Cancelled</option>
							</select>
						</td>
					</tr>
				</table>
				<input type="hidden" id="stockprice" name="stockprice">
				<input type="hidden" id="oldqty" name="oldqty">
				</form>
				<br>
				<input type="button" value="Sumbit" onclick="javascript:submit()">　<input type="button" onclick="parent.closeAndUpdate()" value="Cancel">
				</fieldset>
			
	</div>
</body>
</html>
