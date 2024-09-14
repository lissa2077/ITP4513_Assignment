<?php
	$hostname = "127.0.0.1";
	$username = "root";
	$pwd = "P@\$\$word";
	$db = "projectDB";

	$conn = mysqli_connect($hostname,$username,$pwd,$db) or die(mysql_connect_error());
?>
