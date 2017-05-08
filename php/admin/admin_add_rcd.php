<html>

<head>
<meta http-equiv="Content-Language" content="en-au">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<title>Administrator Add Record</title>
<base target="main">
</head>

<body background="rlaemb.JPG" topmargin="4" leftmargin="20">

<?php
include('str_decode_parse.inc');
include("field_verification.inc");
include("regexp.inc");
include("userinfo.inc"); //$userinfo
$userstr	=	"?".base64_encode($userinfo);
include("find_domain.inc");	
echo "<a id=top></a><p align=center><font size=5><b>";
if ($priv	==	'00') {
	echo "Table Records Manipulation</b></font>"."  ";
} elseif ($priv	==	'10') {
	echo "Add New Record to Tables</b></font>"."  ";
} else {
	echo "Add New Company and Country</b></font>"."  ";
}
echo "<a href=\"".$PHP_SELF."$userstr\">[Refresh]</a></p><hr>";

$bgdcolor ="#d2ebb6";
$wkdays[1][0] = "Monday";
$wkdays[2][0] = "Tuesday";
$wkdays[3][0] = "Wednesday";
$wkdays[4][0] = "Thursday";
$wkdays[5][0] = "Friday";
$wkdays[6][0] = "Saturday";
$wkdays[7][0] = "Sunday";
$wkdays[1][1] = "459";
$wkdays[2][1] = "459";
$wkdays[3][1] = "459";
$wkdays[4][1] = "459";
$wkdays[5][1] = "444";
$wkdays[6][1] = "459";
$wkdays[7][1] = "459";

$i = 0;
if ($priv == "00"){
$tablelist[$i] = "projleader";
$i++;
}
$tablelist[$i] = "country";
$i++;
$tablelist[$i] = "company";
$i++;

if ($priv == "00" || $priv == "10"){
$tablelist[$i] = "pub_holidays";
$i++;
$tablelist[$i] = "chargecode";
$i++;
$tablelist[$i] = "code_prefix";
$i++;
$tablelist[$i] = "employee";
$i++;
$tablelist[$i] = "projcodes";
$i++;
}
$no_tables = $i;
if ($mantablename == "") {
	$mantablename = $tablelist[0];
}
if ($action == "") {
	$action = "modrcd";
	$action = "newrcd";
}
#############################################
#########	first form: table list and action
#############################################
echo "<form method=post action=\"$PHP_SELF\">";
include("userstr.inc");
echo "<table><tr></tr>";
	echo "<tr><th align=left>Select Table</th><th align=left>Action</th><td>&nbsp;</td></tr>";
	echo "<tr><td><select name=mantablename>";
		echo "<option>---Select Table---";
		for ($i=0; $i<$no_tables; $i++) {
			$table_UC = $tablelist[$i];
			$table_UC = strtoupper($table_UC);
			$table_UC = ereg_replace("_", " ", $table_UC);
			if ($tablelist[$i] == $mantablename) {
				echo "<option selected value=\"".$tablelist[$i]."\">".$table_UC;
			} else {
				echo "<option value=\"".$tablelist[$i]."\">".$table_UC;
			}
		}
	echo "</select></td>";
	echo "<td><select name=\"action\">";
		if ($action == "" || $action == "newrcd") {
			echo "<option selected value=\"newrcd\">Add New Record";
			if ($priv == "00") {
				echo "<option value=\"modrcd\">Modify Record";
			}
		} elseif ($action == "modrcd") {
			echo "<option value=\"newrcd\">Add New Record";
			echo "<option selected value=\"modrcd\">Modify Record";
		}
	echo "</select></td>";
	echo "<td><input type=\"submit\" value=\"GO\" name=\"modnewrcd\"></td></tr>";
echo "</table></form>";

include("connet_root_once.inc");
if ($mantablename) {
	include("admin_add_rcd_find_fld.inc");
}

