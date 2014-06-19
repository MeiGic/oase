<?php
session_start();
require('config.php');
function CheckCookieLogin() {
    $uname = $_COOKIE['uname'];
    if (!empty($uname)) {   
        $sql = "SELECT * FROM `member` WHERE `uid`='$uname'";
        $_SESSION['user_is_loggedin'] = 1;
        $_SESSION['cookie'] = $uname;
        // reset expiry date
        setcookie("uname", $uname, time()+3600*24*365, '/', 'www.meigic.tw/oase/go/home.php');
    }
}

if(!isset($_SESSION['cookie']) && empty($_SESSION['user_is_loggedin'])) {
	echo "cookie";
    CheckCookieLogin();
}

if($_SESSION['user_is_loggedin'] != 1 || !isset($_SESSION['sess_user_id'])){
	header("location: login.html");
}
/*
if(!isset($_SESSION['user_is_loggedin']) || $_SESSION['user_is_loggedin'] != 1){
	header("location: login.html");
}*/
 /*
//Check whether the session variable SESS_MEMBER_ID is present or not
if(!isset($_SESSION['sess_user_id']) || (trim($_SESSION['sess_user_id']) == '')) {
	header("location: login.html");
	exit();
}
*/


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Home Page</title>
</head>
 
<body>
<h1>Welcome, <?php

 echo 'suck<br>'; ?></h1>
 <form id="form1" name="form1" method="post" action="logout.php">
	<table width="510" border="0" align="center">
		<tr>
			<td><input type="submit" name="button" id="button" value="Logout" /></td>
		</tr>
	</table>
</form>
	<table width="510" border="0">
		<tr>
			<td>o_id </td>
			<td>product name</td>
			<td>price</td>
		</tr>
<?php

$conn = mysql_connect($db_host, $db_user, $db_pass);
mysql_select_db($db_name, $conn);

$username = mysql_real_escape_string($username);
$userid = $_SESSION['sess_user_id'];
echo $userid."<br>";
$query = "SELECT * FROM `order` WHERE mem_id = $userid";

$result = mysql_query($query);
	echo "eeee<br>";
while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	echo "ggggggggg<br>";
    echo "<tr>";
	echo "<td>".$row['o_id']."</td>";
	echo "<td>".$row['product']."</td>";
	echo "<td>".$row['price']."</td>";
	echo "</tr>";
}
?>
	</table>
</body>
</html>
