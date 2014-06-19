<?php
require('config.php');

// Check for a cookie, if none go to login page
if (!isset($_COOKIE['session_id']))
{
    header('Location: login.php?refer='. urlencode(getenv('REQUEST_URI')));
}

// Try to find a match in the database
$guid = $_COOKIE['session_id'];
$con = mysql_connect($db_host, $db_user, $db_pass);
mysql_select_db($db_name, $con);

$query = "SELECT mem_id FROM oase_member WHERE guid = '$guid'";
$result = mysql_query($query, $con);

if (!mysql_num_rows($result))
{
    // No match for guid
	header('Location: login.php?refer='. urlencode(getenv('REQUEST_URI')));
}
?>