#############################################
#########	Second form: Modify or add new record
#############################################
if ($modnewrcd) {
	if (!$mantablename) {
		echo "<hr><b>No table is selected.</b><br><hr>";
		exit;
	}
	if ($mantablename == "projcodes" && $action == "modrcd") {
		echo "<hr><p><font color=#ff0000><b>Please use other page to modify project code.</b></font><br><p><hr>";
		exit;
	}
	if ($mantablename == "code_prefix" && $action == "modrcd") {
		echo "<hr><p><font color=#ff0000><b>Please see DB administrator.</b></font><br><p><hr>";
		exit;
	}
	$table_UC = $mantablename;
	$table_UC = strtoupper($table_UC);
	$table_UC = ereg_replace("_", " ", $table_UC);
	if ($action == "newrcd") {
		echo "<hr><b>Add new record to table \"<font color=#0000ff>$table_UC</font>\"</b><br><br>";
	} elseif ($action == "modrcd") {
		echo "<hr><b>Modify records in table \"<font color=#0000ff>$table_UC</font>\"</b><br><br>";
	}
		
	if ($action == "newrcd") {
		include("admin_add_rcd_form.inc");		
	} elseif ($action == "modrcd") {
		echo "<form method=post action=\"$PHP_SELF\" name=\"fldform\">";
		include("userstr.inc");
		echo "<input type=\"hidden\" name=\"mantablename\" value=\"$mantablename\">";
		echo "<input type=\"hidden\" name=\"action\" value=\"$action\">";
		//List All Records
		if ($mantablename == "projleader") {
			$sql = "SELECT id, leader, codes FROM timesheet.projleader ORDER BY leader;";
		}
		if ($mantablename == "pub_holidays") {
			$sql = "SELECT id, name, date FROM timesheet.pub_holidays ORDER BY date;";
		}
		if ($mantablename == "country") {
			$sql = "SELECT country_id, country, start_date, end_date FROM timesheet.country ORDER BY country;";
		}
		if ($mantablename == "company") {
			$sql = "SELECT company_id, company_name, description, country, date_start, 
				date_end FROM timesheet.company ORDER BY company_name;";
		}
		if ($mantablename == "chargecode") {
			$sql = "SELECT charging_code, rate, date FROM timesheet.chargecode ORDER BY charging_code;";
		}
		if ($mantablename == "code_prefix") {
			$sql = "SELECT codehead_id, code_prefix, codelable FROM timesheet.code_prefix ORDER BY code_prefix;";
		}
		if ($mantablename == "employee") {
			$sql = "SELECT email_name as email_name1, logon_name as logon_name1, title as title1, 
				first_name as first_name1, middle_name as middle_name1, 
				last_name as last_name1, computer_ip_addr as computer_ip_addr1, edu_qualification, admin_title, tech_title, 
				charging_code, rla_ph_ext, private_ph, private_mobile_ph, private_fax, 
				date_employed, date_unemployed, date_of_birth, private_home_page, 
				private_email_add, homeadd_no_st, homeadd_suburb, homeadd_state, 
				homeadd_postcode, homeadd_country, last_modification FROM timesheet.employee ORDER BY email_name1;";
		}
		if ($mantablename == "projcodes") {
			$sql = "SELECT projcode_id, brief_code, description, special, div15, begin_date, 
				end_date FROM timesheet.projcodes ORDER BY brief_code LIMIT 1;";
		}
		
		//projleader pub_holidays	country	company	chargecode		code_prefix	employee	projcodes
		$result = mysql_query($sql);
		include("err_msg.inc");
		$norcd = mysql_num_rows($result);

		if (mysql_num_rows($result)) {
			//projleader pub_holidays	country	company	chargecode		code_prefix	employee	projcodes
			$column = 2;
			echo "<table>";
			if ($mantablename == "projleader") {
				//todo
				echo "$sql<br>Number of record: $norcd<br>In development.";
				exit;
			}
			if ($mantablename == "pub_holidays") {
				while (list($id, $name, $date) = mysql_fetch_array($result)) {
					$date = date("Y-").substr($date, 5, 14);
					$name1 = "pub$id";
					echo "<tr><td>";
					$name0 = "name$id";
					echo "<input type=\"text\" name=\"$name0\" value=\"$name\" size=20>";
					echo "</td><td><input ";
					echo "onBlur=\"field_verification('date');\" ";
					echo "type=\"text\" name=\"$name1\" value=\"$date\" size=20>";
					echo "</td></tr>";
				}
			}
			if ($mantablename == "country") {
				$column = 3;
				echo "<tr><th>Country</th><th>Start Date</th><th>End Date</th></tr>";
				while (list($country_id, $country, $start_date, $end_date) = mysql_fetch_array($result)) {
					echo "<tr><td><input type=\"text\" name=\"country$country_id\" value=\"$country\"></td>";
					echo "<td><input "
						."onBlur=\"field_verification('date');\" "
						."type=\"text\" name=\"start_date$country_id\" value=\"$start_date\" size=20></td>";
					echo "<td><input "
						."onBlur=\"field_verification('date');\" "
						."type=\"text\" name=\"end_date$country_id\" value=\"$end_date\" size=20></td>";
					echo "</tr>";
				}
			}
			if ($mantablename == "company") {
				$column = 2;
				$id = 0;
				while (list($company_id, $company_name, $description, $country, $date_start, 
					$date_end) = mysql_fetch_array($result)) {
					$idp1 = $id + 1;
					echo "<tr><th colspan=\"$column\" align=left bgcolor=$bgdcolor>"
						."Record <font color=#0000ff><a id=$idp1>$idp1</font> in Table \"Company\"";
					if ($id == 0) {
						echo "&nbsp;&nbsp;<a href=#index>[Go to index]</a>";
					} elseif ($id >0 && $id <$norcd-1) {
						echo "&nbsp;&nbsp;<a href=#index>[Go to index]</a>";
						echo "&nbsp;&nbsp;<a href=#top>[Back to top]</a>";
					}
					echo "</th></tr>";
						
					$linklable[$id] = $company_name;
					echo "<tr><td>Company</td>"
						."<td><input type=\"text\" name=\"company_name$company_id\" value=\"$company_name\" size=60></td></tr>";
					echo "<tr><td>Description</td>"
						."<td><textarea type=\"text\" name=\"description$company_id\" rows=3 cols= 51>$description</textarea></td></tr>";
					echo "<tr><td>Country</td>"
						."<td><input type=\"text\" name=\"country$company_id\" value=\"$country\" size=60></td></tr>";
					echo "<tr><td>Date Start</td><td><input "
						."onBlur=\"field_verification('date');\" "
						."type=\"text\" name=\"date_start$company_id\" value=\"$date_start\" size=20></td></tr>";
					echo "<tr><td>Date End</td><td><input "
						."onBlur=\"field_verification('date');\" "
						."type=\"text\" name=\"date_end$company_id\" value=\"$date_end\" size=20></td></tr>";
					echo "<tr><th colspan=\"$column\">&nbsp;</th></tr>";
					$id++;
				}
			}
			if ($mantablename == "chargecode") {
				$column = 3;
				echo "<tr><th>Code</th><th>Rate ($)</th><th>Date</th></tr>";
				$id = 0;
				while (list($charging_code, $rate, $date) = mysql_fetch_array($result)) {
					$hid = "charging_code".$id."_where";
					echo "<tr><td><input type=\"hidden\" name=\"$hid\" value=\"$charging_code\">"
						."<input type=\"text\" name=\"charging_code$id\" value=\"$charging_code\" size=10></td>";
					echo "<td><input type=\"text\" name=\"rate$id\" value=\"$rate\" size=10></td>";
					echo "<td><input "
						."onBlur=\"field_verification('date');\" "
						."type=\"text\" name=\"date$id\" value=\"$date\" size=10></td>";
					echo "</tr>";
					$id++;
				}
			}
			if ($mantablename == "code_prefix") {
				$column = 2;
				echo "<tr><th>Code Prefix</th><th>Code Lable</th></tr>";
				while (list($codehead_id, $code_prefix, $codelable) = mysql_fetch_array($result)) {
					echo "<tr><td><input type=\"text\" name=\"code_prefix$codehead_id\" value=\"$code_prefix\" size=20></td>";
					echo "<td><input type=\"text\" name=\"codelable$codehead_id\" value=\"$codelable\" size=40></td></tr>";
				}
			}
			if ($mantablename == "employee") {
				$column = 2;
				$id = 0;
				while (list($email_name1, $logon_name1, $title1, $first_name1, $middle_name1, 
					$last_name1, $computer_ip_addr1, $edu_qualification, $admin_title, $tech_title, 
					$charging_code, $rla_ph_ext, $private_ph, $private_mobile_ph, $private_fax, 
					$date_employed, $date_unemployed, $date_of_birth, $private_home_page, 
					$private_email_add, $homeadd_no_st, $homeadd_suburb, $homeadd_state, 
					$homeadd_postcode, $homeadd_country, $last_modification) = 
					mysql_fetch_array($result)) {
					$idp1 = $id + 1;
					$first_name1 = ucwords($first_name1);
					$middle_name1 = ucwords($middle_name1);
					$last_name1 = ucwords($last_name1);
					$linklable[$id] = $first_name1." ".$last_name1;
					echo "<tr><th colspan=\"$column\" align=left bgcolor=$bgdcolor>"
						."Record <font color=#0000ff><a id=$idp1>$idp1</font> in Table \"EMPLOYEE\"";
					if ($id == 0) {
						echo "&nbsp;&nbsp;<a href=#index>[Go to index]</a>";
					} elseif ($id >0 && $id <$norcd-1) {
						echo "&nbsp;&nbsp;<a href=#index>[Go to index]</a>";
						echo "&nbsp;&nbsp;<a href=#top>[Back to top]</a>";
					}
					echo "</th></tr>";
					$hid = "email_name".$id."_where";
					echo "<tr><td>Email Name</td><td><input type=\"hidden\" name=\"$hid\" value=\"$email_name1\">"
						."<input type=\"text\" name=\"email_name$id\" value=\"$email_name1\" size=20></td></tr>";
					echo "<tr><td>Logon Name</td>"
						."<td><input type=\"text\" name=\"logon_name$id\" value=\"$logon_name1\" size=20></td></tr>";
					echo "<tr><td>Title</td>"
						."<td><input type=\"text\" name=\"title$id\" value=\"$title1\" size=20></td></tr>";
					echo "<tr><td>First Name</td>"
						."<td><input type=\"text\" name=\"first_name$id\" value=\"$first_name1\" size=20></td></tr>";
					echo "<tr><td>Middle Name</td>"
						."<td><input type=\"text\" name=\"middle_name$id\" value=\"$middle_name1\" size=20></td></tr>";
					echo "<tr><td>Last Name</td>"
						."<td><input type=\"text\" name=\"last_name$id\" value=\"$last_name1\" size=20></td></tr>";
					echo "<tr><td>Computer ID</td>"
						."<td><input type=\"text\" name=\"computer_ip_addr$id\" value=\"$computer_ip_addr1\" size=20></td></tr>";
					echo "<tr><td>Edu Qualification</td>"
						."<td><input type=\"text\" name=\"edu_qualification$id\" value=\"$edu_qualification\" size=20></td></tr>";
					echo "<tr><td>Admin Title</td>"
						."<td><input type=\"text\" name=\"admin_title$id\" value=\"$admin_title\" size=20></td></tr>";
					echo "<tr><td>Tech Title</td>"
						."<td><input type=\"text\" name=\"tech_title$id\" value=\"$tech_title\" size=20></td></tr>";					
					echo "<tr><td>Charging Code</td>"
						."<td><input type=\"text\" name=\"charging_code$id\" value=\"$charging_code\" size=20></td></tr>";
					echo "<tr><td>Ext (ph)</td>"
						."<td><input type=\"text\" name=\"rla_ph_ext$id\" value=\"$rla_ph_ext\" size=20></td></tr>";
					echo "<tr><td>Tele (H)</td>"
						."<td><input type=\"text\" name=\"private_ph$id\" value=\"$private_ph\" size=20></td></tr>";
					echo "<tr><td>Mobile</td>"
						."<td><input type=\"text\" name=\"private_mobile_ph$id\" value=\"$private_mobile_ph\" size=20></td></tr>";
					echo "<tr><td>Fax (H)</td>"
						."<td><input type=\"text\" name=\"private_fax$id\" value=\"$private_fax\" size=20></td></tr>";
					echo "<tr><td>Date Employed</td><td><input "
						."onBlur=\"field_verification('date');\" "
						."type=\"text\" name=\"date_employed$id\" value=\"$date_employed\" size=20></td></tr>";					
					echo "<tr><td>Date Unemployed</td><td><input "
						."onBlur=\"field_verification('date');\" "
						."type=\"text\" name=\"date_unemployed$id\" value=\"$date_unemployed\" size=20></td></tr>";
					echo "<tr><td>Date of Birth</td><td><input "
						."onBlur=\"field_verification('date');\" "
						."type=\"text\" name=\"date_of_birth$id\" value=\"$date_of_birth\" size=20></td></tr>";						
					echo "<tr><td>Private WWW Page</td>"
						."<td><input type=\"text\" name=\"private_home_page$id\" value=\"$private_home_page\" size=20></td></tr>";
					echo "<tr><td>Private Email Address</td>"
						."<td><input type=\"text\" name=\"private_email_add$id\" value=\"$private_email_add\" size=20></td></tr>";	
					echo "<tr><td colspan=\"$column\"><b>Home Address</b></td></tr>";
					echo "<tr><td>No/Street</td>"
						."<td><input type=\"text\" name=\"homeadd_no_st$id\" value=\"$homeadd_no_st\" size=20></td></tr>";
					echo "<tr><td>Suburb</td>"
						."<td><input type=\"text\" name=\"homeadd_suburb$id\" value=\"$homeadd_suburb\" size=20></td></tr>";
					echo "<tr><td>State</td>"
						."<td><input type=\"text\" name=\"homeadd_state$id\" value=\"$homeadd_state\" size=20></td></tr>";
					echo "<tr><td>PostCode</td>"
						."<td><input type=\"text\" name=\"homeadd_postcode$id\" value=\"$homeadd_postcode\" size=20></td></tr>";
					echo "<tr><td>Country</td>"
						."<td><input type=\"text\" name=\"homeadd_country$id\" value=\"$homeadd_country\" size=20></td></tr>";
					echo "<tr><td>Last Modification</td><td><input "
						."onBlur=\"field_verification('date');\" "
						."type=\"text\" name=\"last_modification$id\" value=\"$last_modification\" size=20></td></tr>";
					$id++;
				}
			}
			if ($mantablename == "projcodes") {
				$column = 2;
				$id = 0;
				include("specialentrydef.inc");
				$d15_list[0] = "Y";
				$d15_list[1] = "N";
				while (list($projcode_id, $brief_code, $description, $special, $div15, 
						$begin_date, $end_date) = mysql_fetch_array($result)) {
					$tmp =  $brief_code;
					$tmp = ereg_replace("__", "&", $tmp);
					$tmp = ereg_replace("_", " ", $tmp);					
					$linklable[$id] = $tmp;
					$idp1 = $id + 1;
					echo "<tr><th colspan=\"$column\" align=left bgcolor=$bgdcolor>"
						."Record <font color=#0000ff><a id=$idp1>$idp1</font> in Table \"PROJCODES\"";
					if ($id == 0) {
						echo "&nbsp;&nbsp;<a href=#index>[Go to index]</a>";
					} elseif ($id >0 && $id <$norcd-1) {
						echo "&nbsp;&nbsp;<a href=#index>[Go to index]</a>";
						echo "&nbsp;&nbsp;<a href=#top>[Back to top]</a>";
					} elseif ($id == $norcd-1) {
						echo "&nbsp;&nbsp;<a href=#top>[Back to top]</a>";
					}
					echo "</th></tr>";
					$ids = $projcode_id;
					echo "<tr><td>Brief Code</td>"
						."<td><input type=\"text\" name=\"brief_code$ids\" value=\"$tmp\" size=60></td></tr>";
					echo "<tr><td>Description</td>"
						."<td><textarea name=\"description$ids\" rows=3 cols=51>$description</textarea></td></tr>";
					echo "<tr><td>Special</td>"
						."<td><SELECT name=\"special$ids\">";
						for ($si=0; $si<count($splist); $si++) {
							if ($special == $splist[$si]) {
								echo "<option selected>$splist[$si]";
							} else {
								echo "<option>$splist[$si]";
							}
						}
					echo "</option></select></td></tr>";
					flush();
					echo "<tr><td>15 Divisible? (Y/N)</td>"
						."<td><input type=\"text\" name=\"div15$ids\" value=\"$div15\" size=5></td></tr>";
					/*
						."<td><SELECT name=\"div15$ids\">";
						for ($si=0; $si<count($d15_list); $si++) {
							if ($div15 == $d15_list[$si]) {
								echo "<option selected>$d15_list[$si]";
							} else {
								echo "<option>$d15_list[$si]";
							}
						}
					echo "</option></select></td></tr>";
					*/
					echo "<tr><td>Start Date</td><td><input "
						."onBlur=\"field_verification('date');\" "
						."type=\"text\" name=\"begin_date$ids\" value=\"$begin_date\" size=20></td></tr>";					//
					echo "<tr><td>End Date</td><td><input "
						."onBlur=\"field_verification('date');\" "
						."type=\"text\" name=\"end_date$ids\" value=\"$end_date\" size=20></td></tr>";			
					$id++;
					//exit;
					flush();
				}
			}
			
			echo "<tr><td colspan=\"$column\">&nbsp;<input type=\"hidden\" name=\"norcd\" value=\"$norcd\"></td></tr>";
			echo "<tr><td colspan=\"$column\" align=center>";
			$table_UC = "Update Records";
			echo "<input type=\"submit\" ";
			//echo 'onClick="return confirm(\'Do you really want to submit?\n\nPlease confirm.\');" ';
			echo "name=\"UPDATE\" value=\"$table_UC\"><a id=index></a></td>";
			
			if ($mantablename == "employee") {
				echo "<tr><td colspan=\"$column\">&nbsp;</td></tr>";
				$k = 4;
				$j = 0;
				echo "<tr><td colspan=\"$column\"><b>Staff Index</b></td></tr>";
				echo "<tr><td colspan=\"$column\"><font size=2><b>";
				for ($i=0; $i<$norcd ; $i++) {
					$l = $i + 1;
					if ($j == $k) {
						echo "<a href=#$l>[$linklable[$i]]</a><br>";
						$j = 0;
					} else {
						echo "<a href=#$l>[$linklable[$i]]&nbsp;</a>";
						$j++;
					}
				}
				echo "</b></font></td></tr>";
			} 

			if ($mantablename == "projcodes" || $mantablename == "company") {
				$incemnt = 15;
				$lk = 10;
				echo "<tr><td colspan=\"$column\">&nbsp;</td></tr>";
				if ($mantablename == "projcodes") {
					echo "<tr><td colspan=\"$column\"><b>Project Code Index</b></td></tr>";
				} else {
					echo "<tr><td colspan=\"$column\"><b>Company List Index</b></td></tr>";
				}
				echo "<tr><td colspan=\"$column\"><font size=2><b><table>";
				for ($i=0; $i<$norcd; $i++) {
					$j = $i + 1;
					echo "<tr><th><a id=no$j>$j</th><td><a href=#$j>[$linklable[$i]]</a></td>";
					echo "<td><a href=#top>[Back to top] </a>";
						$bkk = 1;
						$kk = (int)($j/$lk)*$lk;
						if ($kk == $i) {
							while ($bkk<=$norcd) {
								echo "<a href=#no$bkk>[$bkk]</a>";
								$bkk = $bkk + $incemnt;
							}
						}
					echo "</td></tr>";
				}
				echo "</b></font></table></td></tr>";
			}
			echo "</table><p>";
		}
	}
	echo "</form>";
}

