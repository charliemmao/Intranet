<html>

<head>
<LINK REL="StyleSheet" HREF="style.css" TYPE="text/css">
</head>

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
echo "<h2 align=center><a id=top>One Step Order</a>";
echo "<a href=\"".$PHP_SELF."$userstr\"><font size=2>[Refresh]</font></a></h2><hr>";
include("connet_root_once.inc");

if ($supid) {
	include("rla_fin_onesupgoods.inc");
	include("rla_fin_rlacode.inc");
	include("rla_fin_ghrcode.inc");
}

//################################################
//process new order
if ($neworder) {
//#################
	include("rla_fin_onestep_dataprocess.inc");

//rlafinance.ordersteps new and completed	
	$tmp = date("Y-m-d H:i");
	$process = "n;$email_name;$tmp";
	$sql = "INSERT INTO rlafinance.ordersteps SET order_id='$order_id', process='$process';";
	$result = mysql_query($sql);
	//echo "$sql<br><br>";	
	include("err_msg.inc");
	$stepstr = "f";
	include("rla_fin_order_procedure_update.inc");
	
//feedback
	echo "<h2>Order completed.</h2>";
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
		include("rla_fin_onestep_genform.inc");
	}
}

//################################################
//one step order form preparation  and faxno!=''
	$sql = "SELECT supid as id, company, faxno, telno FROM rlafinance.supplierid 
		where telno!='' and faxno!=''
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
	echo "<hr><br><a href=#top>Back to top</a><br><br>";
//*/
?>
</html>
