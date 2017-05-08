<html>
<?php

//goods_id, name, description, supplier, product_code

echo "$hr<form name=goodslistman>";
echo '<input type="hidden" name="frm_str" value= "'.$userstr.'">';
if ($goodsmodify) {
	echo '<input type="hidden" name="goods_id" value= "'.$goods_id.'">';
	$sql = "SELECT COUNT(*) as ctr
        FROM rlafinance.priceref 
        WHERE goods_id='$goods_id'";	
    	$result = mysql_query($sql);
    	include("err_msg.inc");
    	list($ctr) = mysql_fetch_array($result);

	echo "<h2>Modify following goods <a href=rla_fin_goods_price_history.php?goods_id=$goods_id target=\"_new\">[View Price History ($ctr)]</a></h2>";
} else {
	echo "<h2>Add new goods</h2>";
}

echo "<table>";
// and faxno!=''
$sql = "SELECT supid as id, company, telno, faxno FROM rlafinance.supplierid 
	where telno!=''
	order by company";
$result = mysql_query($sql);
include("err_msg.inc");
$no = mysql_num_rows($result);
echo "<tr class=tr2><td><b>Supplier&nbsp;&nbsp;&nbsp;&nbsp;</b></td><td><select name=supid>";	// ($no)
echo "<option value=\"-1\" >---select one---";

while (list($id, $company, $telno, $faxno) = mysql_fetch_array($result)) {
	$tmp = $company;
	if ($faxno) {
			$tmp=$tmp." (Fax: $faxno)";
	}
	if ($telno) {
			//$tmp=$tmp." (Tel: $telno)";
	}
	if ($contactperson) {
			//$tmp=$tmp." ($contactperson)";
	}
	if ($supid == $id) {
		echo "<option value=\"$id\" selected>$tmp";
	} else {
		echo "<option value=\"$id\">$tmp";
	}
}
echo "</option></select></td></tr>";
$sql = "SELECT details as maxitem
        FROM rlafinance.control 
        WHERE controllist='nogoodsatonce'";
$result = mysql_query($sql);
include("err_msg.inc");
list($maxitem) = mysql_fetch_array($result);
//$maxitem=10;
if ($goodsmodify) {
	$maxitem = 1;
}
	include("find_admin_ip.inc");

if ($email_name != "$adminname") {
	//$maxitem = 1;
}
$sign="<font color=#ff0000>No $ sign</font>";
for ($inew=0; $inew<$maxitem; $inew++) {
	($inew%2)?$tr="tr1":$tr="tr2";
	echo "<tr class=tr1><td colspan=2>";
	echo "<table border=0 class=$tr>";
	$iitem = $inew + 1;
	echo "<tr><th colspan=2 align=left><font color=#0000ff>Item $iitem</font></th></tr>";
	$j="";
	if ($inew) {
		$j = $inew;
	}
	echo "<tr><th align=left>Product Name</th><td>
		<input type=text name=name$j value=\"$name\" size=50></td></tr>";
	echo "<tr><th align=left>Description</th><td>
		<input type=text name=description$j value=\"$description\" size=50></td></tr>";
	if (!$goodsmodify) {
		$price = "";
	}
	if (!$price) {
		$price = "0.00";
	}
	echo "<tr><th align=left>Price-$sign</th><td>
		<input type=text name=price$j value=\"$price\" size=10>";
	//echo "</td></tr><tr><th align=left>";
	echo "&nbsp;&nbsp;&nbsp;&nbsp;<b>Supplier's Code</b>&nbsp;&nbsp;&nbsp;&nbsp;
		<input type=text name=product_code$j value=\"$product_code\" size=10></td></tr>";
	echo "</td></tr></table>";
}
	
if ($goodsmodify) {
	echo "<tr><th align=left><font color=#ff0000>Save Option</font></th><td>
	<select name=modoption>";
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
	echo "</option></select></td></tr>";
}

echo "<tr><td colspan=2 align=middle>";
echo "<br><button type=submit name=goodsmanipulation ";
echo "onclick=\"return chkgoods();\">";
if ($goodsmodify) {
	echo "<b>Modify</b>";
} else {
	echo "<b>Add New</b>";
}
echo "</button><br><b><font color=#ff0000>$first_name, please check your entry carefully, no modification is allowed.</font></b><br><br></td></tr>";

echo "<tr><td class=tr2 colspan=2>If supplier is not on the list please enter ".
	"supplier details<br>from \"<font color=#0000ff>Supplier List</font>\" on left frame.</td></tr>";
echo "</table>";
echo "</form>";
?>
</html>