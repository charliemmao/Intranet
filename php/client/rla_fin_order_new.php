<html>
<body background="rlaemb.JPG" leftmargin="20">
<?php
//js
include('str_decode_parse.inc');
include("rla_functions.inc");
include("regexp.inc");
include("rla_fin_order.inc");
include("userinfo.inc"); //$userinfo
$userstr	=	"?".base64_encode($userinfo);
echo "<h2 align=center><a id=top>New Order</a>";
echo "<a href=\"".$PHP_SELF."$userstr\"><font size=2>[Refresh]</font></a></h2><hr>";
include("connet_root_once.inc");

include("rla_fin_rlacode.inc");

if ($supid) {
	include("rla_fin_onesupgoods.inc");
}

//################################################process new order
if ($neworder) {
	echo "<h2>Hi $first_name</h2>";
	include("rla_fin_display_sup_info.inc");

//enter data to DB
/* table 1: orderid. columns:
	order_id, order_by, estimate_cost, spec_instruction,	
	updatestatus	
	paymethod, pay_remarks	
	invoice_no, invoice_date, invoice_cost, invoice_gst, delivery_cost, delivery_gst
*/
/* table 2: orderdetails. columns: 
	order_id, goods_id, supid, unit, unit_price, rlaprojid,
	code_id
	gst_applicable, gst_percent, 
	to_inventory, inv_processed, 
	checkin
*/
/* table 3: ordersteps. columns: order_id, process, */
//$status: n=new;m=modified;c=cancelled;p=approved;s=sent;r=received;v=verified;b=backorder;f=completed

//echo "supid=$supid<br>";
//echo "noitem=$noitem<br>";

	##find first allowed order no in the ordering system
	$sql = "SELECT details as allowedid
        FROM rlafinance.control 
        WHERE controllist='orderidstart'";
	$result = mysql_query($sql);
	include("err_msg.inc");
   list($allowedid) = mysql_fetch_array($result);
	
	##find current order no in the ordering system
	$sql = "SELECT order_id as currentid
        FROM rlafinance.orderid 
        LIMIT 1";
	$result = mysql_query($sql);
	include("err_msg.inc");
   list($currentid) = mysql_fetch_array($result);
   
   if ($currentid) {
   		$idallowed = "null";
   	} else {
   		$idallowed = $allowedid;
   	}

//****************insert to table orderid
	$orderdate = date("Y-m-d"); 
	$sql = "INSERT INTO rlafinance.orderid SET 
		order_id='$idallowed', order_by='$email_name', 
		orderdate='$orderdate', estimate_cost='$estimate_cost', 
		spec_instruction='$spec_instruction', updatestatus='n';";
	$result = mysql_query($sql);
	include("err_msg.inc");
	//echo "$sql<br><br>";	
	$order_id = mysql_insert_id();
	$rlaorderid = $order_id;
	
	$tmp = date("Y-m-d H:i");
	$process = "n;$email_name;$tmp";
	$sql = "INSERT INTO rlafinance.ordersteps SET order_id='$order_id', process='$process';";
	$result = mysql_query($sql);
	//echo "$sql<br><br>";	
	include("err_msg.inc");

	$sql = "INSERT INTO rlafinance.orderreason 
        SET order_id='$order_id', orderreason='$orderreason';";
	$result = mysql_query($sql);
	//echo "$sql<br><br>";	
	include("err_msg.inc");

	include("rla_fin_orderdetails_entry.inc");
	//$authperson
	echo "<hr>";
}

