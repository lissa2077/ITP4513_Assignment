<!doctype html>
<html>
<script>
function submitf(){
	if(document.getElementById('partName').value != "" && (document.getElementById('price').value != ""&& document.getElementById('qty').value != "" ) ){
		document.getElementById('partID').disabled = false;
		document.getElementById('partf').submit();
	}else{
		alert('You must input all part information');
	}
}

function close(){
	parent.closeAndUpdate();
}
</script>
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
			$sql = "SELECT * FROM `part` WHERE `partNumber` = '".$_POST['partID']."';";
			$rs = mysqli_query($conn,$sql);
			$rc = mysqli_fetch_assoc($rs);
			if($rc){
				$sql = "Update `part` SET `email` = '".$_SESSION['Account']."',`partName` = '".$_POST['partName']."',`stockQuantity`=".$_POST['qty']." ,`stockPrice` = ".$_POST['price'].",`stockStatus` = ".$_POST['status']." WHERE `partNumber` = ".$_POST['partID'].";";
				$rs = mysqli_query($conn,$sql);
				echo "<script type='text/javascript'>alert('update');</script>";
				$date = strtotime("+1 day");
				$_SESSION['modifyTime'] = date("Y-m-d",$date);
				$_SESSION['modifyPerson'] = $lastName." ".$firstName;
				echo "<script type='text/javascript'>close();</script>";
			}else{
				$sql = "INSERT INTO `part` VALUES (".$_POST['partID'].",'".$_SESSION['Account']."','".$_POST['partName']."',".$_POST['qty'].",".$_POST['price'].",".$_POST['status'].",'','');";
				$rs = mysqli_query($conn,$sql);
				echo "<script type='text/javascript'>alert('New Part Add');</script>";
				$date = strtotime("+1 day");
				$_SESSION['modifyTime'] = date("Y-m-d",$date);
				$_SESSION['modifyPerson'] = $lastName." ".$firstName;
				echo "<script type='text/javascript'>close();</script>";
			}
		}
?>
<head>
<meta charset="utf-8">
<title>Manage Part</title>
<link href="Manage.css" rel="stylesheet" type="text/css">

</head>

<body>
		<div id="content">
			<h1>Add/Modify Part</h1>
			<form id="partf" name="partf" action="ManageModifyPart.php?update=1" method="post">
				<fieldset>
					<table>
						<tr>
							<th>Part Number</th>
							<td><input type="text" id="partID" name="partID"></td>
						</tr>
						<tr>
							<th>Part Name</th>
							<td><input type="text" id="partName" name="partName"></td>
						</tr>
						<tr>
							<th>Part Price</th>
							<td><input type="text" id="price" name="price"></td>
						</tr>
						<tr>
							<th>Qty</th>
							<td><input type="text" id="qty" name="qty"></td>
						</tr>
						<tr>
							<th>Status</th>
							<td><select id="status" name="status" >
								  <option value="0">Available</option>
								  <option value="1">Unavailable</option>
								</select>
							</td>
						</tr>
					</table>
					
					<br>
					<input type="button" value="Submit" onclick="javascript:submitf()">　<input type="button" onclick="javascript:parent.closeAndUpdate()" value="Cancel">
				</fieldset>
			</form>
		</div>
</body>
</html>
