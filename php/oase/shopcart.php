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

$p_id = mysqli_real_escape_string($conn, $_POST['p_id']);
$so_id = mysqli_real_escape_string($conn, $_POST['so_id']);
$size = mysqli_real_escape_string($conn, $_POST['size']);
$temp = mysqli_real_escape_string($conn, $_POST['temp']);
$sweet = mysqli_real_escape_string($conn, $_POST['sweet']);
$count = mysqli_real_escape_string($conn, $_POST['count']);

$type = $_REQUEST['type'];
if(empty($type)) {
	echo "failed:" . "incorrect_request";
	die();
}

$temp_order = $_SESSION['temp_order'];
if(!isset($temp_order))
	$temp_order = array();

switch($type) {
	case "get_temp_order":
		$index = $_REQUEST['index'];
		if(empty($index))
			echo json_encode($temp_order);
		else
			echo json_encode($temp_order[$index - 1]);
		break;
	case "get_temp_order_count":
		echo "success:" . count($temp_order);
		break;
	case "add_temp_order":
		if(empty($p_id) || empty($size) || empty($temp) || empty($sweet) || empty($count)) {
			echo "failed:" . "incorrect_request";
			die();
		}
		$order = array(
			"p_id" => $p_id,
			"so_id" => $so_id,
			"size" => $size,
			"temp" => $temp,
			"sweet" => $sweet,
			"count" => $count
		);
		array_push($temp_order, $order);
		echo "success:" . session_id();
		break;
	case "remove_temp_order":
		$index = $_REQUEST['index'];
		if(empty($index))
			unset($temp_order);
		else
			unset($temp_order[$index - 1]);
		$temp_order = array_values($temp_order);
		echo "success:" . session_id();
		break;
	default:
		echo "failed:" . "incorrect_request";
		die();
}
$_SESSION['temp_order'] = $temp_order;
session_write_close();
?>