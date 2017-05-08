<html>
<?php	
	$sql = "SELECT goods_id as id, name, description
		FROM rlafinance.goodsid
		where supid='$supid'";
	//echo $sql;
	$result = mysql_query($sql);
	include("err_msg.inc");
	$no = mysql_num_rows($result);
	$nogs=0;
	$supgdsidpricestr = "";
	if ($no) {
		while (list($id, $name, $description) 
			= mysql_fetch_array($result)) {
			$sql = "SELECT price, ymd FROM rlafinance.priceref
				WHERE goods_id='$id' ORDER BY ymd DESC limit 1;";
			$r = mysql_query($sql);
			list($price, $ymd ) =  mysql_fetch_array($r);
			$supgdsidpricestr = $supgdsidpricestr."$id=$price@";
			$tmp = "$name";
			if ($description) {
				$tmp = "$tmp-$description";
			}
			if ($price) {
				$tmp = "$tmp-"."$".$price;
			}
			$glist[$nogs][0] = $id;
			$glist[$nogs][1] = $tmp;
			$prod[$id] = $tmp;
			//echo "$nogs-$id-$tmp<br>";
			$nogs++;
		}
	}
//echo "<br>$nogs<br>";
?>
</html>
