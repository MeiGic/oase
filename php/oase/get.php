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

$type = $_REQUEST['type'];
if(empty($type)) {
	echo "failed:" . "incorrect_request";
	die();
}

$isloggedin = $_SESSION['user_is_loggedin'];
$user_id = mysqli_real_escape_string($conn, $_SESSION['user_id']);

if(!empty($isloggedin)) {
	if(!empty($user_id)) {
		$query = "SELECT *
				FROM oase_member
				WHERE mem_id = '$user_id';";
				
		$result = mysqli_query($conn, $query);
		
		if(mysqli_num_rows($result) == 0) // User not found. So, redirect to login_form again.
		{
			echo "failed:user_not_found";
			die();
		}
	} else {
		echo "failed:" . "user_id_not_found";
		die();
	}
} else {
	echo "failed:" . "user_not_loggedin";
	die();
}

$userData = mysqli_fetch_assoc($result);

switch($type) {
	case "name":
		echo "success:" . $userData['mem_name'];
		break;
	case "phone":
		echo "success:" . $userData['mem_phone'];
		break;
	case "address":
		echo "success:" . $userData['mem_address'];
		break;
	default:
		echo "failed:" . "incorrect_request";
		die();
}
session_write_close();
?>