#############################################
#########	process new records
#############################################
if ($addnewrcd) {	
	$table_UC = $mantablename;
	$table_UC = strtoupper($table_UC);
	$table_UC = ereg_replace("_", " ", $table_UC);
	if ($mantablename == "projleader") {
		/*
		while (list($key, $val) = each($HTTP_POST_VARS)) {
   			echo "$key => $val<br>";
		}
		//*/
		$codeslist = "";
		if (!$grporind) {
			echo "<hr><h2>Please select from either \"Project Group\" or \"Individual Project\" list</h2>";
			exit;
		} elseif ($leader== "") {
			echo "<hr><h2>Please select a leader name.</h2>";
			exit;
		} elseif ($grporind == "Y") {
			for ($i=0; $i<$nogrp; $i++) {
				$tmp = "codegrp".$i;
				//echo $tmp.", ".$$tmp."<br>";
				if ($$tmp) {
					$codeslist = $codeslist.$$tmp."@";
				}
			}
			//echo "Group<br>$codeslist<br>";
		} elseif ($grporind == "N") {
			for ($i=0; $i<$noind; $i++) {
				$tmp = "codeind".$i;
				//echo $tmp.", ".$$tmp."<br>";
				if ($$tmp) {
					$codeslist = $codeslist.$$tmp."@";
				}
			}
			//echo "Ind<br>$codeslist<br>";
		}
		$existchk = "SELECT id FROM timesheet.projleader WHERE leader='$leader';";
		$result = mysql_query($existchk);
		include("err_msg.inc");
		list($id) = mysql_fetch_array($result);
		if ($codeslist == "" && $grporind == "Y") {
			if ($id) {
				$sql = "UPDATE timesheet.projleader SET grpcode='$grpcode' WHERE id='$id';";
				$result = mysql_query($sql);
				include("err_msg.inc");
				echo "<hr><h2>$leader record for \"<font color=#0000ff>Project Group</font>\" list has been deleted.</h2>";
			} else {
				echo "<hr><h2>Please select code from \"<font color=#0000ff>Project Group</font>\" list.</h2>";
			}
			exit;
		} elseif ($codeslist == "" && $grporind == "N") {
			if ($id) {
				$sql = "UPDATE timesheet.projleader SET codes='$codeslist' WHERE id='$id';";
				$result = mysql_query($sql);
				include("err_msg.inc");
				echo "<hr><h2>$leader record for \"<font color=#0000ff>Individual Projects List</font>\" has been deleted.</h2>";
			} else {
				echo "<hr><h2>Please select code from \"<font color=#0000ff>Individual Projects List</font>\".</h2>";
			}
			exit;
		}
		$existchk = "SELECT id FROM timesheet.projleader WHERE leader='$leader';";
		
		$sql = "INSERT INTO timesheet.projleader SET ";
		if ($grporind == "N") {
			$sql = $sql."id='NULL', leader='$leader', codes='$codeslist';";
		} else {
			$sql = $sql."id='NULL', leader='$leader', grpcode='$codeslist';";
		}
	}
	if ($mantablename == "pub_holidays") {
		$str	=	"'$date'";	
		$sql = "select DAYOFWEEK($str) as wkd;";
		$result = mysql_query($sql);
		include("err_msg.inc");
		list($wkd) = mysql_fetch_array($result);
		$weekdays = $wkd - 1;
		$minutes = $wkdays[$weekdays][1];
		if (trim($name) == "" || trim($date) == "") {
			$msg = "<hr><br>NAME and/or "
				."DATE field have to be filled.";
		}
		$existchk = "SELECT * FROM timesheet.pub_holidays WHERE name='$name' and date='$date';";
		$sql = "INSERT INTO timesheet.pub_holidays SET "
			."id='$id', name='$name', date='$date', weekdays='$weekdays', minutes='$minutes';";
	}
	if ($mantablename == "country") {
		if (trim($country) == "") {
			$msg = "<font color=#ff0000><b>NAME</b></font> <font color=#ff0000><b>DATE</b></font> <hr><br>Country field has to be filled.";
		}
		$existchk = "SELECT * FROM timesheet.country WHERE country='$country';";
		$sql = "INSERT INTO timesheet.country SET "
			."country_id='$country_id', country='$country', "
			."start_date='$start_date', end_date='$end_date';";
	}
	if ($mantablename == "company") {
		if (trim($company_name) == "" || trim($country) == "") {
			$msg = "<font color=#ff0000><b>Country</b></font> <hr><br>Company Name and/or "
			."Country fields have to be filled.";
		}
		$existchk = "SELECT * FROM timesheet.company WHERE company_name='$company_name'";
		$sql = "INSERT INTO timesheet.company VALUES('$company_id', '$company_name', 
			'$description', '$country', '$date_start', '$date_end');";
	}
	if ($mantablename == "chargecode") {
		if (trim($charging_code) == "" || trim($rate) == "") {
			$msg = "<font color=#ff0000><b>Company Name</b></font> <font color=#ff0000><b>Country</b></font> <hr><br>Charging Code and/or "
				."Rate field have to be filled.";
		}
		$existchk = "SELECT * FROM timesheet.chargecode WHERE charging_code='$charging_code';";
		$sql = "INSERT INTO timesheet.chargecode SET "
			."charging_code='$charging_code', rate='$rate', date='$date';";
	}
	if ($mantablename == "code_prefix") {
		if (trim($code_prefix) == "" || trim($codelable) == "") {
			$msg = "<font color=#ff0000><b>Charging Code</b></font> <font color=#ff0000><b>Rate</b></font> <hr><br>Code Prefix and/or "
				."Code Lable field have to be filled.";
		}
		$existchk = "SELECT * FROM timesheet.code_prefix WHERE code_prefix='$code_prefix';";
		$sql = "INSERT INTO timesheet.code_prefix SET "
			."codehead_id='$codehead_id', code_prefix='$code_prefix', codelable='$codelable';";
	}
	if ($mantablename == "employee") {
		if (trim($email_name1) == "" || trim($logon_name1) == "" || trim($first_name1) == "" || trim($last_name1) == "") {
			$msg = "<font color=#ff0000><b>Code Prefix</b></font> <font color=#ff0000><b>Code Lable</b></font> <hr><br>Email name, "
				."Logon Name, "
				."First Name and "
				."Last Name field have to be filled.";
		}
		$existchk = "SELECT * FROM timesheet.employee WHERE email_name='".strtolower($email_name1)."';";
		//$computer_ip_addr1 = getenv("REMOTE_ADDR"); 
		$sql = "INSERT INTO timesheet.employee VALUES('$email_name1', '$logon_name1', 
			'$title1', '$first_name1', '$middle_name1', '$last_name1', '$computer_ip_addr1', 
			'$edu_qualification1', '$admin_title1', '$tech_title1', '$charging_code1', 
			'$rla_ph_ext1', '$private_ph1', '$private_mobile_ph1', '$private_fax1', 
			'$date_employed1', '$date_unemployed1', '$date_of_birth1', '$private_home_page1', 
			'$private_email_add1', '$homeadd_no_st1', '$homeadd_suburb1', '$homeadd_state1', 
			'$homeadd_postcode1', '$homeadd_country1', '$last_modification1', '$tsentry1');";
	}
	if ($mantablename == "projcodes") {
		if (trim($brief_code) == "" || trim($description) == "") {
			$msg = "<font color=#ff0000><b>Email name</b></font><font color=#ff0000><b>Logon Name</b></font><font color=#ff0000><b>First Name</b></font> <font color=#ff0000><b>Last Name</b></font> <hr><br>Country field has to be filled."
				."Last Name field have to be filled.";
		}
		$tmp = trim($brief_code);
		$existchk = "SELECT * FROM timesheet.projcodes WHERE brief_code='$tmp';";
		$sql = "INSERT INTO timesheet.projcodes VALUES('$projcode_id', '$brief_code', 
			'$description', '$special', '$div15', '$begin_date', '$end_date', 'y');";
		//projcode_id, brief_code, description, special, div15, begin_date, end_date, costcenter

	}
	
	if ($msg == "") {
		$result = mysql_query($existchk);
		include("err_msg.inc");
		if (mysql_num_rows($result)) {
			if ($mantablename == "projleader") {
				list($id) = mysql_fetch_array($result);
				$sql = "UPDATE timesheet.projleader SET ";
				if ($grporind == "Y") {
					$sql = $sql."grpcode='$codeslist' where id='$id' and leader='$leader';";
				} else {
					$sql = $sql."codes='$codeslist' where id='$id' and leader='$leader';";
				}
				$result = mysql_query($sql);
				include("err_msg.inc");
				if ($result) {
					$table_UC = $mantablename;
					$table_UC = strtoupper($table_UC);
					$table_UC = ereg_replace("_", " ", $table_UC);
					echo "<hr><b>The following record has been modified to the table \"<font color=#0000ff>$table_UC</font>\"</b><br><br>";
				} else {
					echo "<hr>The following record has failed to be modified to the table \"$table_UC\"<b>The following record has failed to be added to the table \"<font color=#0000ff>$table_UC</font>\"</b><br><br>";
					$table_UC = $mantablename;
					$table_UC = strtoupper($table_UC);
					$table_UC = ereg_replace("_", " ", $table_UC);
					echo "<hr><b>Add new record to table \"<font color=#0000ff>$table_UC</font>\"</b><br><br>";
					include("admin_add_rcd_find_fld.inc");
					include("admin_add_rcd_form.inc");	
					echo "<hr><br><a href=#top>Back to top</a><br><br>";	
					exit;
				}
			} else {
				echo "<hr><b>The following record is existed in the table "
				."\"<font color=#0000ff>$table_UC</font>\". And the action has been cancelled.</b><br><br>";
				//<font color=#ff0000><b>Country</b></font> <font color=#ff0000><b>Last Name</b></font> 
			}
		} else {
			$result = mysql_query($sql);
			include("err_msg.inc");
			if ($result) {
				$table_UC = $mantablename;
				$table_UC = strtoupper($table_UC);
				$table_UC = ereg_replace("_", " ", $table_UC);
				echo "<hr><b>The following record has been added to the table \"<font color=#0000ff>$table_UC</font>\"</b><br><br>";
			} else {
				echo "<hr>The following record has failed to be added to the table \"$table_UC\"<b>The following record has failed to be added to the table \"<font color=#0000ff>$table_UC</font>\"</b><br><br>";
			}
		}
		echo "<table border=1>";
		if ($mantablename == "projleader") {
			$sql = "SELECT id, leader, codes, grpcode FROM timesheet.projleader WHERE leader='$leader';";
			$result = mysql_query($sql);
			include("err_msg.inc");
			list($id, $leader, $codes, $grpcode) = mysql_fetch_array($result);
			echo "<tr><td><b>Leader</b></td><td>$leader</td></tr>";
			echo "<tr><td><b>Code Groups</b></td><td>$grpcode</td></tr>";
			echo "<tr><td><b>Individual Codes</b></td><td>$codes</td></tr>";
		} else {
			for ($i=0; $i<count($name_array); $i++) {
			if (!ereg("auto_increment",$flags[$i])) {
				$tmp = $name_array[$i];
				if ($mantablename == "pub_holidays" && $tmp =="weekdays") {
					$$tmp = $wkdays[$$tmp][0];
				}
				$tmp0 = ereg_replace("__","&",$tmp);
				$tmp0 = ereg_replace("_"," ",$tmp0);
				$tmp0 = ucwords($tmp0);				
				$tmp0 = ereg_replace("Of", "of",$tmp0);
				if ($mantablename == "employee") {
					$tmp0 = ereg_replace("1","",$tmp0);
				}
				echo "<tr><td><b>$tmp0</b></td><td>".$$tmp."</td></tr>";
			}
			}
		}
		echo "</table><p>";
		//echo $sql."<br>";
		//echo $existchk."<br>";
	} else {
		echo $msg." Add new record has been aborted.<br><br>";
	}
	
	$table_UC = $mantablename;
	$table_UC = strtoupper($table_UC);
	$table_UC = ereg_replace("_", " ", $table_UC);
	echo "<hr><b>Add new record to table \"<font color=#0000ff>$table_UC</font>\"</b><br><br>";
	include("admin_add_rcd_find_fld.inc");
	include("admin_add_rcd_form.inc");		
}