//################################################
if ($createneworderform) {
	if (!$supid && !$noitem) {
		echo "<h3><font color=#ff0000>Please select a supplier and enter no of item to be ordered.</h3>";
	} elseif (!$supid) {
		echo "<h3><font color=#ff0000>Please select a supplier.</h3>";
	} elseif (!$noitem) {
		echo "<h3><font color=#ff0000>Please enter no of item to be ordered.</h3>";
	} else {
	//form orderform
	echo "<form name=orderform method=\"POST\" action=\"$PHP_SELF\">";
	echo '<input type="hidden" name="frm_str" value= "'.$userstr.'">';
	echo '<input type="hidden" name="supid" value= "'.$supid.'">';
	echo '<input type="hidden" name="noitem" value= "'.$noitem.'">';
	echo "<input type=hidden name=supgdsidpricestr value=$supgdsidpricestr>";

	echo "<p><b>Order Details</b><p>";
	echo "<table border=1>";
	echo "<tr><th>No</th><th>Product ($nogs)</th><th>Qty</th><th>$/Unit</th><th>For Proj</th></tr>";
	$col=5;
	for ($i=0; $i<$noitem; $i++) {
		$k= $i+1;
		echo "<tr><td>$k</td><td><select name=\"goods$i\" onchange=\"auto_fill_price();\">";
		echo "<option value=\"\">---select one---";
		for ($j=0; $j<$nogs; $j++) {
			$id = $glist[$j][0];
			$tmp = $glist[$j][1];
			echo "<option value=\"$id\">$tmp";
		}
		echo "</option></select></td>";
		echo "<td><input type=\"text\" name=\"quantity$i\" size=4></td>";
		echo "<td><input type=\"text\" name=\"unitprice$i\" size=6></td>";
		echo "<td><select name=\"rlacharge$i\">";
		if ($i == 0) {
			echo "<option value=\"\">---select one---";
		} else {
			echo "<option value=\"saa\">Same As Above";
		}
		for ($j=0; $j<$norlacharge; $j++) {
			echo "<option value=\"".$rlacharge[$j][0]."\">".$rlacharge[$j][1];
		}
		echo "</td>";
		echo "</tr>";
	} 
	
	$sql = "SELECT details FROM rlafinance.control WHERE controllist='AuthPerson'";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($details) = mysql_fetch_array($result);
	$tmp = explode(";", $details);
	echo "<tr><th align=left colspan=2>Cost Estimation</th>
		<td colspan=3><input type=\"text\" name=\"estimate_cost\"></td></tr>";
	$tmpstr = "<br>&nbsp;&nbsp;";
	$tmpstr="";
	echo "<tr><td align=left colspan=2><b>Extra Order Info</b><font size=2 color=#0000ff>
		$tmpstr If you have too many items to order,
		$tmpstr enter at least one item to the list above and
		$tmpstr write others on a piece of paper deliver
		$tmpstr it to purchasing officer. Type instruction to box on the right to indicate 
		you have extra items to order.
		</font></td>
		<td colspan=3><textarea rows=\"4\" name=\"spec_instruction\" cols=\"36\"></textarea></tr>";
	$strspe="";
	if ($priv == "00") {
		$strspe = "You receive this order by accident. Please ignore it. I am debugging program on other machine.".
			"\n\nCharlie M MAO\n".date("Y-m-d");
	}
	echo "<tr><td align=left colspan=2><b>Purpose</b><br><font size=2 color=#0000ff>
		&nbsp;&nbsp;Give your reason for the materials/services requested.
		</font></td>
		<td colspan=3><textarea rows=\"5\" name=\"orderreason\" cols=\"36\">$strspe</textarea></tr>";

	echo "<tr><th align=left colspan=$col>Select person to approve this order ";
	echo "<select name=\"authperson\">";
	include("find_admin_ip.inc");
	if ($priv == "00") {
		$k=0;
	} else {
		$k=1;
	}
	
	for ($i=$k; $i<count($tmp); $i++) {
		if ($tmp[$i]) {
			$person = explode("@",$tmp[$i]);
			$v = $person[0];
			echo "<option value=\"$v\">".$person[1];
		}
	}
	echo "</option></th></tr>";
	
	echo "<tr><th align=left colspan=$col>Do you want to send mail to above person?&nbsp;";
	echo "<select name=\"askforapproval\">";
	if ($priv == "00") {
		echo "<option value=\"n\">No";
		echo "<option value=\"y\">Yes";
	} else {
		echo "<option value=\"y\">Yes";
		echo "<option value=\"n\">No";
	}
	echo "</option></select>";
	echo "<br><font size=2 color=#0000ff>Select \"Yes\" to get your order approved.<br>";
	echo "Select \"No\" in case you want to modify it or some goods are not in the goods list.</font>";
	echo "</th></tr>";

	echo "<tr><td colspan=$col align=center>";

	echo "<input type=\"submit\" name=neworder value=\"Submit New Order\"";
	echo "onclick=\"return (chkorder($noitem));\" ";
	echo ">";

	echo "</td></tr>";	
	echo "<tr><td colspan=$col>If the goods you want to buy".
		" is not on the list, please click <font color=#0000ff><b>\"Goods List\"</b></font>".
		" on left frame.<br><br><font color=#ff0000>NB: Unit price includes GST.</font></td></tr>";
	echo "</table>";
	echo "</form><p>";
	echo "<b>Supplier's Info</b><p>";	
	include("one_supplier_info.inc");
	}
	echo "<hr>";
}

