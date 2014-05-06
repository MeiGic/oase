<?php
/************************************************************************\
|* Parameter:                                                           *|
|*   $table => the name of the table.                                   *|
|* Return Value:                                                        *|
|*   JSON encode which is convert from the imformation of the table.    *|
|* Exception:                                                           *|
|* 1. Error (if SQL connection fail).                                   *|
|* 2. Table is not exist (if the table isn't exist).                    *|    
|* By longbiau, Writen on 2014/05/06.                                   *|  
\************************************************************************/
function get_json_from_table($table){
	$link = mysql_connect("127.0.0.1" , "root" , "#password") or die("Error " . mysql_error($link));
	mysql_query("SET names 'utf8'");
	mysql_select_db('oase' , $link);
	$sql1 = "select count(*) from ".$table;
	$query1 = mysql_query($sql1 , $link);
	if($query1 == 0)
		die("Table is not exist!");
	$sql = "SELECT * from ".$table;
	$query = mysql_query($sql , $link);
	$json = array();
	for($i=0; $row = mysql_fetch_array($query , MYSQL_ASSOC); $i++)
		$json[$i] = $row;
	return json_encode($json);
}
?>
