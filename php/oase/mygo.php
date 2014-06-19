<?php
	$link = mysqli_connect("127.0.0.1" , "root" , "meigic1212") or die("Error " . mysqli_error($link));
	mysqli_select_db($link, "oase") or die ("no database"); 
	
	echo "  sssss";
	for($i = 1; $i<=24; $i++){
		if($i < 10)
		$sql = "UPDATE oase_product SET  pic_path =  'http://www.meigic.tw/oase/images/product/product0".$i."' WHERE p_id='$i' ;";
		else
		$sql = "UPDATE oase_product SET  pic_path =  'http://www.meigic.tw/oase/images/product/product".$i."' WHERE p_id='$i' ;";
		$query = mysqli_query($link, $sql);
echo "suck";
}

	
	mysqli_close($link);



?>