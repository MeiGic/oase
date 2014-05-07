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

$type = $_REQUEST['type'];
switch($type){
	case 'productlist':
		echo get_json_from_table('oase_product');
		break;
	case 'storelist':
		echo get_json_from_table('oase_unit');
		break;
	default:
		echo 'The parameter is not accepted!<br>';
		break;
}
function get_json_from_table($table){
	$link = mysqli_connect("127.0.0.1" , "root" , "#password") or die("Error " . mysqli_error($link));
	mysqli_select_db($link, "oase") or die ("no database"); 
	$link->set_charset('utf8');
	$sql1 = "select count(*) from ".$table;
	$query1 = mysqli_query($link, $sql1);
	if($query1 == 0)
		die("Table is not exist!");
	$sql = "SELECT * from ".$table;
	$query = mysqli_query($link, $sql);
	$json = array();
	for($i=0; $row = mysqli_fetch_assoc($query); $i++)
		$json[$i] = $row;
	mysqli_close($link);
	return json_encode($json);
}
?>
