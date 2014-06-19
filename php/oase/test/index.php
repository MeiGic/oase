<?php
session_start();
//session.cookie_lifetime = 0;
//$_session['login'] = true;
//echo $_session['login']."<br>";
//echo $_session['num']."<br>";
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>會員</title>
		<script type="text/javascript">
			function check_data(){
				if(document.myForm.account.value.length == 0)
					alert("帳號欄位不可以空白！");
				else if(document.myForm.password.value.length == 0)
					alert("密碼欄位不可以空白！");
				else
					myForm.submit();
			}
		</script>
	</head>
	<body>
		<form action="checkpwd.php" method="post" name="myForm">
			<table width="40%" align="center">
				<tr>
					<td align="center">
						<font color="#3333FF">帳號：</font>
						<input name="account" type="text" size="15">
					</td>
				</tr>
				<tr>
					<td align="center">
						<font color="#3333FF">密碼：</font>
						<input name="password" type="password" size="15">
					</td>
				</tr>
			</table>
		</form>
		<p align="center">
			<a href="join.html">加入會員</a>
			<a href="search_pwd.html">查詢密碼</a>
		</p>
	</body>
</html>