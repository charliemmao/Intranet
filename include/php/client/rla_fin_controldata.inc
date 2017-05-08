<html>
<?php
	$thisip = getenv('REMOTE_ADDR');
	$ipprex = substr($thisip,0, strlen($thisip)-4);
	$thisip = substr($thisip,strlen($thisip)-3,3);
	//echo "$thisip<br>";
	
	include('connet_root_once.inc');
	$sql = "SELECT details FROM rlafinance.control WHERE controllist='AuthPerson'";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($details) = mysql_fetch_array($result);
	//echo "$sql<br>$details<br>";
	parsestr($details);
	$noauthperson= $strcount;
	$AuthPerson = $str;
	for ($i=0; $i<$noauthperson; $i++) {
		for ($j=0; $j<count($AuthPerson[$i]); $j++) {
			if ($thisip == $AuthPerson[$i][$j]) {
				$mystat = "auth";
				//echo "mystat = $mystat $thisip<br>";
			}
		}
	}
	include("find_admin_ip.inc");
	if ($privspec == 10 && $mystat != "auth") {
		$mystat = "auth";
		include("find_admin_ip.inc");
		//mail ("$adminname@rla.com.au","RE: $email_name=$mystat ($ser)", "", "From: $email_name@$domain");
	}

	$sql = "SELECT details as domain FROM rlafinance.control WHERE controllist='domain'";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($domain ) = mysql_fetch_array($result);
	//echo "$sql<br>$domain=$ipprex<br>";

	$sql = "SELECT details as company FROM rlafinance.control WHERE controllist='company'";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($company) = mysql_fetch_array($result);

	$sql = "SELECT details as sendmail FROM rlafinance.control WHERE controllist='sendmail'";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($sendmail) = mysql_fetch_array($result);

	$sql = "SELECT details as domain FROM rlafinance.control WHERE controllist='testdb'";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($testdb) = mysql_fetch_array($result);

	$sql = "SELECT details FROM rlafinance.control WHERE controllist='ExecSec'";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($details) = mysql_fetch_array($result);
	//echo "$sql<br>$details<br>";
	parsestr($details);
	$noExecSec = $strcount;
	$ExecSec= $str;
	for ($i=0; $i<$noExecSec; $i++) {
		for ($j=0; $j<count($ExecSec[$i]); $j++) {
			if ($thisip == $ExecSec[$i][$j]) {
				$mystat = "exec";
			}
		}
	}

	$sql = "SELECT details FROM rlafinance.control WHERE controllist='OrderOfficer'";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($details) = mysql_fetch_array($result);
	//echo "$sql<br>$details<br>";
	parsestr($details);
	$noOrderOfficer = $strcount;
	$OrderOfficer= $str;
	for ($i=0; $i<$noOrderOfficer; $i++) {
		for ($j=0; $j<count($OrderOfficer[$i]); $j++) {
			if ($thisip == $OrderOfficer[$i][$j]) {
				$mystat = "poff";
			}
		}
	}
	
	function parsestr($details) {
		global $tmp;
		global $str;
		global $strcount;
		$tmp = explode(";", $details);
		$strcount=count($tmp);
		for ($i=0; $i<$strcount; $i++) {
			$str[$i] = explode("@", $tmp[$i]);
		}
	}
?>
</html>
