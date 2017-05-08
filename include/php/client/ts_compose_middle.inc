<html>

<?php
##	section: project code
	echo '<p><table align="left" border="0" width="100%">';// width="100%"
	$specialobject = 0;
	$pubtravel = 5;
	for ($kk=0; $kk<$ij; $kk++) {
		$tail = "";
		$val = $code_order[$kk];
		if ($val<0) {
			$val = abs($val)-1;
			if ($codeprefix_list[$val][1] == "") {
				$codeprefix_list[$val][1] = "Other";
			}
			echo "<tr><td colspan=2 bgcolor=$color width=\"100%\"><font color=#0000ff><b>";
			echo $codeprefix_list[$val][2]."</b></font></td></tr>";
			//strtoupper($first_name)."'s project codes: "
		} else {
			$i = $val;
			#	lable
			$name = $pri_codelist[0][$i];
			$value = ereg_replace("__", "&", $name);
			$value = ereg_replace("_", " ", $value);
			$popup = addslashes($pri_codelist[3][$i]);
			$dbtimefound = $dbrcd_list[$name];
			
			echo "<tr><td";
			//echo " onMouseOut=\"ClearFlyOver();\" onMouseOver=\"FlyOver('$popup');\"";
			echo ">";
			//echo $value;
			
			echo "<a onMouseOver=\"self.status='$popup'; return true\" onMouseOut=\"displaytimeonsattusbar(); return true\">$value</a>";
			echo '</td>';

			#	text box for time entry
			// 15 divisible
			$d15	=	strtoupper(trim($pri_codelist[2][$i]));
			if ($d15 == "Y") {
				$head = "d15";
			} else {
				$head = "";
			}
			$tail	=	trim($pri_codelist[1][$i]);
			//echo $i.": ". $pri_codelist[3][$i]."<br>tail= $tail<br><br>";
			$slist = 0;
			if ($tail == "date") {
				if ($pri_codelist[3][$i] == "Annual Leave") {
					$tail = "_adate";
				} elseif ($pri_codelist[3][$i] == "Sick leave") {
					$tail = "_sdate";
				} elseif ($pri_codelist[3][$i] == "Long Service Leave") {
					$tail = "_ldate";
				}
				$slist = 1;
			} elseif ($tail == "text") {
				$tail = "_text";
				$slist = 1;
			} elseif ($tail== "time,company,country") {
				$tail = "_tcc";
				$slist = 1;
			} elseif ($tail== "travel") {
				$tail = "_trvl";
				//echo $i.": ". $pri_codelist[3][$i]."<br>tail= $tail<br><br>";
				$slist = 1;
			} else {
				$tail = "";
			}

			$name = $head.$name.$tail;
			echo "<td><input onBlur=\"timesum();\" type=\"text\" size=\"10\" name=\"$name\" ";
			echo " value=\"$dbtimefound\"";
			echo '></td></tr>';
			$noobject++;
		}
		
		//0: brief_code; 1:special; 2:div15; 3:description
		if ($slist == 1) {
			//case 1: date
			if ($tail == "_adate") {
				echo "<tr><td bgcolor=$bgdcolor rowspan=1>&nbsp;</td>";
				echo "<td><table bgcolor=$bgdcolor><tr><th>Mon.</th><th>Tues.</th><th>Wed.</th><th>Thur.</th><th>Fri.</th><th>Total</th></tr>";
				$size0 = 5;
				$name = "annual";
				echo "<tr>";
				$obid1 = $noobject -1;
				$tmpdb = $pri_codelist[0][$i];
				for ($ti=1; $ti<=5; $ti++) {
					$timedb = $dbrcd_leavercd_list[$tmpdb][$ti];
					$name1 = "special_".$name.$ti;
					echo "<td><input onBlur=\"leavesum('$name','$obid1');\" type=\"text\" size=\"$size0\" 
						name=\"$name1\" value=\"$timedb\"></td>";
					$noobject++;
				}
				echo "<td align=center><b><div ID=\"$name\">&nbsp;</div></b></td></tr>";
				echo "</table></td></tr>";
			} elseif ($tail == "_sdate") {
				echo "<tr><td bgcolor=$bgdcolor rowspan=1>&nbsp;</td>";
				echo "<td><table bgcolor=$bgdcolor><tr><th>Mon.</th><th>Tues.</th><th>Wed.</th><th>Thur.</th><th>Fri.</th><th>Total</th></tr>";
				$size0 = 5;
				$name = "sick";
				echo "<tr>";
				$obid1 = $noobject -1;
				$tmpdb = $pri_codelist[0][$i];
				for ($ti=1; $ti<=5; $ti++) {
					$timedb = $dbrcd_leavercd_list[$tmpdb][$ti];
					$name1 = "special_".$name.$ti;
					echo "<td><input onBlur=\"leavesum('$name','$obid1');\" type=\"text\" size=\"$size0\" 
						name=\"$name1\" value=\"$timedb\"></td>";
					$noobject++;
				}
				echo "<td align=center><b><div ID=\"$name\">&nbsp;</div></b></td></tr>";
				echo "</table></td></tr>";
			} elseif ($tail == "_ldate") {
				echo "<tr><td bgcolor=$bgdcolor rowspan=1>&nbsp;</td>";
				echo "<td><table bgcolor=$bgdcolor><tr><th>Mon.</th><th>Tues.</th><th>Wed.</th><th>Thur.</th><th>Fri.</th><th>Total</th></tr>";
				$size0 = 5;
				$name = "lsl";
				echo "<tr>";
				$obid1 = $noobject -1;
				$tmpdb = $pri_codelist[0][$i];
				for ($ti=1; $ti<=5; $ti++) {
					$timedb = $dbrcd_leavercd_list[$tmpdb][$ti];
					$name1 = "special_".$name.$ti;
					echo "<td><input onBlur=\"leavesum('$name','$obid1');\" type=\"text\" size=\"$size0\" 
						name=\"$name1\" value=\"$timedb\"></td>";
					$noobject++;
				}
				echo "<td align=center><b><div ID=\"$name\">&nbsp;</div></b></td></tr>";
				echo "</table></td></tr>";
			} elseif ($tail == "_text") {
				$tmp = $pri_codelist[0][$i];
				$activitydb = $dbrcd_research_list[$tmp];
				$name = ereg_replace("d15", "special_", $name);
				echo "<tr><td bgcolor=$bgdcolor rowspan=1>&nbsp;</td>";
				$name= $name."area";
				if ($activitydb == "") {
					$activitydb = "Please enter text which describes the activities undertaken.";
				}
				echo "<td><textarea name=\"$name\" rows=\"4\" cols=\"45\">$activitydb</textarea>";
				$noobject++;
			} elseif ($tail== "_tcc") {
				echo "<tr><td bgcolor=$bgdcolor rowspan=1>&nbsp;</td>";
				echo "<td><table bgcolor=$bgdcolor><tr><th>Time</th><th>Company</th><th>Country</th></tr>";
				$dbtmp = $pri_codelist[0][$i];

				$size0 = 10;
				$name = ereg_replace("d15", "", $name);
				$name0 = "special_".$name;
				$obid1 = $noobject;
				$obid2 = $noobject + 3*$pubtcc - 2;
				$specialobject++;
				for ($tcc=1; $tcc<=$pubtcc; $tcc++) {
					$dbtime = $dbrcd_marketing_list[$dbtmp][0][$tcc];
					$dbcompany = $dbrcd_marketing_list[$dbtmp][1][$tcc];
					$dbcountry = $dbrcd_marketing_list[$dbtmp][2][$tcc];;
					$tname = $name0.$tcc."time";
					echo "<tr>	<td><input onBlur=\"tccsum('tcc$specialobject','$obid1','$obid2');\" type=\"text\" size=\"$size0\" 
					name=\"$tname\"  value=\"$dbtime\"></td>";
					$noobject++;
					$tname = $name0.$tcc."company";
					echo "<td><select name=\"$tname\">";
						include("ts_company_list.inc");
						echo "</select>";
					echo "</td>";
					$noobject++;
					$tname = $name0.$tcc."country";
					echo "<td><select name=\"$tname\">";
						include("ts_country_list.inc");
						echo "</select>";
					echo "</td>";
					$noobject++;
					echo "</tr>";
				}
				echo "<tr><td align=left><b><div ID=\"tcc$specialobject\">&nbsp;</div></b></td></tr>";
				echo "</table></td>";
			} elseif ($tail== "_trvl") {
				echo "<tr><td bgcolor=$bgdcolor colspan=2>";
				echo "<table bgcolor=$bgdcolor><tr><th>No</th><th>Company</th><th>Minutes</th><th>Activity</th></tr>";
				if ($travelrcd > $pubtravel ) {
					$pubtravel = $travelrcd-1;
				}
				$size0 = 10;
				$name = ereg_replace("d15", "", $name);
				$name0 = "special_".$name;
				$obid1 = $noobject;
				$obid2 = $noobject + 3*$pubtravel - 2;
				$specialobject++;
				for ($travel=1; $travel<=$pubtravel; $travel++) {
					$dbtime = $dbrcd_travel_list[0][$travel];
					$dbcompany = $dbrcd_travel_list[1][$travel];
					$dbactivity = $dbrcd_travel_list[2][$travel];
					echo "<tr><th>$travel</th>";
					#select company
					$tname = $name0.$travel."company";
					$addvalue = "1";
					echo "<td><select name=\"$tname\">";
						include("ts_company_list.inc");
						echo "</select>";
					echo "</td>";
					$noobject++;

					#Enter minutes
					$tname = $name0.$travel."time";
					echo "<td><input onBlur=\"travelnochk('travel$specialobject','$obid1','$obid2','$noobject');\" 
					type=\"text\" size=\"$size0\" 
					name=\"$tname\" value=\"$dbtime\"></td>";
					$noobject++;

					$tname = $name0.$travel."activity";
					echo "<td><textarea name=\"$tname\" cols=40 rows=2>$dbactivity</textarea></td></tr>";
					$noobject++;
				}
				echo "<tr><th colspan=2><b><div ID=\"traveldiv\">&nbsp;</div></b></th></tr>";
				echo "</table></td>";
			}
		}
		flush();
	}
?>

<script language=javascript>
function displaytimeonsattusbar() {
	stavar = 	obtochange.innerText	;
	window.status = obtochange.innerText	;
}
</script>