//################################################
//form preparation  and faxno!=''
	$sql = "SELECT supid as id, company, faxno, telno FROM rlafinance.supplierid 
		where telno!='' 
		order by company";
	$result = mysql_query($sql);
	include("err_msg.inc");
	$no = mysql_num_rows($result);
	
	echo "<form name=preparation>";
	echo '<input type="hidden" name="frm_str" value= "'.$userstr.'">';
	echo "<table border=0>";
	echo "<tr><th align=left colspan=2>Select a supplier ($no)</th></tr>";
	echo "<tr><td colspan=2><select name=supid>";
	echo "<option value=\"\">--- select one ---";
	while (list($id, $company, $faxno, $telno) = mysql_fetch_array($result)) {
		$sql = "SELECT description
			FROM rlafinance.goodsid
			where supid='$id'";
		//echo $sql."<br>";
		$result1 = mysql_query($sql);
		include("err_msg.inc");
		$no1 = mysql_num_rows($result1);
		if ($no1) {
		$tmp = $company;
			if ($faxno) {
				$tmp=$tmp." (Fax: $faxno)";
			}
			if ($telno) {
				$tmp=$tmp." (Tel: $telno)";
			}
			$tmp=$tmp." ($no1)";
			if ($supid == $id) {
				echo "<option value=\"$id\" selected>$tmp";
			} else {
				echo "<option value=\"$id\">$tmp";
			}
		}
	}
	echo "</td></tr>";
	
	echo "<tr><th align=left colspan=2>No of item    ";
	if (!$noitem) {
		$noitem = 2;	
	}
	echo "<input type=\"text\" name=\"noitem\" value=\"$noitem\" size=5></th></tr>";
	
	echo "<tr><td colspan=2 align=center>";
	echo "<input type=\"submit\" name=createneworderform value=\"Create New Order Form\">";
	echo "</td></tr>";
	echo "<tr><td colspan=2>&nbsp;</td></tr>";

	echo "<tr><td colspan=2>If the supplier you want to place the order to".
		" is not on the list,<br>please click <font color=#0000ff><b>\"Supplier List\"</b></font>".
		" on left frame.</td></tr>";
	
	echo "</table>";
	echo "</form>";

	$sql = "SELECT order_id, orderdate 
        FROM rlafinance.orderid 
        WHERE order_by='$email_name' and updatestatus='q'
        ORDER BY orderdate DESC";
    $result = mysql_query($sql);
    include("err_msg.inc");
    $no = mysql_num_rows($result);
	if ($no) {
		echo "<hr>";
		echo "<h2>$first_name</h2>";
		echo "<b>System found the following order be queried you have not take any action yet. Please modify ";
		echo "from existing order page if you want your order to be processed further.</b><br><br>";
		while (list($order_id, $orderdate) = mysql_fetch_array($result)) {
			echo "<b>$order_id, $orderdate</b><br>";
		}
		echo "<br><br><b>System Administrator</b><br>";
	}
	echo "<hr><br><a href=#top>Back to top</a><br><br>";
?>
<script language=javascript>

function search_split_match() {
}

function auto_fill_price() {
	//return;
	//replace search split match
	goodsstr = orderform.supgdsidpricestr.value;
	//alert(goodsstr );
	var RegExp1 = /\@/;
	var	 regexp0 = /\=/g;
	var goodsidprice = goodsstr.split(RegExp1);
	//alert("No " + goodsidprice.length);
	var gdsidtmp = new Array();
	var gdspricetmp = new Array();
	for (i=0; i < goodsidprice.length; i++) {
		tmp = goodsidprice[i];
		//alert(tmp);
		tmp0 = tmp.split(regexp0);
		gdsidtmp[i] = tmp0[0];
		gdspricetmp[i] = "";
		if (tmp0.length>1) {
			gdspricetmp[i] = tmp0[1];
		}
		//alert(tmp + "\n" + gdsidtmp[i] + "\n" + gdspricetmp[i] + "\n" );
	}
	
	var e = window.event.srcElement;
	//unitprice goods
	var myregexp = /goods/g;
	var price = "";
	for (i=0; i < goodsidprice.length; i++) {
		if (gdsidtmp[i] == e.value) {
			price = gdspricetmp[i];
			break;
		}
	}
	//alert(e.name + "\n" + e.value + "\n" + tmp + "\n" + goodsstr);

	var tmp5 = e.name;
	tmp5 =tmp5.replace(myregexp, "unitprice");
	var target = document.all(tmp5);
	if (target != null) {
		target.value = price;
		//alert(price);
	}
}
</script>
