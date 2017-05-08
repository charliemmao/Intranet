<html>

<head>
<title>IPTraf Log File Analyser</title>
</head>
<body leftmargin="20" bgcolor="#FFFFCC" text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="rlaemb.JPG">

<?php
$tstart=date("H:i:s");
include("admin_access.inc");
include('rla_functions.inc');
include("connet_root_once.inc");

/*
	$sql = "SELECT email_name as en, computer_ip_addr as cip
        FROM timesheet.employee
        WHERE tsentry='y';";
    $result = mysql_query($sql);
    include("err_msg.inc");
    $fpoutput = fopen("/usr/local/apache/htdocs/iptraflog/iplist", "w");
	$i=0;
    while (list($en, $cip) = mysql_fetch_array($result)) {
    	if (5 < strlen($cip)) {
    		$ip = substr($cip,12,3);
    		if ($ip>0) {
    			$i++;
    			//echo "$i:   $en, $cip, $ip<br>";
    			fputs($fpoutput, "$ip,$en\n");
    		}
    	}
    }
    fclose($fpoutput);
//*/
    
echo "<id=top><h2 align=center>IPTraf Log File Analyser<a href=\"$PHP_SELF$admininfo\">
<font size=2>[Refresh]</font></a></h2><hr>";

#################################################################
## load text file form
#################################################################

echo "<form method=post action=\"$PHP_SELF\">";
	include("userstr.inc");
	//$mainidir	$filepath	$rptreldir		$pcipfile	$LANipadd
	$mainidir = "/usr/local/apache/htdocs/";
	$filepath = "iptraflog/";
	$rptreldir = $filepath."report/";
	echo "<table border=1>";
	echo "<tr><th colspan=2><font color=#0000ff>Main Parameters</font></th></tr>";	
	echo "<tr><th align=left>File to be processed</th>";
		echo "<td><select name=inputname>";
		$dir = $mainidir.$filepath;
		filelist($dir,$inputname);
	echo "</option></select></td></tr>";
	
	if (!$LANipadd) {
		include("find_admin_ip.inc");
		$LANipadd = $netip; 
	}
	echo "<tr><th align=left>Intranet IP</th><td>
		<input type=text name=LANipadd value=\"$LANipadd\" size=30></td></tr>";
	if (!$pcipfile) {
		$pcipfile = "iplist";
	}
	echo "<tr><th align=left>Local PC IPlist File</th><td>
		<input type=text name=pcipfile value=\"$pcipfile\" size=30></td></tr>";
	
	echo "<tr><th colspan=2><font color=#0000ff>File Path: Ending with \"/\"</font></th></tr>";	
	echo "<tr><th align=left>Absolute Server Path</th><td>
		<input type=text name=mainidir value=\"$mainidir\" size=30></td></tr>";
	echo "<tr><th align=left>Reletive File Path</th><td>
		<input type=text name=filepath value=\"$filepath\" size=30></td></tr>";
	echo "<tr><th align=left>Reletive Report File Path</th><td>
		<input type=text name=rptreldir value=\"$rptreldir\" size=30></td></tr>";

