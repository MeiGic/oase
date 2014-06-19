<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>購物車</title>
	</head>
	<body bgcolor="lightyellow">
		<table border="0" align="center" width="800" cellspacing="2">
			<tr bgcolor="#BABA76" height="30" align="center">
				<td>書號</td>
				<td>書名</td>
				<td>定價</td>
				<td>數量</td>
				<td>小計</td>
				<td>變更數量</td>
			</tr>
			<?php
				if(empty($_COOKIE["book_no_list"])){
					echo "<tr align='center'>";
					echo "<td colspan='6'>目前購物車內沒有任何商品及數量！</td>";
					echo "</tr>";
				}else{
					$book_no_array = explode(",", $_COOKIE["book_no_list"]);
					$book_name_array = explode(",", $_COOKIE["book_name_list"]);
					$price_array = explode(",", $_COOKIE["price_list"]);
					$quantity_array = explode(",", $_COOKIE["quantity_list"]);
					for($i=0;$i<count($book_no_array);$i++){
						$sub_total = $price_array[$i]*$quantity_array[$i];
						$total += $sub_total;
						
						echo "<form method='post' action='change.php?book_no=".$book_no_array[$i]."'>";
						echo "<tr bgcolor='#EDEAB1'>";
						echo "<td align='center'>".$book_no_array[$i]."</td>";
						echo "<td align='center'>".$book_name_array[$i]."</td>";
						echo "<td align='center'>".$price_array[$i]."</td>";
						echo "<td align='center'><input type='text' name='quantity' value='".$quantity_array[$i]."' size='5'></td>";
						echo "<td align='center'>$".$sub_total."</td>";
						echo "<td align='center'><input type='submit' value='修改'></td>";
						echo "</tr>";
						echo "</form>";
					}
					echo "<tr align='right' bgcolor='#EDEAB1'>";
					echo "<td colspan='6'>總金額 = ".$total."</td>";
					echo "</tr>";
					echo "<tr align='center'>";
					echo "<td colspan='6'>"."<br><input type='button' value='退回所有商品' onClick=\"javascript:window.open('delete_order.php','_self')\">'";
					echo "</td>";
					echo "</tr>";
				}
			?>
		</table>
	</body>
</html>