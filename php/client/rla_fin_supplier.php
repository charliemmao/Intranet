<html>
<body background="rlaemb.JPG" leftmargin="20">

<?php
//js
include('inventory_verify.inc');
include('str_decode_parse.inc');
include("rla_functions.inc");
include("chksupplier.inc");
include("regexp.inc");
include("userinfo.inc"); //$userinfo
$userstr	=	"?".base64_encode($userinfo);
echo "<h2 align=center><a id=top>Supplier List and Modification</a>";
echo "<a href=\"".$PHP_SELF."$userstr\"><font size=2>[Refresh]</font></a></h2><hr>";
include("connet_root_once.inc");
	
if ($supid) {
	$supid_from=$supid;
	//echo $supid."=supid<br>";
} 
#################suppliers list Manipulation
if ($suppliersmanipulation) {
/* table: supplierid. columns: supid, company, address, contactperson, telno, 
	faxno, mobno, email, www, */
	if ($supid) {
		// update suppliers
		$sql = "UPDATE rlafinance.supplierid SET company='$company', 
			address='$address', contactperson='$contactperson', telno='$telno', 
			faxno='$faxno', mobno='$mobno', email='$email', www='$www' WHERE supid='$supid'";
	} else {
		//add new suppliers
		$sql = "SELECT supid FROM rlafinance.supplierid WHERE company like '$company'";
		$result = mysql_query($sql);
		include("err_msg.inc");
		list($supid) = mysql_fetch_array($result);
		if ($supid) {
			$sql = "";
		} else {
			$sql = "INSERT INTO rlafinance.supplierid VALUES('null', '$company', '$address', 
				'$contactperson', '$telno', '$faxno', '$mobno', '$email', '$www');";
		}
	}

	//echo $sql.".<br>";
	if ($sql) {
		$result = mysql_query($sql);
		include("err_msg.inc");
		if ($supid) {
			echo "<h2>Suppliers \"$company\" has been updated.</h2>";
		} else {
			echo "<h2>Suppliers \"$company\" has been added.</h2>";
		}
	} else {
		echo "<h2>Suppliers \"$company\" to be added has been in the DB.<p>
			<a onclick=\"backhistory(-1);\"><font color=#0000fff>Back to Previous Page.</font></a></h2>";
	}
	echo "<hr>";
}

#################	 list current suppliers record
/* table: supplierid. columns: supid, company, address, contactperson, telno, faxno, mobno, email, www, */
$sql = "SELECT supid as id, company, faxno, telno, contactperson FROM rlafinance.supplierid order by company";
$result = mysql_query($sql);
include("err_msg.inc");
$no = mysql_num_rows($result);

if (!$no) {
	echo "<h2>No record for suppliers.</h2>";
} else {
	echo "<h2>Current Supplier List</h2>";
	echo "<form name=supplierslist>";
	echo '<input type="hidden" name="frm_str" value= "'.$userstr.'">';
	echo "<table border=0>";
	echo "<tr><th align=left>No of Suppliers: $no</th></tr>";
	echo "<tr><td><select name=supid>";
	
	while (list($id, $company, $faxno, $telno, $contactperson) = mysql_fetch_array($result)) {
		$tmp = $company;
		if ($faxno) {
			$tmp=$tmp." (Fax: $faxno)";
		}
		if ($telno) {
			$tmp=$tmp." (Tel: $telno)";
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
	echo "</td></tr>";
	echo "<tr><td align=center>";
	echo "<input type=\"submit\" name=suppliersmodify value=\"Modify\">";
	echo "&nbsp;&nbsp;";
	//echo "<input type=\"submit\" name=suppliersdelete value=\"Delete\">";
	echo "</td></tr></table>";
	echo "</form>";
}

################Modify suppliers list: collect data
if ($suppliersmodify) {
	$sql = "SELECT company, address, contactperson, telno, faxno, mobno, 
	email, www FROM rlafinance.supplierid WHERE supid='$supid'";
	//echo $sql."<br>";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($company, $address, $contactperson, $telno, $faxno, $mobno, $email, $www) = 
		mysql_fetch_array($result);
	//echo "$company, $address, $contactperson, $telno, $faxno, $mobno, $email, $www<br>";
}

#################	Form to Create or Modify New suppliers
include("rla_fin_new_mod_supplier.inc");
###################

echo "<hr><br><a href=#top>Back to top</a><br><br>";
?>
</html>
