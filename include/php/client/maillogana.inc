<?php
$debug = 0;
$domain="\@rla\.com\.au";

########	Define directory
$maindir="/usr/local/apache/htdocs/";

########	Define log files
$emaillogfiles[0]=$maindir."maillog";
for ($i=1; $i<5; $i++) {
	$emaillogfiles[$i]=$maindir."maillog.$i";
}

$cur = time();
$timeoffset = $novar*3600;
if ($dayhour == 0) {
	$timeoffset = 24*$timeoffset; 
}
$secstart = $cur - $timeoffset;
//$first_name</font> in last $novar .$dh[$dayhour]

########	Define files to be used in the search
$fileend=0;
for ($i=0; $i<5; $i++) {
	$filenew = $emaillogfiles[$i];
	#echo "<br>$filenew<br>";
	$fhlog	=	fopen($filenew ,'r');
	$logstr = fgets($fhlog,512);
	#echo $logstr."<br>";
	preg_match_all("/\d{10}/x", $logstr, $timesec);
	if ($timesec[0][0] < $secstart) {
		$fileend = $i;
		break;
	}
}
/*
echo "$cur = cur<br>";
echo "$timeoffset = timeoffset<br>";
echo "$secstart = secstart<br>";
echo $timesec[0][0]." = timesec<br>";
echo "last file to be used ".$emaillogfiles[$fileend]."<br>";
//*/

