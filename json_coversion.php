<?php
/************************************************************************\
|* Parameter:                                                           *|
|*   ?type: The target table.                                           *|
|* Return Value:                                                        *|
|*   JSON encode which is convert from the information of the table.    *|
|* Exception:                                                           *|
|* 1. Error (if SQL connection fail).                                   *|
|* 2. Table is not exist (if the table isn't exist).                    *|
|* By longbiau, Writen on 2014/05/07.                                   *|
\************************************************************************/

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

$type = $_REQUEST['type'];
$id = $_REQUEST['id'];
switch($type){
	case 'productlist':
		echo get_json_from_table('oase_product', $id);
		break;
	case 'storelist':
		echo get_json_from_table('oase_unit', $id);
		break;
	default:
		echo 'The parameter is not accepted!<br>';
		break;
}
function get_json_from_table($table, $id){
	$link = mysqli_connect("127.0.0.1" , "root" , "#password") or die("Error " . mysqli_error($link));
	mysqli_select_db($link, "oase") or die ("no database"); 
	$link->set_charset('utf8');
	if($id == NULL)
		$sql1 = "SELECT count(*) from ".$table;
	else
		$sql1 = "SELECT count(".$id.") from ".$table;
	$query1 = mysqli_query($link, $sql1);
	if($query1 == 0)
		die("Table is not exist!");
	if($id == NULL)
		$sql = "SELECT * from ".$table;
	else
		$sql = "SELECT ".$id." from ".$table;
	$query = mysqli_query($link, $sql);
	$json = array();
	for($i=0; $row = mysqli_fetch_assoc($query); $i++) {
		$json['result'][$i] = $row;
	}
	mysqli_close($link);
	return json_encode($json);
}
?>