/*
	echo "<tr><th align=left>Output</th>";
		$i=0;
		$FileExtList[$i] = ".log"; $i++;
		$FileExtList[$i] = ".txt"; $i++;
		echo "<td>---</td>";
		if (!$outputext) {
			$outputext = $FileExtList[1];
		}
		echo "<td><select name=outputext>";
		for ($j=0; $j<$i; $j++) {
			if ($outputext == $FileExtList[$j]) {
				echo "<option selected>$FileExtList[$j]";
			} else {
				echo "<option>$FileExtList[$j]";
			}
		}
		echo "</td></tr>";
	
	echo "<tr><th align=left>Data Conversion</th>";
		$i = 0;
		$dat[$i] = "N";	$i++;
		$dat[$i] = "Y"; $i++;
		if (!$dataconv) {
			$outputext = $dat[0];
		}

		echo "<td colspan=2><select name=dataconv>";
		for ($j=0; $j<2; $j++) {
			if ($dataconv == $dat[$j]) {
				echo "<option selected>$dat[$j]";
			} else {
				echo "<option>$dat[$j]";
			}
		}
		echo "</td></tr>";
	if (!$entrystart) {
		$entrystart = 1;
	}
	echo "<tr><th align=left>Entry Start</th><td>
		<input type=text name=entrystart value=\"$entrystart\" size=5></td></tr>";
	if (!$increno) {
		$increno = 100;
	}
	echo "<tr><th align=left>No Process</th><td>
		<input type=text name=increno value=\"$increno\" size=5></td></tr>";
		
	echo "<tr><th align=left>Check No Entry</th><td>
		<select name=duplicationcheck>";
		$i = 0;
		$t[$i] = "N";	$i++;
		$t[$i] = "Y"; $i++;
		if (!$duplicationcheck) {
			$duplicationcheck = $t[1];
		}
		for ($i=0; $i<2; $i++) {
			if ($duplicationcheck == $t[$i]) {
				echo "<option selected>$t[$i]";
			} else {
				echo "<option>$t[$i]";
			}
		}		
	echo "</option></select></td></tr>";
//*/

	if (!$fld) {
		$fld = 10;
		/*
		2:
			Tue Nov 28 21:56:47 2000; ******** IP traffic monitor started 
			Tue Nov 28 22:34:46 2000; ******** IP traffic monitor stopped
		3: 	
			Tue Nov 28 22:24:26 2000; TCP; Connection 193.117.250.165:80 &lt;-&gt; 
			$netip.177:1082 idle for 15 minutes, marking for timeout, 25 packets, 23306 bytes
		4: no exist
		5:
			Tue Nov 28 21:56:54 2000; UDP; eth0; 251 bytes; from $netip.177:138 to $netip.255:138 
			Tue Nov 28 21:57:00 2000; ARP; eth0; 60 bytes; from 0000c0f340eb to ffffffffffff
		6:
			Tue Nov 28 21:57:29 2000; TCP; eth0; 40 bytes; from $netip.1:10000 to $netip.174:2963; FIN acknowleged
			Tue Nov 28 21:57:39 2000; ICMP; eth0; 124 bytes; from $netip.1 to $netip.140; dest unreach (port)
		7:	
			Wed Nov 29 01:59:04 2000; TCP; eth0; 40 bytes; from $netip.1:10000 to $netip.174:3429; 
				FIN sent; 10 packets, 7821 bytes 
			Wed Nov 29 01:59:04 2000; TCP; eth0; 40 bytes; from $netip.174:3429 to $netip.1:10000; 
				FIN sent; 8 packets, 755 bytes
		8:
			Tue Nov 28 21:57:42 2000; TCP; ppp0; 40 bytes; from 203.28.4.123:61275 to 
				193.117.250.165:80; Connection reset; 12 packets, 4955 bytes; opposite direction 
				0 packets, 0 bytes
			Tue Nov 28 21:59:03 2000; TCP; eth0; 40 bytes; from 
				$netip.174:2966 to $netip.1:10000; Connection reset; 5 packets, 561 
				bytes; opposite direction 5 packets, 2301 bytes
		//*/
	}
/*
	echo "<tr><th align=left>Display record with</th><td>
		<input type=text name=fld value=\"$fld\" size=5>Fields.</td></tr>";

	echo "<tr><th align=left>Local PC IP Address</th><td>
		<select name=pcipaddre>";
		//$mainidir	$filepath	$rptreldir
		$fileinput = $mainidir.$filepath.$pcipfile;
		$rcdctr = 0;
		$fpinput = fopen($fileinput, "r");
	
		while ($buffer = fgets($fpinput, 5000)) {
			$tmp = explode(",",$buffer);
			$tmp[1] = strtoupper($tmp[1]);
			if ($pcipaddre == $tmp[0]) {
				echo "<option value=$tmp[0] selected>$tmp[1]";
			} else {
				echo "<option value=$tmp[0]>$tmp[1]";
			}
		}		
		fclose($fpinput);
	echo "</option></select></td></tr>";
//*/

	echo "<tr><th colspan=2><font color=#0000ff>Parameters for Summary Report</font></th></tr>";	
	if (!$lapsec) {
		$lapsec  = 300;
	}
	echo "<tr><th align=left>Consecutive packets</th><td>
		<input type=text name=lapsec value=\"$lapsec\" size=5> seconds apart treated as a new connection.</td></tr>";
	if (!$hitsctr) {
		$hitsctr = 4;
	}
	echo "<tr><th align=left>Minimum Hits</th><td>
		<input type=text name=hitsctr value=\"$hitsctr\" size=5> to be listed in summary.</td></tr>";

	echo "<tr><td colspan=2 rowspan=2 valign=middle align=middle><button type=submit name=dumpfiletodb>
		<font size=4><b>Action</b></font></button>";
	echo "</table>";
