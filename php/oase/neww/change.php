<?php
	$book_no = $_GET["book_no"];
	$quantity = $_POST["quantity"];
	
	$book_no_array = explode(",", $_COOKIE["book_no_list"]);
	$book_name_array = explode(",", $_COOKIE["book_name_list"]);
	$price_array = explode(",", $_COOKIE["price_list"]);
	$quantity_array = explode(",", $_COOKIE["quantity_list"]);
	
	$key = array_search($book_no, $book_no_array);
	
	if($quantity == 0 || empty($quantity)){
		unset($book_no_array[$key]);
		unset($book_name_array[$key]);
		unset($price_array[$key]);
		unset($quantity_array[$key]);
	}else{
		$quantity_array[$key] = $quantity;
	}
	
	setcookie("book_no_list", implode(",", $book_no_array));
	setcookie("book_name_list", implode(",", $book_name_array));
	setcookie("price_list", implode(",", $price_array));
	setcookie("quantity_list", implode(",", $quantity_array));
	
	header("location:shopping_car.php");
?>