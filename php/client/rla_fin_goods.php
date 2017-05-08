<html>
<head>
<title>Goods List</title>
<LINK REL="StyleSheet" HREF="../style/style_admin.css" TYPE="text/css">
</head>

<body background="rlaemb.JPG" leftmargin="20">

<?php
//js
include('inventory_verify.inc');
include('str_decode_parse.inc');
include("rla_functions.inc");
include("chkgoods.inc");
include("regexp.inc");
include("userinfo.inc"); //$userinfo
$userstr	=	"?".base64_encode($userinfo."&listmethod=$listmethod");
$hr = "";
echo "<h2 align=center><a id=top>Goods List Management</a>";
echo "<a href=\"".$PHP_SELF."$userstr\"><font size=2>[Refresh]</font></a></h2>$hr";
include("connet_root_once.inc");

if ($goods_id) {
	$goods_id_from=$goods_id;
}

/* table: goodsid. columns: goods_id, name, description, supid, product_code, */
#################goods list Manipulation
if ($goodsmanipulation) {
	$ymd = date("Y-m-d");
	//echo "$goods_id<br>$modoption <br>";
	if ($goods_id && $modoption == "old") {
		// update goods: goodsid
		//check whether supplier changed
		$sql = "SELECT goods_id as id FROM rlafinance.goodsid 
			WHERE goods_id='$goods_id' and supid='$supid'";
		$result = mysql_query($sql);
		include("err_msg.inc");
		list($id) = mysql_fetch_array($result);
		
		if ($id && 0 < $price) { 
			// update goods main entry rlafinance.goodsid: 
			$sql = "UPDATE rlafinance.goodsid SET 
				name='$name', description='$description', 
				supid='$supid', product_code='$product_code' 
				WHERE goods_id='$goods_id';";
			$result = mysql_query($sql);
			include("err_msg.inc");
			//echo "$sql <br><br>";
			echo "<h3>Hi $first_name<br><br>Goods $name-$description has been successfully updated.</h3>";
			
			// update goods price rlafinance.priceref: 
			$sql = "SELECT goods_id as id, price as pri FROM rlafinance.priceref 
				WHERE goods_id='$goods_id' and ymd='$ymd'"; 
			$result = mysql_query($sql);
			include("err_msg.inc");
			list($id, $pri) = mysql_fetch_array($result);
			if ($id) { 
				//keep one price for day
				if ($pri == $price) {
					echo "<h3><font color=#ff0000>No price change.</font></h3>";
				} else {
					$sql = "UPDATE rlafinance.priceref 
						SET price='$price'
						WHERE goods_id='$goods_id' and ymd='$ymd'";
					$result = mysql_query($sql);
					include("err_msg.inc");
					echo "<h3>Price has also been successfully updated.</h3>";
				}
			} else {
				//check most current goods price to avoid duplicates
				$sql = "SELECT ymd as dmy, price as ptmp FROM rlafinance.priceref 
					WHERE goods_id='$goods_id' ORDER BY ptmp DESC LIMIT 1"; 
				$result = mysql_query($sql);
				include("err_msg.inc");
				list($dmy, $ptmp) = mysql_fetch_array($result);
				if ($price != $ptmp) {
					$sql = "INSERT INTO rlafinance.priceref SET goods_id='$goods_id', price='$price', ymd='$ymd'";
					$result = mysql_query($sql);
					include("err_msg.inc");
					echo "<h3>New price has also been inserted.</h3>";
				} else {
					echo "<h3><font color=#ff0000>No price change.</font></h3>";
				}
			}	
			echo "<hr>";
		} else {
			$new = "y";
		}
	}
	if ($goods_id && $modoption == "new") {
		include("rla_fin_goods_addnew.inc");
	}
	if (!$goods_id || $new == "y") {
		include("rla_fin_goods_addnew.inc");
	}
	echo $hr;
}

