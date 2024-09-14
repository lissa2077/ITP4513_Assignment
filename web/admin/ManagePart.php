<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Manage Part</title>
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

function deletePart(){
	var r = confirm("Do you sure delete the part(s)?");
	if (r == true) {
		document.getElementById('modifyPartID').value = "";
		document.getElementById('cancel').value = "1";
		document.getElementById('f').submit();
	}
	
	
}

function deletePartbyid(id){
	
	document.getElementById('modifyPartID').value = id;
	document.getElementById('cancel').value = "1";
	document.getElementById('f').submit();
}

function closeAndUpdate(){
	var popup = document.getElementById("popup");
	popup.style.display= "none";
	document.getElementById('cancel').value = "0";
	document.getElementById('modifyPartID').value = "";
	document.getElementById('f').submit();
}

function fnPopup(id,name,qty,price,status){
	
	
	var popup = document.getElementById("popup");
	popup.style.display= "block";
	
	var ModifyPart = document.getElementById('ModifyPart');
	if(id == null){
		id = document.getElementById('newpartID').value;
		ModifyPart.contentDocument.getElementById('partID').disabled = true;
		ModifyPart.contentDocument.getElementById('partID').value = id;

	}else{
		ModifyPart.contentDocument.getElementById('partID').disabled = true;
		ModifyPart.contentDocument.getElementById('partID').value = id;
		ModifyPart.contentDocument.getElementById('partName').value = name;
		ModifyPart.contentDocument.getElementById('price').value = price;
		ModifyPart.contentDocument.getElementById('qty').value = qty;
		switch(status){
			case "0":
				ModifyPart.contentDocument.getElementById('status').selectedIndex = 0;
				break;
			case "1":
				ModifyPart.contentDocument.getElementById('status').selectedIndex = 1;
				break;
			default:
		}
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
		$sql = "SELECT `partNumber` FROM `part`;";
		$rs = mysqli_query($conn,$sql);
		
		if(isset($_POST['cancel'])){
			if(isset($_POST['modifyPartID']) && $_POST['modifyPartID'] != ""){
				$sql = "DELETE FROM `orderpart` WHERE `partNumber` = ".$_POST['modifyPartID'];
				$rs1 = mysqli_query($conn,$sql);
				$sql = "DELETE FROM `part` WHERE `partNumber` = ".$_POST['modifyPartID'];
				$rs1 = mysqli_query($conn,$sql);
				$date = strtotime("+1 day");	
				$_SESSION['modifyTime'] = date("Y-m-d",$date);	
				$_SESSION['modifyPerson'] = $lastName." ".$firstName;				
			}else{
				while($rc = mysqli_fetch_assoc($rs)){
					$key = "part".$rc['partNumber'];
					if(isset($_POST[$key])){
						$sql = "DELETE FROM `orderpart` WHERE `partNumber` = ".$rc['partNumber'];
						$rs1 = mysqli_query($conn,$sql);
						$sql = "DELETE FROM `part` WHERE `partNumber` = ".$rc['partNumber'];
						$rs1 = mysqli_query($conn,$sql);
						$date = strtotime("+1 day");
						$_SESSION['modifyTime'] = date("Y-m-d",$date);
						$_SESSION['modifyPerson'] = $lastName." ".$firstName;
					}
				}
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
						<div class="username"><a href="ManageAccount.php"><?php echo $lastName." ".$firstName; ?></a>
						</div>
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
			<h1>Part</h1>
			<div class="tablenav">
				<table>
					<tr>
						<td id="bordertd"><a href="ManagePart.php?fitter=0">All Part</a></td>
						<td id="bordertd"><a href="ManagePart.php?fitter=1">Available Part</a></td>
						<td ><a href="ManagePart.php?fitter=2">Unavailable Part</a></td>
					</tr>
				</table>
			</div>
			<button onclick="fnPopup()">Add Part</button> <button onclick="deletePart()">Delete Part</button>
			<br>
			<div class="tabledb">
			<form id="f" method="post" action="ManagePart.php<?php if(isset($_GET['fitter'])){ echo "?fitter=".$_GET['fitter'] ; }?>">
				<table>
					<tr>
						<th></th>
						<th>Part Number</th>
						<th>Part Name</th>
						<th>Qty</th>
						<th>Price</th>
						<th>Last modified</th>
						<th>Status</th>
						<th></th>
					</tr>
					<?php
					
					$sql = "SELECT `part`.*,`administrator`.`firstName`,`administrator`.`lastName` FROM `part`,`administrator` WHERE `part`.`email` = `administrator`.`email`";
						if(isset($_GET['fitter'])){
							if($_GET['fitter'] == 1){
								$sql = $sql."AND `part`.`stockStatus` = 0";
							}else{
								if($_GET['fitter'] == 2){
									$sql = $sql."AND `part`.`stockStatus` = 1";
								}
							}
						}
						$sql = $sql." Order by `part`.`partNumber` ";
						$count = 1;
						$rs = mysqli_query($conn,$sql);
						while($rc = mysqli_fetch_assoc($rs)){
							if($rc['partNumber'] == $count){
								$count++;
							}
							echo "<tr>";
							echo "<td><input type=\"checkbox\" id=\"part".$rc['partNumber']."\" name=\"part".$rc['partNumber']."\"></td>";
							echo "<td>".$rc['partNumber']."</td>";
							echo "<td>".$rc['partName']."</td>";
							echo "<td>".$rc['stockQuantity']."</td>";
							echo "<td>\$".$rc['stockPrice']."</td>";
							echo "<td>".$rc['lastName']." ".$rc['firstName']."</td>";
							if($rc['stockStatus'] == 0){
								echo "<td>Available</td>";
							}else{
								echo "<td>Unavailable</td>";
							}
							echo "<td><a href=\"javascript:fnPopup(".$rc['partNumber'].",'".$rc['partName']."','".$rc['stockQuantity']."','".$rc['stockPrice']."','".$rc['stockStatus']."')\">Modify</a>　<a href=\"javascript:deletePartbyid(".$rc['partNumber'].")\">Delete</a></td>";
							echo "</tr>";
						}
					?>
				</table>
				<input type="hidden" id="modifyPartID" name="modifyPartID">
				<input type="hidden" id="cancel" name="cancel">
				<input type="hidden" id="newpartID" name="newpartID" value="<?php echo $count; ?>">
				</form>
				<?php
					$sql = "SELECT count(`partNumber`) FROM `part`;";
					$rs = mysqli_query($conn,$sql);
					$rc = mysqli_fetch_assoc($rs);
				?>
				1-5 of <?php echo $rc['count(`partNumber`)']?> part | Page 1
			</div>
		</div>
		<div id="popup" style="background-color:rgba(0, 0, 0, 0.7);top:0px; left:0px;position: absolute;border:1px solid #606060; width:99%; height:100%; display:none;">
		<iframe id="ModifyPart" src="ManageModifyPart.php<?php if(isset($_GET['fitter'])){ echo "?fitter=".$_GET['fitter'] ; }?>" style="position: absolute;top:calc(50vh - 222px); left:calc(50vw - 305px);background-color:#FFFFFF;margin:0px 5px;width:620px;height:445px;"></iframe>
		</div>
	</div>
	
</body>
</html>
