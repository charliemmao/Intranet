<html>
<body background="rlaemb.JPG" leftmargin="20">
<?php
//js
include('inventory_verify.inc');
include('str_decode_parse.inc');
include("rla_functions.inc");
include("regexp.inc");
include("rla_fin_order.inc");
include("rla_fin_controldata.inc");

include("userinfo.inc"); //$userinfo
$userstr	=	"?".base64_encode($userinfo);

if ($action == "fullvieworder") {
	include("rla_fin_display_order.inc");
	exit;
}

echo "<h2 align=center><a id=top>Search Order by Keyword</a>";
echo "<a href=\"".$PHP_SELF."$userstr\"><font size=2>[Refresh]</font></a></h2><hr>";
include("connet_root_once.inc");

//#######################################################################
//***********Search Order Form
	echo "<form name=searchform method=post>";
	echo "<table>";
	/* table: goodsid. columns: goods_id, name, description, supid, product_code,  */
	
	$sql = "SELECT goods_id as gid, name as gdsname
		FROM rlafinance.goodsid";
	$result = mysql_query($sql);
	include("err_msg.inc");
	
	while (list($gid, $gdsname) = mysql_fetch_array($result)) {
		$gdsname = ereg_replace("'", " ", $gdsname);
		$gdsname = ereg_replace("-", " ", $gdsname);
		$gdsname = ereg_replace("\(", " ", $gdsname);
		$gdsname = ereg_replace("\)", " ", $gdsname);
		$gdsname = ereg_replace(",", " ", $gdsname);
		$gdsname = ereg_replace("/", " ", $gdsname);
		$gdsname = ereg_replace("\.", " ", $gdsname);
		$gdsname = ereg_replace("\+", " ", $gdsname);
		$gdsname = ereg_replace("\#", " ", $gdsname);
		$gdsname = ereg_replace("\"", " ", $gdsname);
		$tmp = explode(" ", $gdsname);
		for ($i=0; $i<count($tmp); $i++) {
			$str = $tmp[$i];
			if (strlen($str) > 3) {
				if (0 < $str) {
				} else {
					$str = strtolower($str);
					$kword[$str] .= $gid."=";
				}
			}
		}
	}
	ksort ($kword);
	reset ($kword);
	$i=0;
	echo "<tr><th align=left>Select Keyword</th>";
	echo "<td><select name=keywordgoods>";
	$i = 0;
	while (list ($key, $val) = each ($kword)) {
		if ($keywordgoods == $val) {
			echo "<option value=\"$val\" selected>$key";
			$kwordfrom = $key;
		} elseif ($i=0) {
			echo "<option value=\"$val\" selected>$key";
		} else {
			echo "<option value=\"$val\">$key";
		}
		$i++;
	}
	echo "</option></select></td></tr>";		
	//dateentry();
	
	echo "<tr><th colspan=2><button type=\"submit\" name=searchorder><b>Search</b></button></td></tr>";
	echo "</table><p>";

