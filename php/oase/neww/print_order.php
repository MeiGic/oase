<?php
	if(empty($_COOKIE["book_no_list"])){
		echo "<script type='text/javascript'>";
		echo "alert('你尚未選購任何產品');";
		echo "history.back();";
		echo "</script>";
	}
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>購物車</title>
	</head>
	<body bgcolor="lightyellow">
		<table border="1" bgcolor="white" rules="cols" align="center" cellpadding="5">
			<tr height="25">
				<td colspan="4" align="center" bgcolor="#CCCC00">個人資料</td>
			</tr>
			<tr height="25">
				<td colspan="4">姓名：<u><?php echo $_COOKIE["name"] ?>
					<?php for($i=0;$i<=100-2*strlen($_COOKIE["name"]); $i++) echo "&nbsp;"; ?></u>
				</td>
			</tr>
			<tr height="25">
				<td colspan="4">電話：<u><?php for($i=0;$i<=100;$i++) echo "&nbsp;";?></u>
				</td>
			</tr>
			<tr height="25">
				<td colspan="4">地址：<u><?php for($i=0;$i<=100;$i++) echo "&nbsp;";?></u>
				</td>
			</tr>
			<tr height="25">
				<td colspan="4">
					郵寄方式：xxxxx
				</td>
			</tr>
			<tr height="25">
				<td colspan="4">
					信用卡卡號：xxxxx
				</td>
			</tr>
			<tr height="25">
				<td colspan="4">
					有效日期：xxdddx
				</td>
			</tr>
			<tr height="25">
				<td colspan="4">
					簽名：sssss
				</td>
			</tr
			<tr height="25">
				<td colspan="4" align="center" bgcolor="#CCCC00">訂單細目</td>
			</tr>
			<tr height="25" align="center" bgcolor="#FFFF99">
				<td>書名</td>
				<td>定價</td>
				<td>數量</td>
				<td>小計</td>
			</tr>
			<?php
				$book_name_array = explode(",", $_COOKIE["book_name_list"]);
				$price_array = explode(",", $_COOKIE["price_list"]);
				$quantity_array = explode(",", $_COOKIE["quantity_list"]);
				for($i=0; $i<count($book_name_array);$i++){
					$sub_total = $price_array[$i]*$quantity_array[$i];
					
					$total += $sub_total;
					
					echo "<tr>";
					echo "<td align='center'>".$book_name_array[$i]."</td>";
					echo "<td align='center'>$".$price_array[$i]."</td>";
					echo "<td align='center'>".$quantity_array[$i]."</td>";
					echo "<td align='center'>$".$sub_total."</td>";
					echo "</tr>";
				}
			?>
		</table>
	</body>
</html>