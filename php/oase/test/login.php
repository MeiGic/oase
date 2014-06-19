<?php
session_start();
require('config.php');
$username = $_POST['username'];
$password = $_POST['password'];
$autologin = $_POST['autologin'];

$conn = mysql_connect($db_host, $db_user, $db_pass);
mysql_select_db($db_name, $conn);
 
$username = mysql_real_escape_string($username);
$query = "SELECT id, uid, username, password, salt
        FROM member
        WHERE username = '$username';";

$result = mysql_query($query);

if(mysql_num_rows($result) == 0) // User not found. So, redirect to login_form again.
{
    header('Location: login.html');
}
 
$userData = mysql_fetch_array($result, MYSQL_ASSOC);
$hash = hash('sha256', $userData['salt'] . hash('sha256', $password) );
 
if($hash != $userData['password']) // Incorrect password. So, redirect to login_form again.
{
    header('Location: login.html');
}else{ // Redirect to home page after successful login.
	if($autologin == true){
		$cookiehash = md5(sha1(username . id));
		setcookie("uname", $cookiehash, time()+3600*24*365, '/', 'www.meigic.tw/oase/go/home.php');
		$sql = "UPDATE `member` SET `uid`='$cookiehash' WHERE `username`='$username'";
		mysql_query($sql);
	}
	$_SESSION['user_is_loggedin'] = 1;
	session_regenerate_id();
	//$_SESSION['sess_user_id'] = $userData['id'];
	//$_SESSION['sess_username'] = $userData['username'];
	session_write_close();
	header('Location: home.php');
}


?>