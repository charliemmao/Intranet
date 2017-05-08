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
echo "<h2 align=center><a id=top>Delete Goods Enrty</a>";
echo "<a href=\"".$PHP_SELF."$userstr\"><font size=2>[Refresh]</font></a></h2>$hr";
include("connet_root_once.inc");

if ($deletethisgoods && $goods_id && $supid && $company) {
	$sql = "SELECT name, description, product_code 
		FROM rlafinance.goodsid 
		WHERE supid='$supid' and goods_id='$goods_id'";
	//echo "$sql<br>";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($name, $description, $product_code) = mysql_fetch_array($result);
	$sql = "DELETE FROM rlafinance.goodsid 
        WHERE goods_id='$goods_id' and name='$name' and 
            description='$description' and supid='$supid'";
    //echo "$sql<br>";
	$result = mysql_query($sql);
	include("err_msg.inc");
	$sql = "DELETE FROM rlafinance.priceref 
        WHERE goods_id='$goods_id'";
	$result = mysql_query($sql);
	include("err_msg.inc");
        
	echo "<hr><h2>The following goods has been deleted from DB</h2>";
	echo "<table border=0>";
	echo "<tr><th align=left>Company</th><td>$company</td></tr>";
	echo "<tr><th align=left>Product</th><td>$name</td></tr>";
	echo "<tr><th align=left>Description</th><td>$description</td></tr>";
	if (!$product_code) {
		$product_code = "---";
	}
	echo "<tr><th align=left>Product Code</th><td>$product_code</td></tr>";
	echo "</table><br><hr>";
}

#################goods list Manipulation
if ($listgoods) {
	$sql = "SELECT company
		FROM rlafinance.supplierid
		WHERE supid='$supid'";
	//echo "$sql<br>";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($company) = mysql_fetch_array($result);
	$sql = "SELECT goods_id as id, name, description, product_code 
		FROM rlafinance.goodsid 
		WHERE supid='$supid'
		ORDER BY name";
	$result = mysql_query($sql);
	include("err_msg.inc");
	$no = mysql_num_rows($result);
	if (1<$no) {
		echo "<hr><h2>Currently following goods are listed under <br>$company</h2>";
	} else {
		echo "<hr><h2>Only one goods is listed under <br>$company</h2>";
	}
	echo "<table border=1>";
	echo "<tr><th>Goods ID</th><th>Name</th><th>Description</th><th>Product Code</th><th>Action</th></tr>";
	while (list($id, $name, $description, $product_code) = mysql_fetch_array($result)) {
		if (!$product_code) {
			$product_code = "---";
		}
		echo "<tr><th align=left>$id</th><td>$name</td><td>$description</td><td>$product_code</td>
			<td><a href=\"$PHP_SELF?deletethisgoods=y&goods_id=$id&supid=$supid&company=$company\">[Delete]</a></td>
			</tr>";
	}
	echo "</table><br><hr>";
}

#################	 list companies which has goods under its name  
	$sql = "SELECT DISTINCT t2.company as comp, t2.supid as spip
		FROM rlafinance.goodsid as t1, rlafinance.supplierid as t2 
		WHERE t1.supid=t2.supid
		GROUP BY t2.company
		ORDER BY t2.company";
	$result = mysql_query($sql);
	include("err_msg.inc");
	$no = mysql_num_rows($result);
	
		echo "<h2>Company List</h2>";
		echo "<form name=deletegoods>";
		echo '<input type="hidden" name="frm_str" value= "'.$userstr.'">';
		echo "<table border=0>";
		echo "<tr><th align=left>Number of Companies: $no</th></tr>";
		echo "<tr><td><select name=supid>";
		while (list($comp, $spip) = mysql_fetch_array($result)) {
			if ($spip == $supid) {
				echo "<option selected value=\"$spip\">$comp-$spip";
			} else {
				echo "<option value=\"$spip\">$comp-$spip";
			}
		}
		echo "</option></select>";
		echo "</td></tr>";
		echo "<tr><th><button type=\"submit\" name=listgoods ><b>List Goods</b></button></td></tr></table>";
		echo "</form>";

echo "<hr><br><a href=#top>Back to top</a><br><br>";
?>
</html>
