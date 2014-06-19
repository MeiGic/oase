<?php
require('config.php');
		session_start(); 

$account = $_POST['account'];
$password = $_POST['password'];
$refer = $_POST['refer'];

if ($account == '' || $password == '')
{
    // No login information
    header('Location: login.php?refer='. urlencode($_POST['refer']));
}
else
{
    // Authenticate user
    $con = mysql_connect($db_host, $db_user, $db_pass);
    mysql_select_db($db_name, $con);
    
    $query = "SELECT mem_id, MD5(UNIX_TIMESTAMP() + mem_id + RAND(UNIX_TIMESTAMP()))
        guid FROM oase_member WHERE account = '$account' AND password = password('$password')";
        
    $result = mysql_query($query, $con)
    	or die ('Error in query1');
    
    if (mysql_num_rows($result))
    {
        $row = mysql_fetch_row($result);
        // Update the user record
        $query = "UPDATE oase_member SET guid = '$row[1]' WHERE mem_id = $row[0]";
        mysql_query($query, $con)
        	or die('Error in query2');
        
        // Set the cookie and redirect
        // setcookie( string name [, string value [, int expire [, string path
        // [, string domain [, bool secure]]]]])
        // Setting cookie expire date, 6 hours from now
		
		$cookieexpiry = (time() + 21600);
        setcookie("session_id", $row[1], $cookieexpiry);

        if (empty($refer) || !$refer)
        {
            $refer = 'index.php';
        }

        header('Location: '. $refer);
    }
    else
    {
        // Not authenticated
        header('Location: login.php?refer='. urlencode($refer));
    }
}
?>