#############################################
#########	UPDATE process data
#############################################
if ($UPDATE) {
	$table_UC = $mantablename;
	$table_UC = strtoupper($table_UC);
	$table_UC = ereg_replace("_", " ", $table_UC);
	echo "<hr><b>Update $norcd records for table \"<font color=#0000ff>$table_UC</font>\".</b><br><br>";
	if ($mantablename == "pub_holidays") {
		echo "<table border=1>";
		echo "<tr><th>Name</th><th>Date</th><th>Weekdays</th><th>Minutes</th></tr>";
		$i=1;
		$pubid = "pub$i";
		while ($$pubid) {
			$str	=	"'".$$pubid."'";	
			# find day of the week for today
			$sql = "select DAYOFWEEK($str) as wkd;";
			$result = mysql_query($sql);
			include("err_msg.inc");
			list($wkd) = mysql_fetch_array($result);
			$wkd = $wkd - 1;
			$name0 = "name$i";
			$name = $$name0;
			$weekdays = $wkdays[$wkd][0];
			$minutes = $wkdays[$wkd][1];
			$date = $$pubid;
			$sql = "UPDATE timesheet.pub_holidays SET name='$name', date='$date', weekdays='$wkd', minutes='$minutes' "
				."WHERE id='$i';";
			$result = mysql_query($sql);
			include("err_msg.inc");
			$i++;
			$pubid = "pub$i";
			$tmp = "";
		}
		$sql = "SELECT name, date, weekdays, minutes FROM timesheet.pub_holidays ORDER BY date;";
		$result = mysql_query($sql);
		include("err_msg.inc");
		if (mysql_num_rows($result)) {
			while (list($name, $date, $weekdays, $minutes) = mysql_fetch_array($result)) {
				echo "<tr><td>$name</td><td>$date</td><td>".$wkdays[$weekdays][0]."</td><td>$minutes</td></tr>";
			}
		}
		echo "</table><p>";
	}
			
	if ($mantablename == "country") {
		//company_id, company_name, description, country, date_start, date_end 
		$lbl = "Country@Start Date@End Date";
		$fld = "country, start_date, end_date";
		$where = "country_id";
		$value = "id";
	}
	if ($mantablename == "company") {
		//company_id, company_name, description, country, date_start, date_end 
		$lbl = "Company@Description@Country@Start Date@End Date";
		$fld = "company_name, description, country, date_start, date_end";
		$where = "company_id";
		$value = "id";
	}
	if ($mantablename == "chargecode") {
		//charging_code, rate, date 
		$lbl = "Charging Code@Rate@Date";
		$fld = "charging_code, rate, date";
		$where = "charging_code";
		$hihhen = "charging_code";
		$value = "id";
	}
	if ($mantablename == "code_prefix") {
		//codehead_id, code_prefix, codelable
		$lbl = "Code Prefix@Code Lable";
		$fld = "code_prefix, codelable";
		$where = "codehead_id";
		$value = "id";
	}
	if ($mantablename == "employee") {
		$fld = "email_name, logon_name, title, first_name, middle_name, "
			."last_name, computer_ip_addr, edu_qualification, admin_title, tech_title, "
			."charging_code, rla_ph_ext, private_ph, private_mobile_ph, private_fax, "
			."date_employed, date_unemployed, date_of_birth, private_home_page, "
			."private_email_add, homeadd_no_st, homeadd_suburb, homeadd_state, "
			."homeadd_postcode, homeadd_country, last_modification";
		$lbl = ereg_replace(", ", "@", $fld);
		$lbl = ereg_replace("_", " ", $lbl);
		$lbl = ucwords($lbl);
		$where = "email_name";
		$hihhen = "email_name";
		$value = "id";
	}
	if ($mantablename == "projcodes") {
		$fld = "brief_code, description, special, div15, begin_date, end_date";
		$lbl = ereg_replace(", ", "@", $fld);
		$lbl = ereg_replace("_", " ", $lbl);
		$lbl = ucwords($lbl);
		$where = "projcode_id";
		$value = "id";
	}

	if ($mantablename != "pub_holidays") {
		$fldexp = explode(", ", $fld);
		$column = count($fldexp);
		for ($id=0; $id<=$norcd; $id++) {
			for ($j=0; $j<$column; $j++) {
				$tmp = $fldexp[$j].$id;
				$fdbk[$id][$j] = $$tmp;
			}
			if ($hihhen) {
				$tmp = $hihhen.$id."_where";
				$hidden_list[$id] = $$tmp;
			}
		}
		for ($id=0; $id<=$norcd; $id++) {
			if ($fdbk[$id][0] != "") {
				$sql = "UPDATE timesheet.$mantablename SET ";
				for ($j=0; $j<count($fldexp)-1; $j++) {
					$sql = $sql.$fldexp[$j]."='".$fdbk[$id][$j]."', ";
				}
				$j = count($fldexp)-1;
				$sql = $sql.$fldexp[$j]."='".$fdbk[$id][$j];
				if (!$hihhen) {
					$sql = $sql."' WHERE $where='".$$value."';";
				} else {
					$sql = $sql."' WHERE $where='".$hidden_list[$id]."';";
				}
				$result = mysql_query($sql);
				include("err_msg.inc");
				//echo $sql."<br>";
			}
		}

		echo "<table border=1><tr>";
		$tmp = explode("@", $lbl);
		for ($i=0; $i<count($tmp); $i++) {
			echo "<th>$tmp[$i]</th>";
		}
		echo "</tr>";
		for ($i=0; $i<=$norcd; $i++) {
			echo "<tr>";
			for ($j=0; $j<$column; $j++) { 
				echo "<td>".$fdbk[$i][$j]."</td>";
			}
			echo "</tr>";
		}		
		echo "</table><p>";
	}
}

if ($projgrp) {
	echo "<form method=\"post\" name=\"myform\">";
	echo "<input type=\"hidden\" name=\"projgrp\" value=\"$projgrp\">";
	echo "</form>";
}
?>
<hr><br><a href=#top>Back to top</a><br><br>
</body>