echo "</form>";

#################################################################
## Log File Analyser
#################################################################
if ($dumpfiletodb) {
	if ($inputname == "") {
		echo "<b>No file is selected<b>";
		exit;
	}
//	read user ip and email name
	//$mainidir	$filepath	$rptreldir		$pcipfile
	$fileinput = $mainidir.$filepath.$pcipfile;
	//echo "Local PC IP list file at: $fileinput<br>";
	
	$rcdctr = 0;
	$fpinput = fopen($fileinput, "r");
	while ($buffer = fgets($fpinput, 5000)) {
		$tmp = explode(",",$buffer);
		$ip[$rcdctr] = $tmp[0];
		$ename[$rcdctr] = trim($tmp[1]);
		$nmfrmip["$tmp[0]"] = $ename[$rcdctr];
		//echo "$ip[$rcdctr]   $ename[$rcdctr]<br>";
		$pcctr[$tmp[0]] = 0;
  		flush();
  		$rcdctr++;
	}
	fclose($fpinput);
	echo "<hr>";
  	flush();
	//echo "$rcdctr<br>";	
	$ipno = $rcdctr;

//	iptraf file analysis
	//$mainidir	$filepath	$rptreldir		$pcipfile
	$fileinput = $mainidir.$filepath.$inputname;
	//echo "File to be analysed at: $fileinput<br>";
	echo "<h1>Analysing log file of $inputname</h1><br>";
	$rcdctr = 0;
	$fpinput = fopen($fileinput, "r");
	
	$SHOWALL = "N";
	if ($SHOWALL == "Y") {
		echo "<table border=1>";
		/* list raw data
			echo "<tr><th>No Field</th><th>Time (hh:mm)</th><th>Protocal</th><th>Interface</th>
			<th>Bytes</th><th>From-To</th><th>Packet Info</th><th>pkt no+bytes</th><th>Others</th></tr>";
			echo "<tr><th>Time (hh:mm)</th><th>Protocal</th><th>From</th><th>bytes</th></tr>";
		//*/
		echo "<tr><th>Time (hh:mm)</th><th>From</th><th>To</th><th>bytes</th></tr>";
	}
	$ctrno = 0;
	while ($buffer = fgets($fpinput, 5000)) {
		$str = explode(";", $buffer);
		$nofld = count($str);
		$fldno[$nofld] = $fldno[$nofld] + 1;
		if ($nofld >5) {
			$buffer1 = ereg_replace("$LANipadd", "<b>$LANipadd</b>", $str[4]);
			if ($buffer1 != $str[4] && $str[6] != "") {//packet from or to $netip
				//manupulate time string
				$dstrtmp = $str[0];
				$str[0] = dateconvert($str[0]);
				$datestr = dateconvert_date($dstrtmp);
				$spl = explode("@",$str[0]);
				$str[0] = $spl[0];
				//manupulate "from to string"
				$tmp = explode(" to ",$str[4]);
				$from0 = ereg_replace("from ","",$tmp[0]);
				$from1 = explode(":",$from0);
				$from = $from1[0];
				$fromout = substr($from,0,11);
				if ($fromout != "$LANipadd") {
					if (trim($from) != "$LANipadd.1") {
						$to = substr($tmp[1],12,3);
						if ($to>=121 && $to<=177) {
							$bytes = explode(",",$str[6]);
							$bytesctr = ereg_replace(" bytes","",$bytes[1]);
							//put data to individual data array
							if ($packetrcd[$to][$pcctr[$to]-1][0] == $str[0] && 
								$packetrcd[$to][$pcctr[$to]-1][2] == $bytesctr) {
							} else {
								$packetrcd[$to][$pcctr[$to]][0] = $str[0]; 		//time
								//echo $str[0]."<br>";
								$packetrcd[$to][$pcctr[$to]][1] = $from; 			//ip address
								$packetrcd[$to][$pcctr[$to]][2] = $bytesctr; 	//bytes
								$packetrcd[$to][$pcctr[$to]][3] = $spl[1];		//time in second
								$packetrcd[$to][$pcctr[$to]][4] = $datestr;		//date
								$alliplist[$from] = $alliplist[$from]+1;
								$pcctr[$to] = $pcctr[$to] + 1;
								/*
								if ($to == 121) {
									echo "<tr><td>$str[0]</td><td>$from</td><td>$to</td><td>$bytesctr</td></tr>";
									flush();
									$ctrno++;
									if ($ctrno>3) {
										exit;
									}
								}
								//*/
							}
						}
					}
				}
			}
		}
  		$rcdctr++;
	}
	fclose($fpinput);
	if ($SHOWALL == "Y") {
		echo "</"."table>";
		exit;
	}
	$notes = "<br><b>Note:</b><br>If two consecutive packets are $lapsec seconds apart, no time
		is calculated in time total.<br>Total download volume is an estimation only.";

###############################################		
// 	write individual internet access
###############################################	
	$inputname = ereg_replace(".log", "", $inputname);
	//$mainidir	$filepath	$rptreldir		$pcipfile
	$iptrafpath = $mainidir.$rptreldir;
	for ($i=0; $i<$ipno; $i++) {
		$to = $ip[$i];
		if ($pcctr[$to]>0) {
			$output = "<hr><h3>To $ename[$i] with IP $LANipadd.$to</h3>";
			$output = $output."<t"."able border=1>";
			$output = $output."<tr><th>Time (h:m:s)</th><th>IP Address</th><th>bytes</th></tr>";
			//echo "$ip[$i]   $ename[$i]<br>";
			$totalbytes = 0;
			$totalsec = 0;
			for ($j=0; $j<$pcctr[$to]; $j++) {
				$tmp = trim($packetrcd[$to][$j][1]);
				$tmp = "<a href=\"http://$tmp\" target=\"_blank\">$tmp</a>";
				if ($j == 0) {
					$output = $output."<tr><td>".$packetrcd[$to][$j][0]." ".$packetrcd[$to][$j][4]."</td>";
					$output = $output."<td>".$tmp."</td>";
					$output = $output."<td>".$packetrcd[$to][$j][2]."</td></tr>";
				} else {
					$output = $output."<tr><td>".$packetrcd[$to][$j][0]." ".$packetrcd[$to][$j][4]."</td>";
					$output = $output."<td>".$tmp."</td>";
					$output = $output."<td>".$packetrcd[$to][$j][2]."</td></tr>";
					$laps = $packetrcd[$to][$j][3] - $packetrcd[$to][$j-1][3];
					if ($laps <= $lapsec && $packetrcd[$to][$j-1][4] == $packetrcd[$to][$j][4]) {
						$totalsec = $totalsec + $laps;						
					}
				}
				$totalbytes = $totalbytes + $packetrcd[$to][$j][2];
			}
			
			$totalbytes = $totalbytes/1024;
			$totalbytes = number_format($totalbytes, 2);
			$pcdnld["$to"] = $totalbytes;
			
			if ($totalsec == 0) {
				$totalsec = 1;
			}
			$secdatforsort["$to"] = $totalsec;
			$h = (int)($totalsec/3600);
			$m = (int)(($totalsec - $h*3600)/60);
			$s = (int)(($totalsec - $h*3600- $m*60));
			if ($h<10) {
				$h = "0$h";
			}
			if ($m<10) {
				$m = "0$m";
			}
			if ($s<10) {
				$s = "0$s";
			}
			$pcsec["$to"] = "$h:$m:$s";//$totalsec;
			
			$output = $output."<tr><th colspan=3>Total Time ($ename[$i]): ".$pcsec["$to"].".</th><tr>";
			$output = $output."<tr><th colspan=3>Total Download ($ename[$i]): ".$pcdnld["$to"]." KB.</th><tr>";
			$output = $output."</"."table>";
			$output = $output.$notes;
			$fileoutput = $ename[$i]."_".$to."_".$inputname.".html";
			$fpoutput = fopen($iptrafpath.$fileoutput, "w");
			fputs($fpoutput, "<ht"."ml>");
			fputs($fpoutput, $output);
			//fputs($fpoutput, "</ht>"."ml>");
			fclose($fpoutput);
			
		}
		flush();				
	}
						
###############################################		
// 	write summary
###############################################	
	arsort($secdatforsort);
	$fileoutput = "$iptrafpath$inputname.html";
	$filecsv = "$iptrafpath$inputname.csv";
	
	$fpoutput = fopen($fileoutput, "w");
	$fpoutputcsv = fopen($filecsv, "w");
	
	fputs($fpoutput, "<ht"."ml>");
	$tmp = "<h1>Summary from log file $inputname$inputext</h1>";
	$tmpcsv = "Summary from log file $inputname$inputext\n";
	fputs($fpoutputcsv,$tmpcsv);
	$tmp = $tmp."<t"."able border=1>";
	$tmp = $tmp."<tr><th>Name</th><th>PC Address</th><th>Time</th>
		<th>Download (KB)</th></tr>";
	$tmpcsv = "\nName,PC Address,Time,Download (KB)\n";
	fputs($fpoutputcsv,$tmpcsv);
	for(reset($secdatforsort); $to = key($secdatforsort); next($secdatforsort)) {
   		//echo "$to = ".$secdatforsort[$to]."<br>";
		$nm = $nmfrmip[$to];
			//for ($i=0; $i<$ipno; $i++) {
			//$to = $ip[$i];
		if ($pcctr[$to]>0) {
			$fileoutput = $nm."_".$to."_".$inputname.".html";
			//$fileoutput = $ename[$i]."_".$to."_".$inputname.".html";
			//echo "http://$SERVER_NAME/$rptreldir$fileoutput<br>";
			//http://$SERVER_NAME
			$tmp = $tmp."<tr><td>$nm</td><td>".
				"<a href=\"/$rptreldir$fileoutput\" target=\"_blank\">
				$LANipadd.$to</a></td><td>".$pcsec["$to"]."</td><td>".$pcdnld["$to"]."</td><tr>";
			$tmpcsv = "$nm,$LANipadd.$to,".$pcsec["$to"].',"'.$pcdnld["$to"].'"';
			fputs($fpoutputcsv,$tmpcsv."\n");
		}
	}
	$tmpcsv= "\nNote:\n";
	fputs($fpoutputcsv,$tmpcsv);
	$tmpcsv= ",\"If two consecutive packets are $lapsec seconds apart, a new connection is considered.\"";
	fputs($fpoutputcsv,$tmpcsv."\n");
	$tmpcsv = ",\"Total download volume is an estimation only.\"";
	fputs($fpoutputcsv,$tmpcsv."\n");
	
	$tmp = $tmp."</"."table>";
	$tmp = $tmp.$notes;
	echo $tmp;
	//$tmp = ereg_replace("iptraflog/","",$tmp);
	fputs($fpoutput,$tmp);

	//IP list
	arsort($alliplist);
	$tmp = "<br><h2>List of Most Visited Sites</h2>";
	$tmpcsv = "\nList of Most Visited Site\n";
	fputs($fpoutputcsv,$tmpcsv);
	$tmp = $tmp."<t"."able border=1><tr><th>Address</th><th>Hits</th></tr>";
	$tmpcsv = "Address,Hits\n";
	fputs($fpoutputcsv,$tmpcsv);
	for(reset($alliplist); $key = key($alliplist); next($alliplist)) {
		if ($alliplist[$key] >= $hitsctr) {
			$key0 = trim($key);
			$tmp = $tmp."<tr><td><a href=\"http://$key0\" target=\"_blank\">$key0</a></td>
			<td>".$alliplist[$key]."</td></tr>";
			$tmpcsv ="$key0,$alliplist[$key]\n";
			fputs($fpoutputcsv,$tmpcsv);
		}
	}
	$tmp = $tmp."</t"."able>";
	echo $tmp;
	fputs($fpoutput,$tmp);
	fputs($fpoutput,"</ht"."ml>");
	fclose($fpoutput);
	fclose($fpoutputcsv);
	echo "<hr><h4>Download Summary File 
		<a href=\"http://$SERVER_NAME/$rptreldir$inputname.html\" target=\"_blank\"><b>[HTML]<b></a>
		<a href=\"http://$SERVER_NAME/$rptreldir$inputname.csv\" target=\"_blank\"><b>[CSV]<b></a>
		</h4>";
	echo "<br><h4><a href=\"http://$SERVER_NAME/$rptreldir\" target=\"_blank\">View All Summary Files</a></h4>";

	echo "<hr>";
	echo "<br>Total Records: $rcdctr<br><br>";
	echo "<table border=1>";
	echo "<tr><th>No Field</th><th>Entry</th><th>%</th></tr>";
	for ($i=0; $i<10; $i++) {
		if ($fldno[$i]>5) {
			$tmp = number_format(100*$fldno[$i]/$rcdctr, 2);
			echo "<tr><td>$i</td><td>$fldno[$i]</td><td>$tmp</td></tr>";
		}
	}
	echo "</table><br>";
	echo "<br>From ".$tstart." to ".date("H:i:s")."<br>";
}
echo "<hr><a href=#top>Back to top</a><br><br>";

