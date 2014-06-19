<?php
//CORS
// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
	header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
	header('Access-Control-Allow-Credentials: true');
	header('Access-Control-Max-Age: 86400');    // cache for 1 day
}
// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         

	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
		header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

	exit(0);
}

$sessionid = $_REQUEST['sessionid'];
if(isset($sessionid))
	session_id($sessionid);
session_start();
require('config.php');

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

/* check connection */
if (mysqli_connect_errno()) {
    echo "failed:database_connect_failed";
	die();
}

mysqli_set_charset($conn, "utf8");

$username = mysqli_real_escape_string($conn, $_POST['username']);
$password = mysqli_real_escape_string($conn, $_POST['password']);

if(empty($username) || empty($password)) {
	echo "failed:incorrect_request";
	die();
}

$query = "SELECT mem_id, account, password, salt
        FROM oase_member
        WHERE account = '$username';";

$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) == 0) // User not found. So, redirect to login_form again.
{
    //header('Location: login.html');
	echo "failed:user_not_found";
	die();
}
 
$userData = mysqli_fetch_assoc($result);
$hash = hash('sha256', $userData['salt'] . hash('sha256', $password) );
 
if($hash != $userData['password']) // Incorrect password. So, redirect to login_form again.
{
    //header('Location: login.html');
	echo "failed:wrong_password";
	die();
} else { // Redirect to home page after successful login.
	$_SESSION['user_is_loggedin'] = 1;
	$_SESSION['user_id'] = $userData['mem_id'];
	session_regenerate_id();
	//header('Location: home.php');
	echo "success:" . session_id();
}
session_write_close();
?>