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
// Inialize session
session_start();

$isloggedin = $_SESSION['user_is_loggedin'];
if(!empty($isloggedin)) {
	echo "failed:user_is_loggedin";
	die();
}

require('config.php');

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

/* check connection */
if (mysqli_connect_errno()) {
    echo "failed:database_connect_failed";
	die();
}

mysqli_set_charset($conn, "utf8");

$name = mysqli_real_escape_string($conn, $_POST['name']);
$birthday = mysqli_real_escape_string($conn, $_POST['birthday']);
$career = mysqli_real_escape_string($conn, $_POST['career']);
$phone = mysqli_real_escape_string($conn, $_POST['phone']);
$gender = mysqli_real_escape_string($conn, $_POST['gender']);
$address = mysqli_real_escape_string($conn, $_POST['address']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = mysqli_real_escape_string($conn, $_POST['password']);
$password_confirmation = mysqli_real_escape_string($conn, $_POST['password_confirmation']);

if(empty($name) || empty($email) || empty($password) || empty($password_confirmation)) {
	echo "failed:incorrect_request";
	die();
}

if($password != $password_confirmation) {
	echo "failed:password_confirmation_failed";
	die();
}
 
$hash = hash('sha256', $password);
 
function createSalt()
{
    $text = md5(uniqid(rand(), true));
    return substr($text, 0, 3);
}

$salt = createSalt();
$encryptpassword = hash('sha256', $salt . $hash);

//sanitize username
$email = mysqli_real_escape_string($conn, $email);

$checkquery = "SELECT account
        FROM oase_member
        WHERE account = '$email';";

$check = mysqli_query($conn, $checkquery);

if(mysqli_num_rows($check) > 0) // account must not found.
{
	echo "failed:account_name_overlap";
	die();
}

$jointime = date("Y-m-d", time());

$query = "INSERT INTO oase_member 
		( account, password, salt, jointime, mem_name, mem_phone, mem_gender, mem_address, career, mem_birthday ) VALUES 
		( '$email', '$encryptpassword', '$salt', '$jointime', '$name', '$phone', '$gender', '$address', '$career', '$birthday' )";
 
//added $conn variable in order to connect to our database.
$result = mysqli_query($conn, $query);
 
mysqli_close($conn);
 
if($result) {
	echo "success:" . time();
} else {
	echo "failed:insert_failed";
	die();
}
session_write_close();
?>