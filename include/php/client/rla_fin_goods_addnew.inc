<html>

<head>
<title></title>
</head>

<?php
	$i=0;
	$newgoodslist[$i][0] = $name; 
	$newgoodslist[$i][1] = $description; 
	$newgoodslist[$i][2] = $price; 
	$newgoodslist[$i][3] = $product_code; 
	for ($i=1; $i<100; $i++) {
		$namestr = "name$i";
		$descriptionstr = "description$i";
		$pricestr = "price$i";
		$product_codestr = "product_code$i";
		if (!$$namestr && !$$description) {
			break;
		} else {
			$newgoodslist[$i][0] = $$namestr ; 
			$newgoodslist[$i][1] = $$descriptionstr ; 
			$newgoodslist[$i][2] = $$pricestr ; 
			$newgoodslist[$i][3] = $$product_codestr; 
		}
	}
	$newgoods = $i;
	for ($i=0; $i<$newgoods; $i++) {
	/*
		echo $i.": ".$newgoodslist[$i][0] ."<br>" ; 
		echo $i.": ".$newgoodslist[$i][1] ."<br>" ; 
		echo $i.": ".$newgoodslist[$i][2] ."<br>" ; 
		echo $i.": ".$newgoodslist[$i][3] ."<br>";
	//*/
		$name = $newgoodslist[$i][0]; 
		$description = $newgoodslist[$i][1]; 
		$price = $newgoodslist[$i][2]; 
		$product_code = $newgoodslist[$i][3]; 

		//check existance of goods
		$sql = "SELECT goods_id FROM rlafinance.goodsid 
			WHERE name='$name' and description='$description' and supid='$supid';";
		$result = mysql_query($sql);
		include("err_msg.inc");
		list($goods_id) = mysql_fetch_array($result);
		
		if ($goods_id) {
			echo "<h3><font color=#ff0000>Goods $name ($description) to be added has been in the DB.</font></h3>";
			//<a onclick=\"backhistory(-1);\"><font color=#0000fff>Back to Previous Page.</font></a>
		} else {
			//add new goods
			$sql = "INSERT INTO rlafinance.goodsid VALUES('null', '$name', 
			'$description', '$supid', '$product_code');";
			
			$result = mysql_query($sql);
			include("err_msg.inc");
			if ($priv == "00") {echo "$sql<br>";}
			
			$goods_id= mysql_insert_id();
			if ($price>0) {
				$sql = "INSERT INTO rlafinance.priceref SET goods_id='$goods_id', price='$price', ymd='$ymd'";
				$result = mysql_query($sql);
				include("err_msg.inc");
				if ($priv == "00") {echo "$sql<br>";}
			}
			echo "<h3>New goods $name ($description) has been successfully added.</h3>";
		}
	}
?>
</html>