//Find mail entries (one line list)
$mctr=0;
$fileline=0;
$debug = 0;
$searchstr = "$staffname@rla.com.au";
//echo "$searchstr <br>";
$mctr = 0;
for ($i=$fileend; $i>=0; $i--) {
  	$filenew = $emaillogfiles[$i];
  	#print "<br>open file ".$filenew."...<br>";
	$fhlog	=	fopen($filenew ,'r');
	while ($logstr = fgets($fhlog,512)) {
		$alllines[$fileline] = $logstr;
		//1		belong to $email_name
		$newstr = ereg_replace("$searchstr","",$logstr);
		if ($newstr != $logstr) {
			//2		within time frame
			preg_match_all("/\d{10}/x", $logstr, $timesec);
			$sec = $timesec[0][0];
			if ($sec >= $secstart) {
				$torcd=0;
				//Feb 17 12:48:51 1013912331.323571 starting delivery 3295: msg 910999 to local
				$newstrfrom = ereg_replace("from","",$logstr);
				if ($newstrfrom != $logstr) {
					$rcd[$mctr][2]= 1;	#outgoing mail
					#echo "from 1<br>";
				}
				$newstrto = ereg_replace("to local","",$logstr);
				if ($newstrto != $logstr) {
					$rcd[$mctr][2]=2;		#incoming mail
					#echo "to local 2<br>";
				}
				$newstrto = ereg_replace("to=","",$logstr);
				if ($newstrto != $logstr) {
					$rcd[$mctr][2]=2;		#incoming mail
					#echo "to= 2<br>";
				}
				
				if ($mailtype == 0) {//outgoing mail
					if ($rcd[$mctr][2] == 1) {
						$torcd= 1;
					}
				} elseif ($mailtype == 1) {//incoming mail
					if ($rcd[$mctr][2] == 2) {
						$torcd= 1;
					}
				} else {									
					$torcd=1;
				}
				if ( $torcd == 1 ) {
					$rcd[$mctr][0]=$sec;			#time in seconds
					$rcd[$mctr][1]=$fileline;	#line number
					$logstr = ereg_replace("<","(",$logstr);
					$logstr = ereg_replace(">",")",$logstr);
					$logstr = ereg_replace(" woody qmail\:","",$logstr);
					if ($rcd[$mctr][2] == 1) {
						$logstr = ereg_replace("$searchstr","<font color=#ff0000><b>$searchstr</b></font>",$logstr);
					} else {
						$logstr = ereg_replace("$searchstr","<font color=#0000ff><b>$searchstr</b></font>",$logstr);
					}
					$mctr++;
					echo "$mctr  $logstr<br>";
				}
			}
		}
		$fileline++;
	}
}
if ($mctr>1) {
	echo "<br><b>Total mail messages found are $mctr.</b><br><hr>";
} elseif ($mctr == 1) {
	echo "<br><b>Only one mail message found.</b><br><hr>";
} else {
	echo "<br><b>No ($mctr) mail message found.</b><br><hr>";
	exit;
}
########	find mail delivery status
$totalfail=0;
echo "<h2>Mail delivery status and log extract.</h2>";
for ($i=$mctr-1; $i>=0; $i--) {//<
	$k=$mctr-$i;
	print "<br><b>Message $k (";
	if ($rcd[$i][2] == 1) {
		print "<font color=#ff0000>Outgoing</font>";
	} else {
		print "<font color=#0000ff>Incoming</font>";
	}
	print ")</b><br>";
	//print  "time=".$rcd[$i][0]."  id=".$rcd[$i][1]."<br>";

	# find	new msg line
	$j=$rcd[$i][1];	#from this line backward search
	$logstr= $alllines[$j];
	while (ereg_replace("new msg", "", $logstr) == $logstr) {
		if ($debug == 1 ) {
			print "line=$j\t$logstr<br>";
		}
		$j--;
		if ($j<0) {
			$j1=0;
			break;
		}
		$logstr= $alllines[$j];
	}

	$j1 = $j;
	# find	end msg line
	$j=$rcd[$i][1]+1;
	$logstr= $alllines[$j];
	while (ereg_replace("end msg", "", $logstr) == $logstr) {
		$j++;
		if ($j>$fileline) {
			$j1=fileline;
			break;
		}
		$logstr= $alllines[$j];
	}

	$j2 = $j;

#	print related log entry and msg delivery id
	for ($j=$j1; $j<=$j2; $j++) {
		#print "line=$j\t$alllines[$j]<br>";
		$logstr = $alllines[$j];
		$logstr = ereg_replace("<","(",$logstr);
		$logstr = ereg_replace(">",")",$logstr);
		$logstr = ereg_replace(" woody qmail\:","",$logstr);
		if ($rcd[$i][2] == 1) {
			$logstr = ereg_replace("$searchstr","<font color=#ff0000><b>$searchstr</b></font>",$logstr);
		} else {
			$logstr = ereg_replace("$searchstr","<font color=#0000ff><b>$searchstr</b></font>",$logstr);
		}
		print "$logstr<br>";
	}
	
/*
#mail deliver fail/success
	$conl=0;
	$debug = 0 ;
	for ($j=$j1; $j<=$j2; $j++) {
		$logstr= $alllines[$j];
		#May  2 18:27:26 woody qmail: 988793846.440708 delivery 714: failure:
		if (ereg_replace("failure", "", $logstr) != $logstr) {
			$conl=1;
			if ($debug == 1 ) {
				print "line=$j\t$alllines[$j]\n";
				print "line=$j\t$alllines[$j]\n";
			}
			print "Failed to delivery\n";
		} else {
			print "Success\n";
			//goto success;
		}

		$newid = $1;
		#May  2 18:27:26 woody qmail: 988793846.077484 starting delivery 714: msg 36672 to local rp@rla.com.au
		for ($k=$j1; $k<$j; $k++) {
			$logstr= $alllines[$k];
			@tmp = split "$newid:", $logstr;
			if ($#tmp == 1) {
				$jmid=$k;
				#print $jmid."\n";
				//goto pstr;
			}
		}
	pstr:
		for ($k=$j1; $k<$jmid; $k++) {
			$logstr = $alllines[$k];
			print $logstr;
			if (m/from/i) {
				goto failline;
			}
		}
	failline:
		print $alllines[$jmid];
		print $alllines[$j];

		for ($k=$j1; $k<=$j2; $k++) {
			$logstr= $alllines[$k];
			print $logstr;
			if (m/failure/) {
				$conl=1;
			}
		}
	}
	if ($conl == 1) {
		$tmp = "Mail failed to deliver.\n";
		print $tmp;
		$totalfail++;
	}

	$totalsuc= $mctr - $totalfail;
	$tmp="<br>Summary:<br>";
	print $tmp;
	print outfhentry $tmp;
	print $tmp;
	$tmp="<br>Total message found\t$mctr.<br>";
	print $tmp;
	print outfhentry $tmp;
	print $tmp;
	if ($totalsuc>0) {
		$tmp="\Successful\t\t$totalsuc.<br>";
		print $tmp;
		print $tmp;
	}
	if ($totalfail>0) {
		$tmp="Fail\t\t\t$totalfail.<br>";
		print $tmp;
		print $tmp;
	}

	$cur=scalar localtime(time());
	$tmp="<br>Current Time $cur<br>";
	print $tmp;
	print $tmp;
//*/
}
?>
