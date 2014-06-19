<?php
	setcookie("name", $_POST["name"]);
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>購物車</title>
	</head>
	<frameset rows="60, *" board="0">
		<frame name="top" noresize scrolling="no" src="show_link.html">
		<frame name="bottom" noresize src="catalog.php">
	</frameset>		
</html>