if ($searchorder) {
	echo "<hr><h2>Following goods contain keyword <font color=#0000ff>$kwordfrom</font></h2>";
	if ($priv == "00") {
		//echo "$keywordgoods <br><br>";
	}
	$gdidlist = explode("=", $keywordgoods);
	echo "<table border=1>";
	echo "<tr><th>No</th><th>Goods</th><th>Supplier</th><th>Order No</th></tr>";
	$j = 0;
	for ($i=0; $i<count($gdidlist); $i++) {
		$goods_id = $gdidlist[$i];
		if ($goods_id) {
			$sql = "SELECT t1.name as name, t1.description as des, t1.supid as idsup, 
					t2.company as company,
					t3.order_id as ordid,
					t4.order_by as order_by, t4.orderdate as orderdate
        		FROM rlafinance.goodsid as t1, rlafinance.supplierid as t2, 
        			rlafinance.orderdetails as t3, rlafinance.orderid as t4
        		WHERE t1.goods_id='$goods_id' and t1.supid=t2.supid and
        			t3.goods_id='$goods_id' and t3.supid=t1.supid and
        			t3.order_id=t4.order_id;";
        	//echo "$sql<br>";
            
			$result = mysql_query($sql);
			include("err_msg.inc");
			$nofound = 0;
			while (list($name, $des, $idsup, $company, $ordid, $order_by, $orderdate) = mysql_fetch_array($result)) {
				if (!$ordid) {
					$ordid = "---";
				}
				$j++;
				$nofound++;
				$userstr	=	"?".base64_encode($userinfo."&action=fullvieworder&order_id=$ordid");
				echo "<tr><td>$j</td><td>$name<br>$des</td><td>$company</td><td width=90>
				<a href=\"rla_fin_order_fullview.php$userstr\" target=\"_blank\">$ordid</a> $order_by<br>$orderdate</td></tr>";
			}
			
			if (!$nofound) {
				$sql = "SELECT t1.name as name, t1.description as des,
						t2.company as company, t2.address as address, t2.contactperson as cont,
						t2.telno as tel, t2.faxno as fax
        			FROM rlafinance.goodsid as t1, rlafinance.supplierid as t2 
        			WHERE t1.goods_id='$goods_id' and t1.supid=t2.supid;";

       		//echo "$sql<br>";
				$result = mysql_query($sql);
				include("err_msg.inc");
				list($name, $des, $company, $address , $cont, $tel, $fax) = mysql_fetch_array($result);
				$j++;
				$userstr	=	"?".base64_encode($userinfo."&action=fullvieworder&order_id=$ordid");
				$supstr = "";
				if ($cont) {
					$supstr .= "Contact: $cont<br>";
				}
				if ($tel) {
					$supstr .= "Tel: $tel<br>";
				}
				if ($fax) {
					$supstr .= "Fax: $fax";
				}
				echo "<tr><td>$j</td><td>$name<br>$des</td><td>
					$company<br>$address<br>$supstr</a></td><td align=middle>---</td></tr>";
			}
		}
	}
	echo "</table><p>";
}

echo "<hr><br><a href=#top>Back to top</a><br><br>";
function dateentry() {
	echo "<tr><th align=left>Period</th>";
		if (!$mthst) {
			$mthst = date("m");
		}
		if (!$yearst) {
			$yearst = date("Y");
		}
		if (!$mthed) {
			$mthed = date("m");
		}
		if (!$yeared) {
			$yeared = date("Y");
		}
		$yearfrom = 2001;
		$yearto = date("Y");
		if (20 < $yearto - $yearfrom) {
			$yearfrom = $yearto - 20;
		}
		
		echo "<td><b>From&nbsp;</b><select name=mthst>";
		for ($i=1; $i<=12; $i++) {
			if ($i == $mthst) {
				echo "<option value=$i selected>".$mths[$i];
			} else {
				echo "<option value=$i>".$mths[$i];
			}
		}
		echo "</option></select>";
		echo "<select name=yearst>";
		for ($i=$yearfrom; $i<=$yearto ; $i++) {
			if ($i == $yearst) {
				echo "<option value=$i selected>".$i;
			} else {
				echo "<option value=$i>".$i;
			}
		}
		echo "</option></select>";
		
		echo "<b>&nbsp;To&nbsp;</b><select name=mthed>";
		for ($i=1; $i<=12; $i++) {
			if ($i == $mthed) {
				echo "<option value=$i selected>".$mths[$i];
			} else {
				echo "<option value=$i>".$mths[$i];
			}
		}
		echo "</option></select>";
		echo "<select name=yeared>";
		for ($i=$yearfrom; $i<=$yearto ; $i++) {
			if ($i == $yeared) {
				echo "<option value=$i selected>".$i;
			} else {
				echo "<option value=$i>".$i;
			}
		}
		echo "</option></select>";
	echo "</td></tr>";
	echo "<tr><th align=left>Date Type</th>";
	echo "<td><select name=typedate>";
	$dtype[0] = "Order date";
	$dtype[1] = "Invoice Date";
	if (!$typedate) {
		$typedate = 0;
	}
	for ($i=0; $i<2; $i++) {
		if ($i == $typedate) {
			echo "<option value=$i selected>".$dtype[$i];
		} else {
			echo "<option value=$i>".$dtype[$i];
		}
	}
	echo "</option></select></td></tr>";
	echo "<tr><th  colspan=2>&nbsp;</th></tr>";
}
?>
</html>
