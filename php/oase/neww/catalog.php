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
				<td>輸入數量</td>
				<td>進行訂購</td>
			</tr>
			<?php
				
				$link = mysql_connect("127.0.0.1" , "root" , "meigic1212") or die("Error " . mysqli_error($link));
				mysql_query("SET NAMES 'UTF8'");
				mysql_select_db("store", $link) or die ("no database"); 

				$sql = "SELECT * FROM product_list";

				$result = mysql_query($sql, $link);
				
				$total_records = mysql_num_rows($result);
				
				for($i=0; $i<$total_records;$i++){
					$row = mysql_fetch_assoc($result);
					
					echo "<form method='post' action='add_to_car.php?book_no=".$row["book_no"]."&book_name=".urlencode($row["book_name"])."&price=".$row["price"]."'>";
					echo "<tr align='center' bgcolor='#EDEAB1'>";
					echo "<td>".$row["book_no"]."</td>";
					echo "<td>".$row["book_name"]."</td>";
					echo "<td>".$row["price"]."</td>";
					echo "<td><input type='text' name='quantity' size='5' value='1'></td>";
					echo "<td><input type='submit' value='放入購物車'></td>";
					echo "</tr>";
					echo "</form>";
				}
				
				mysql_free_result($result);
				mysql_close($link);
			?>
		</table>
	</body>
</html>