#################	 list current goods record
//$listmethod = "company";
if ($listmethod == "company") {
	$sql = "SELECT t2.company,
		t1.goods_id as id, t1.name, t1.description, t1.product_code 
		FROM rlafinance.goodsid as t1, rlafinance.supplierid as t2 
		WHERE t1.supid=t2.supid
		ORDER BY t2.company, t1.name";
} elseif ($listmethod == "goods") {
	$sql = "SELECT t2.company,
		t1.goods_id as id, t1.name, t1.description, t1.product_code 
		FROM rlafinance.goodsid as t1, rlafinance.supplierid as t2 
		WHERE t1.supid=t2.supid
		ORDER BY t1.name, t2.company";
}
	//echo $sql."<br>";
	$result = mysql_query($sql);
	include("err_msg.inc");
	$no = mysql_num_rows($result);
	
	if (!$no) {
		//echo "<h2>No record for goods by $listmethod.</h2>";
	} else {
		$tmpp = "<font color=#0000ff>".strtoupper($listmethod)."</font>";
		echo "<h2>Current Goods List by $tmpp</h2>";
		echo "<form name=goodsid>";
		echo '<input type="hidden" name="frm_str" value= "'.$userstr.'">';
		echo "<table border=0>";
		echo "<tr><th align=left>Goods List: $no</th></tr>";
		echo "<tr><td><select name=goods_id>";
		while (list($company, $id, $name, $description, $product_code) 
			= mysql_fetch_array($result)) {
			//echo "$company, $id, $name, $description, $product_code<br>";	
			$sql = "SELECT price, ymd FROM rlafinance.priceref
				WHERE goods_id='$id' ORDER BY ymd DESC limit 1;";
			$r = mysql_query($sql);
			list($price, $ymd ) =  mysql_fetch_array($r);
			$tmp = $name;
			if ($description) {
				$tmp .= "-$description";
			}
			if ($listmethod == "goods") {
				if ($price) {
					$tmp .= " ($company-".'$'."$price)";
				} else {
					$tmp .= " ($company)";
				}
			} else {
				$tmp = "$company: ".$tmp;
				if ($price) {
					$tmp .= " (".'$'."$price)";
				}
			}
			
			if ($id == $goods_id || $id == $goods_id_from) {
				echo "<option selected value=\"$id\">$tmp";
			} else {
				echo "<option value=\"$id\">$tmp";
			}
		}
		echo "</td></tr>";

	$sql = "SELECT description as destmp 
        FROM logging.sysmastertable 
        WHERE item='chgoods'";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($destmp) = mysql_fetch_array($result);

	if ($destmp == ereg_replace("$email_name", "" ,$destmp)) {
		$beabletochangegoods = "";
	} else {
		$beabletochangegoods = 1;
	}

	if ($beabletochangegoods) {
		echo "<tr><th align=left><font color=#ff0000>Modify Option</font></th></tr>";
		echo "<tr><td><select name=modoption>";
		if (!$modoption) {
			$modoption = "old";
		}
	
		if ($modoption == "old") {
			echo "<option value=\"old\" selected>Modify only";
			echo "<option value=\"new\">Modify then save it as new goods";
		} else {
			echo "<option value=\"old\">Modify only";
			echo "<option value=\"new\" selected>Modify then save it as new goods";
		}
		echo "</option></select>";

		//echo "</td></tr><tr><td align=center>";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;<button type=\"submit\" name=goodsmodify ><b>GO</b></button>";
		//echo "<input type=\"submit\" name=goodsdelete value=\"Delete\">";
		echo "</td></tr>";
	}
		echo "</table>";
		echo "</form>";
	}

################Modify goods list: collect data
if ($goodsmodify) {
	//echo "$goods_id, $name, $description, $supid, $product_code<br>";
	$sql = "SELECT name, description, supid, product_code FROM rlafinance.goodsid 
		WHERE goods_id='$goods_id';";
	//echo $sql."<br>";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($name, $description, $supid, $product_code) = mysql_fetch_array($result);
	
	/* table: priceref. columns: goods_id, price, ymd, */
	$sql = "SELECT price, ymd FROM rlafinance.priceref
		WHERE goods_id='$goods_id' ORDER BY ymd DESC limit 1;";
	//echo $sql."<br>";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($price, $ymd) = mysql_fetch_array($result);
}

#################	Form to Create or Modify New Goods
include("rla_fin_new_mod_goodslist.inc");
###################

echo "$hr<br><a href=#top>Back to top</a><br><br>";
?>
</html>
