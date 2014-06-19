<?php
require('config.php');
$username = $_POST['username'];
$password1 = $_POST['password1'];
$password2 = $_POST['password2'];
$email = $_POST['email'];
 
if($password1 != $password2)
    header('Location: registration.html');

if(strlen($username) > 30)
    header('Location: registration.html');
 
$hash = hash('sha256', $password1);
 
function createSalt()
{
    $text = md5(uniqid(rand(), true));
    return substr($text, 0, 3);
}

$salt = createSalt();
$password = hash('sha256', $salt . $hash);
 
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name); //we have added the database name called "login"
 
//mysql_select_db('login', $conn); //we remove this line
 
//sanitize username
$username = mysqli_real_escape_string($conn, $username);
 
$query = "INSERT INTO member ( username, password, email, salt ) VALUES 
		( '$username', '$password', '$email', '$salt' )";
 
//added $conn variable in order to connect to our database.
mysqli_query($conn, $query);
 
mysqli_close($conn);
 
header('Location: login.php');
?>