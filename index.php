<html>
<!--
SELECT [STRAIGHT_JOIN] [SQL_SMALL_RESULT] [SQL_BIG_RESULT] [HIGH_PRIORITY]
       [DISTINCT | DISTINCTROW | ALL]
    select_expression,...
    [INTO {OUTFILE | DUMPFILE} 'file_name' export_options]
    [FROM table_references
        [WHERE where_definition]
        [GROUP BY col_name,...]
        [HAVING where_definition]
        [ORDER BY {unsigned_integer | col_name | formula} [ASC | DESC] ,...]
        [LIMIT [offset,] rows]
        [PROCEDURE procedure_name] ]	chargecode
-->
<head>
<title>RLA Intranet First Page</title>
</head>
<script language=javascript>
		//window.location="http://charlie.rla.com.au/index.php";
</script>

<body background="images/rlaemb.JPG">

<?php
include("ts_showlastmonthtsht.inc");

##################################################
## main contents
##################################################
	include("admin_find_sys.inc"); // get domain name, intranet IP prefix, is this a pc for admin

	$curip = substr($thisip, strlen($thisip)-3, strlen($thisip));
	
	if ($curip == 174) {
		//include('data_col_or_logon.inc');
		//echo "<b>Hi Intranet user</b><br><br>Intranet Server is Currently Offline.<br><br><br>Charlie";
		//exit;
	} else {
		//echo "charlie";
		//exit;
	}
		//echo "1 email_name = $email_name; curip = $curip<br>";
	if ($curip<=190 or $curip >=201) {
		$prob = 0;
		//echo $prob."<br>";
		//exit;
		if ($prob == 1) {
			echo "<h1>Dear RLA Intranet User</h1>";
			echo "Currently RLA Intranet Server has some problems need to be fixed, 
			therefore it is not accessable. Please try it again at later time.<br><br><br>
			Regards<br><br><br>
			<b>from System Administrator</b><br>";
			echo date("m-d-Y");
		} else {
			//echo "2 email_name = $email_name; curip = $curip<br>";
			include('data_col_or_logon.inc');
		}
	} elseif (190 < $curip && $curip <201) {
		//dynamically assigned ip is greater than 190 and less than 201
		$dialin = 1;
		include("user_new_logon.inc");
	}
	exit;
	

##################################################
#	test program
##################################################
	/*
	$priviledge	=	'00';
	include('allow_to_show.inc');
	include('adminctl.inc');	
	//*/
	
	/*
	include('connet_root.inc');
	$qry = "select DAYNAME('20000504');";
	include('find_one_val.inc');
	echo $out.'<br>';
	//*/
	//date("Y-m-d H:i:s")
	
	//bool mail(string to, string subject, string message, string [additional_headers]);
	include("find_admin_ip.inc");
	$to 		= "$adminname@rla.com.au";
	$subject	=	"PHP email test";
	$message	=	"This is only a test.";
	$header	=	"From: admin@$SERVER_NAME\nReply-To: admin@$SERVER_NAME\n";
	mail($to, $subject, $message, $header);
     	
	exit;
	$de_encode="decode";
	$str	=	'"charlie"';
	include("de_encode.inc");
	
	$de_encode="encode";
	$str	=	'"'.$out.'"'; 
	include("de_encode.inc"); 


$tomorrow  = mktime(0,0,0,date("m"),date("d")+1,date("Y"));
$lastmonth = mktime(0,0,0,date("m")-1,date("d"),date("Y"));
$nextyear  = mktime(0,0,0,date("m"),date("d"),date("Y")+1);
echo $tomorrow."<br>";
echo $lastmonth."<br>";
echo $nextyear."<br>";
echo date( "M-d-Y", $tomorrow)."<br>";
echo date( "M-d-Y", $lastmonth)."<br>";
echo date( "M-d-Y", $nextyear)."<br>";
/*
echo date( "M-d-Y", mktime(0,0,0,12,32,1997))."<br>";
echo date( "M-d-Y", mktime(0,0,0,13,1,1997))."<br>";
echo date( "M-d-Y", mktime(0,0,0,1,1,1998))."<br>";
<a href="#" onclick="javascript:self.close()">Close Window</a>
//*/

?>
</body>
</html>