function dateconvert_date($dstr) {
	global $mths;
	$addh= 10; //10 normal season; 11 daylight saving time
	$addm = 30;
	$dstr = ereg_replace(":"," ",$dstr);
	$tmp = explode(" ",$dstr);
	
	$newdstr = strftime ("%b %d", mktime ($tmp[3]+$addh,$tmp[4],$tmp[5],$mths[$tmp[1]],$tmp[2],$tmp[6]));
	//mktime (hour,min,sec,month,day,year)
/*
	for ($i=0; $i<count($tmp); $i++) {
		echo "$i  ".$tmp[$i]."<br>";
	}
1 Jun	=month
2 18	=day
3 03	=hour
4 36	=min
5 25	=sec
6 2002	=year
	echo $dstr."<br>";
	echo $newdstr ."<br>";
	exit;
//*/
	return $newdstr;
}

function dateconvert($dstr) {
	global $mths;
	//echo $dstr."<br>";
	$addh=10; //normal season; 11 daylight saving time
	$dstr = substr($dstr,11,8);
	$dstr = ereg_replace(":","",$dstr);
	$h = substr($dstr,0,2);
	$m = substr($dstr,2,2);
	$s = substr($dstr,4,2);
	$m = $m + 30;
	if ($m >=60 ) {
		$m = $m - 60;
		$h = $h + $addh;
	} else {
		$h = $h + $addh-1;
	}
	if ($h > 24) {
		$h = $h - 24;
	}
	if ($h <10) {
		$h = "0".$h;
	}
	if ($m <10) {
		$m = "0".$m;
	}
	$sec = $h*3600 + $m*60 + $s;
	$dstr = "$h:$m:$s@$sec";
	//$dstr = "$h:$m"."<br>";
	//$dstr = "$h$m$s"."<br>";
	//echo $mths["Aug"]." =aug<br>";
	//echo $dstr."<br>";
	//setlocale ('LC_TIME', 'en_US');
	//echo strftime ("%b %d %Y %H:%M:%S", mktime (20+10,0,0,12,31,98))."<br><br>";
	//echo gmstrftime ("%b %d %Y %H:%M:%S", mktime (20,0,0,12,31,98))."<br><br>";
	return $dstr;
}
	
function filelist($dir,$inputname) {
	$d = dir($dir);
	$filectr=0;
	while($entry=$d->read()) {
		$entry0 = ereg_replace("log$","",$entry);
		if ($entry0 != $entry) {
			$filelist[$filectr] = $entry;
			$filectr++;
	   	}
	}
	$d->close();
	rsort($filelist);
	for ($i=0; $i<count($filelist); $i++) {
		$j =  $i+1;
		if ($inputname == $filelist[$i]) {
			echo "<optio"."n selected value=\"$filelist[$i]\">$j:  $filelist[$i]";
		} else {
			echo "<opti"."on value=\"$filelist[$i]\">$j:  $filelist[$i]";
		}
	} 
}
?>
</body>
