<html>

<head>
<title>Library Patent Dump From Text File</title>
</head>
<body leftmargin="20" bgcolor="#FFFFCC" text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="rlaemb.JPG">

<?php
include("admin_access.inc");
include('rla_functions.inc');
include("connet_other_once.inc");
//include("lib_patent_pdf_entry.inc");

echo "<id=top><h2 align=center>Library Patent Dump From Text File
<a href=\"$PHP_SELF$admininfo\"><font size=2>[Refresh]</font></a></h2><hr>";
//exit;

#################################################################
## Delete all records from dumppatent table
#################################################################
/*
	$sql = "DELETE FROM library.dumppatent;";
	//$sql = "DELETE FROM library.dumppatent WHERE id>='$delfrom';";
	$result = mysql_query($sql);
	include("err_msg.inc");
	echo $sql;
	//exit;
	/*
//*/

//include("lib_delete_other_entry.inc");
//include("patent_dump_modify.inc");
#################################################################
## load text file form
#################################################################
/*
echo "<form method=post action=\"$PHP_SELF\">";
	include("userstr.inc");
	$i=0;
	$FileExtList[$i] = ".txt"; $i++;
	$FileExtList[$i] = ".doc"; $i++;
	echo "<table border=1>";
	echo "<tr><th>File Type</th><th>File Name</th><th>File Ext</th></tr>";
	echo "<tr><th align=left>Input</th>";
		if (!$inputname) {
			$inputname = "pat0";
		}
		
		echo "<td><input type=text name=inputname value=\"$inputname\" size=20></td>";
		if (!$inputext) {
			$inputext = $FileExtList[0];
		}
		echo "<td><select name=inputext>";
		for ($j=0; $j<$i; $j++) {
			if ($inputext == $FileExtList[$j]) {
				echo "<option selected>$FileExtList[$j]";
			} else {
				echo "<option>$FileExtList[$j]";
			}
		}
		echo "</td></tr>";

	echo "<tr><th align=left>Output</th>";
		echo "<td>---</td>";
		if (!$outputext) {
			$outputext = $FileExtList[1];
		}
		echo "<td><select name=outputext>";
		for ($j=0; $j<$i; $j++) {
			if ($outputext== $FileExtList[$j]) {
				echo "<option selected>$FileExtList[$j]";
			} else {
				echo "<option>$FileExtList[$j]";
			}
		}
		echo "</td></tr>";

	echo "<tr><th align=left>File path</th><td colspan=2>
		<input type=text name=filepath value=\"/usr/local/apache/htdocs/patent/\" size=40></td></tr>";
	
	echo "<tr><th align=left>Data Conversion</th>";
		$dat[0] = "Y";
		$dat[1] = "N";
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
	echo "<tr><th align=left>Entry Start</th><td colspan=2>
		<input type=text name=entrystart value=\"$entrystart\" size=5></td></tr>";
	if (!$increno) {
		$increno = 1000;
	}
	echo "<tr><th align=left>No Process</th><td colspan=2>
		<input type=text name=increno value=\"$increno\" size=5></td></tr>";
		
	echo "<tr><th align=left>Check No Entry</th><td colspan=2>
		<select name=duplicationcheck>";
		$t[0] = "Y";
		$t[1] = "N";
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
	echo "<tr><th align=left>Delete Record From</th><td colspan=2>
		<input type=text name=delfrom value=\"1000000\" size=10></td></tr>";

	echo "<tr><td colspan=3 align=middle><button type=submit name=dumpfiletodb>
		<font size=4>Dump File To Buffer DB</font></button>";
	echo "</table>";
echo "</form>";
//*/

#################################################################
/*
$sql = "UPDATE library.dumppatent SET processed='N' WHERE processed='Y';";
echo $sql."<br>";
$result = mysql_query($sql);
include("err_msg.inc");	
//*/

## process records in Buffer DB to library DB
if ($processbufferdb) {
	$cat_id = 2; //patent
	if ($notoprocess == 0) {
		echo "<b>All Records in Buffer Database Have Been Processed.</b><br>";
		exit;
	}
	$new1 = "<font color=#ff0000>";
	$new2 = "</font>";
	$sql = "SELECT id, country, patent_no, patent_title, assignee, inventor, 
		file_date, patent_date, classification, keycode, pct, keywords, abstract, 
		citations, abstract_only, electronic_copy, processed FROM library.dumppatent 
		WHERE id>='$startfromid' and processed='N' limit $numtobeprocessed;";
	//echo "$sql<br>";
	$result0 = mysql_query($sql);
	include("err_msg.inc");
	while (list($id, $country, $patent_no, $patent_title, $assignee, $inventor, 
		$file_date, $patent_date, $classification, $keycode, $pct, $keywords, $abstract, 
		$citations, $abstract_only, $electronic_copy, $processed) = mysql_fetch_array($result0)) {
		
		echo "<br><br><b>Patent DumpDB ID: $id.</b><br>";
		if (!$abstract) {
			$abstract = $keywords;
			$keywords = "";
		}
		/*
		echo "<table border=1>";
			echo "<tr><th align=left>ID</th><td>$id</td></tr>";
			echo "<tr><th align=left>Country</th><td>$country</td></tr>";
			echo "<tr><th align=left>NO</th><td>$patent_no</td></tr>";
			echo "<tr><th align=left>Title</th><td>$patent_title</td></tr>";
			echo "<tr><th align=left>Assignee</th><td>$assignee</td></tr>";
			echo "<tr><th align=left>Inventor</th><td>$inventor</td></tr>";
			echo "<tr><th align=left>File Date</th><td>$file_date</td></tr>";
			echo "<tr><th align=left>Patent Date</th><td>$patent_date</td></tr>";
			echo "<tr><th align=left>Classification</th><td>$classification</td></tr>";
			echo "<tr><th align=left>Keycode</th><td>$keycode</td></tr>";
			echo "<tr><th align=left>PCT</th><td>$pct</td></tr>";
			echo "<tr><th align=left>Keyword</th><td>$keywords</td></tr>";
			echo "<tr><th align=left>Abstract</th><td>$abstract</td></tr>";
			echo "<tr><th align=left>Citation</th><td>$citations</td></tr>";
			echo "<tr><th align=left>Abstract Only?</th><td>$abstract_only</td></tr>";
			echo "<tr><th align=left>Electronic Copy?</th><td>$electronic_copy</td></tr>";
			echo "<tr><th align=left>Is Processed?</th><td>$processed</td></tr>";
		echo "</table>";
		//*/
		
		$patent_title = trim($patent_title);
		$patent_title = ereg_replace("'","\'",$patent_title);
		$assignee = trim($assignee);
		$assignee = ereg_replace("'","\'",$assignee);		
		$inventor = trim($inventor);
		$inventor = ereg_replace("'","\'",$inventor);
		$classification = trim($classification);
		$classification = ereg_replace("'","\'",$classification);
		$keywords = trim($keywords);
		$keywords = ereg_replace("'","\'",$keywords);		
		$abstract = trim($abstract);
		$abstract = ereg_replace("'","\'",$abstract);
		$citations = trim($citations);
		$citations = ereg_replace("'","\'",$citations);

############################################################
		# step 1: insert data to table lib_primlist to find lib_item_id
		$sql = "INSERT INTO library.lib_primlist SET lib_item_id='NULL', 
			cat_id='$cat_id', libtitle='$patent_title', 
			abstract='$abstract';";
		$result = mysql_query($sql);
		include("err_msg.inc");
		if ($result) {
			$lib_item_id = mysql_insert_id();
			if (!$lib_item_id) {
				echo "<h2><font color=#ff0000>Failed to get new library item ID.</font></h2>";
				exit;
			}
		} else {
			echo "<h2><font color=#ff0000>Failed to enter data to library DB common table.</font></h2>";
			exit;
		}
		echo "lib_primlist ID: $lib_item_id. <br>$sql<br>";

		# step 2: insert data to table to patent tables
		$sql = "INSERT INTO library.for_patent SET patent_id='NULL', 
			lib_item_id='$lib_item_id', country='$country', patent_no='$patent_no', 
			assignee='$assignee', file_date='$file_date', pub_date='$pub_date', 
			patent_date='$patent_date', intnl_clsfcn='$classification', 
			citations='$citations', no_pages='$no_pages', elec_copy_add='$elec_copy_add';";
		$result = mysql_query($sql, $contid);
		include("err_msg.inc");
		if ($result) {
			$new_item_id = mysql_insert_id($contid);
			if (!$new_item_id) {
				echo "<h2><font color=#ff0000>Failed to get new patent item ID.</font></h2>";
				exit;
			}
		} else {
			echo "<h2><font color=#ff0000>Failed to enter data into patent table.</font></h2>";
			exit;
		}
		//echo "for_patent: $$new_item_id. $sql<br>";
		
###########################################################
		# step 3: insert data to table prim_keyword
		## keyword start
		$keyword_no = 0;
		if ($keywords) {
			$keywords = ereg_replace(",", " ", $keywords);
			$keylist = explode(" ",$keywords);
			for ($ik=0; $ik<count($keylist); $ik++) {
				$tmpkws= trim($keylist[$ik]);
				if ($tmpkws) {
					$sql = "SELECT keyword_id FROM library.keywords WHERE keyword='$tmpkws';";
					$result = mysql_query($sql);
					include("err_msg.inc");
					list($keyword_id) = mysql_fetch_array($result);
					if (!$keyword_id) {
						$sql = "INSERT INTO library.keywords VALUES('$keyword_id', '$tmpkws');";
						$result = mysql_query($sql);
						include("err_msg.inc");
						$keyword_id = mysql_insert_id();
						//echo "$new1<b>New keyword: $tmpkws. ID: $keyword_id$new2<br>";
					} else {
						//echo "<b>Keyword: $tmpkws. ID: $keyword_id</b><br>";
					}
					$keyword_id_list[$keyword_no] = $keyword_id;
					$keyword_no++;
				}
			}
		}
		for ($i=0; $i<$keyword_no; $i++) {
			$keyword_id = $keyword_id_list[$i];
			$sql = "INSERT INTO library.prim_keyword VALUES('$lib_item_id', '$keyword_id');";
			$result = mysql_query($sql);
			include("err_msg.inc");
		}
		$keyword_id = "";
		for ($i=0; $i<$keyword_no-1; $i++) {
			$keyword_id = $keyword_id.$keyword_id_list[$i].",";
		}
		$keyword_id = $keyword_id.$keyword_id_list[$keyword_no-1];
		## keyword end */

		# step 4: insert data to table prim_auth
		## inventor start
		$inventor = ereg_replace("@@","@",$inventor);
		$inventor = ereg_replace("@ @","@",$inventor);
		//echo "<br><b>ID: $id. Inventor List: $inventor</b>";
		//echo "<table border>";
		//echo "<tr><th>Full Name</th><th>First Name</th><th>Middle Name</th><th>Given Name</th></tr>";
		$auth = explode("@",$inventor);
		$no_auth = 0;
		for ($i=0; $i<count($auth); $i++) {
			$str1au = explode(",",$auth[$i]);
			if (trim($str1au[0]) && trim($str1au[0])) {
				$aulist[0][$no_auth] = trim($str1au[0]);	//given name
					$givenn = $aulist[0][$i];
				
				$str1au[1] = trim($str1au[1]);
				$str2au = explode(" ",$str1au[1]);
				$aulist[1][$no_auth] = trim($str2au[0]);	//first name
					$firstn = $aulist[1][$i];
				
				$aulist[2][$no_auth] = trim($str2au[1]);	//middle name
					$midnn = $aulist[2][$i];
				
				if (!$firstn) {
					$firstn = "&nbsp;";
				}
				if (!$midnn) {
					$midnn = "&nbsp;";
				}
				//echo "<tr><td>$auth[$i]</td><td>$firstn</td><td>$midnn</td><td>$givenn</td></tr>";
				$no_auth++;
			}
		}
		//echo "</table>";
		
		for ($i=0; $i<$no_auth; $i++) {
			$lastname = $aulist[0][$i];
			$firstname = $aulist[1][$i];
			$middlename = $aulist[2][$i];
			
			$sql = "SELECT firstname_id FROM library.auth_first ".
				"WHERE firstname='$firstname';";
			$result = mysql_query($sql);
			include("err_msg.inc");
			list($firstname_id) = mysql_fetch_array($result);
			if (!$firstname_id) {
				$sql = "INSERT INTO library.auth_first VALUES('NULL', '$firstname');";
				$result = mysql_query($sql);
				include("err_msg.inc");
				$firstname_id = mysql_insert_id();
				//echo "$new1<b>New First Name: $firstname ($firstname_id)$new2<br>";
			} else {
				//echo "<b>First Name ID: $firstname_id</b><br>";
			}

			$sql = "SELECT middlename_id FROM library.auth_middle ".
				"WHERE middlename='$middlename';";
			$result = mysql_query($sql);
			include("err_msg.inc");
			list($middlename_id) = mysql_fetch_array($result);
			if (!$middlename_id) {
				$sql = "INSERT INTO library.auth_middle VALUES('NULL', '$middlename');";
				$result = mysql_query($sql);
				include("err_msg.inc");
				$middlename_id = mysql_insert_id();
				//echo "$new1<b>New Middle Name: $middlename ($middlename_id)$new2<br>";
			} else {
				//echo "<b>Middle Name ID: $middlename_id</b><br>";
			}

			$sql = "SELECT lastname_id FROM library.auth_last ".
				"WHERE lastname='$lastname';";
			$result = mysql_query($sql);
			include("err_msg.inc");
			list($lastname_id) = mysql_fetch_array($result);
			if (!$lastname_id) {
				$sql = "INSERT INTO library.auth_last VALUES('NULL', '$lastname');";
				$result = mysql_query($sql);
				include("err_msg.inc");
				$lastname_id = mysql_insert_id();
				//echo "$new1<b>New Last Name: $lastname ($lastname_id)$new2<br>";
			} else {
				//echo "<b>Last Name ID: $lastname_id</b><br>";
			}

			$sql = "SELECT auth_id FROM library.author WHERE ". 
				"firstname_id='$firstname_id' and middlename_id='$middlename_id' and 
				lastname_id='$lastname_id';";
			$result = mysql_query($sql);
			include("err_msg.inc");
			list($auth_id) = mysql_fetch_array($result);
			if (!$auth_id) {
				$sql = "INSERT INTO library.author SET ".
					"firstname_id='$firstname_id', middlename_id='$middlename_id', ".
					"lastname_id='$lastname_id';";
				$result = mysql_query($sql);
				include("err_msg.inc");
				$auth_id = mysql_insert_id();
				//echo "$new1<b>New Author ID: $auth_id</b>$new2<br>";
			} else {
				//echo "<b>Author ID: $auth_id</b><br>";
			}
			$author_id_list[$i] = $auth_id;
		}
		for ($i=0; $i<$no_auth; $i++) {
			$auth_id = $author_id_list[$i];
			$sql = "INSERT INTO library.prim_auth VALUES('$lib_item_id', '$auth_id');";
			$result = mysql_query($sql);
			include("err_msg.inc");
		}
		$author_id = "";
		for ($i=0; $i<$no_auth-1; $i++) {
			$author_id = $author_id.$author_id_list[$i].",";
		}
		$author_id = $author_id.$author_id_list[$no_auth-1];
		// inventor end*/
		
		# step 5: update table prim_keyword
		$sql = "UPDATE library.lib_primlist SET author_id='$author_id', keyword_id='$keyword_id' ".
			"WHERE lib_item_id='$lib_item_id';";
		echo $sql."<br>";
		$result = mysql_query($sql);
		include("err_msg.inc");

		# step 6: set processed="Y" in table dumppatent
		$sql = "UPDATE library.dumppatent SET processed='Y' WHERE id='$id';";
		echo $sql."<br>";
		$result = mysql_query($sql);
		include("err_msg.inc");	
		echo "<hr>";
	}
	//$country		$patent_no		$patent_title		$assignee		$inventor	$file_date		$patent_date	
	//$classification		$keycode	$pct	$keywords	$abstract	$citations		$abstract_only	
	//$electronic_copy	$processed
}

#################################################################
## Form for processing records from buffer DB
if ($priv == "00") {
	echo "<form method=post action=\"$PHP_SELF\">";
	include("userstr.inc");
	echo "<br><table border=1>";
	$sql = "SELECT id FROM library.dumppatent ORDER BY id DESC LIMIT 1;";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($id) = mysql_fetch_array($result);
	echo "<tr><th align=left>Total Records In Buffer DB</th><td>$id</td></tr>";

	$sql = "SELECT id FROM library.dumppatent WHERE processed!='N';";
	$result = mysql_query($sql);
	include("err_msg.inc");
	$no = mysql_num_rows($result);
	echo "<tr><th align=left>Records Processed from Buffer DB</th><td>$no</td></tr>";
	
	$no = $id - $no;
	echo "<tr><th align=left>Unporcessed Records</th><td>$no</td></tr>";
	echo "<input type=hidden name=notoprocess value=\"$no\">";
/*
	echo "<tr><th align=left>Process Records From</th><td>";
	if (!$startfromid) {
		$startfromid = 1;
	}
	echo "<input type=text name=startfromid value=\"$startfromid\" size=10></td></tr>";
//*/
	echo "<tr><th align=left>Number to be Processed</th><td>";
	if (!$numtobeprocessed) {
		$numtobeprocessed = 500;
	}
	echo "<input type=text name=numtobeprocessed value=\"$numtobeprocessed\" size=10></td></tr>";

	echo "<tr><td colspan=3 align=middle><button type=submit name=processbufferdb>
		<font size=4>Process Buffer DB</font></button></td></tr>";
	echo "</table></form>";
}

#################################################################
## dump file into buffer DB
#################################################################
if ($dumpfiletodb) {
	$sql = "DELETE FROM library.dumppatent WHERE id>='$delfrom';";//$delfrom
	$result = mysql_query($sql);
	include("err_msg.inc");
	//echo $sql;
	//exit;
	/*
	$sql = "SELECT id, patent_no FROM library.dumppatent WHERE LENGTH(patent_no)<'5';";
	$result = mysql_query($sql);
	include("err_msg.inc");
	while (list($id,$patent_no) = mysql_fetch_array($result)) {
		echo "$id: $patent_no<br>";
	}
	exit;
	//*/
	$dumpctr = 0;
	$noabstract = 0;
	$filenum = trim($filenum);
	if (6 < $filenum) {
		echo "Please check File Number";
		exit;
	}
	$fileinput = $filepath.$inputname.$inputext;
	$fpinput = fopen($fileinput, "r");
	
	$filerejectionfld = $filepath.$inputname."_fld".$outputext;
	$fprejfld = fopen($filerejectionfld, "w");
	
	$filerejectionname = $filepath.$inputname."_name".$outputext;
	$fpname = fopen($filerejectionname, "w");

	$filerejectiondate = $filepath.$inputname."_date".$outputext;
	$fpdate = fopen($filerejectiondate, "w");
	/*
	echo "<a href=\"../report/$filerejectionname\" target=\"_blank\">
	<font size=\"2\"><b>[Download or Open File for Author Error (.doc)].</b></font></a><br>";
	echo "<a href=\"../report/$filerejectionfld\" target=\"_blank\">
	<font size=\"2\"><b>[Download or Open File for Entries Rejected (.doc)].</b></font></a><br>";
	//*/
	if ($duplicationcheck == "Y") {
		$fileoutputdup = $filepath.$inputname."_dup.csv";
		$fpdup = fopen($fileoutputdup, "w");
	/*	
		echo "<a href=\"../report/$fileoutputdup\" target=\"_blank\">
		<font size=\"2\"><b>[Download or Open File for Entries Duplication Check(.csv)].</b></font></a><br><br>";
	//*/
	} else {
		echo "<br>";
	}
	
	$tmp = "country, patent_no, patent_title, assignee, inventor, file_date, patent_date, 
		classification, keycode, pct, keywords, citations, abstract_only, electronic_copy";
	$str = explode(", ", $tmp);
	
	$patctr = 1;
	$entry = "";
	$sevendigit = "";
	$namerejctr = 0;
	$namewarning = "";
	$daterejctr = 0;
	$datewarning = "";
	$invotherformat = 0;
	while ($buffer = fgets($fpinput, 5000)) {
		$buffer = trim($buffer);
		$entry = $entry.$buffer;
		$strL = strlen($buffer);
		$end = substr($buffer,$strL-3, $strL);
		if ($end == "1*1" || $end == "0*0" || $end == "1*0" || $end == "0*1") {
			if ($patctr >= $entrystart && $patctr <= $entrystart + $increno) {
			//$entry = ereg_replace("'","\'",$entry);
			//$entry = ereg_replace('"','\"',$entry);
			$tmp = explode("*", $entry);
	  		$no = count($tmp);
	  		if ($no == 14 && $fromentry<=$patctr) {
		  		echo "<font size=2 color=#0000ff><b>Entry $patctr: $tmp[0] -- $tmp[1] ($no).</b></font><br>";
			} else {		  	
				echo "<font size=2 color=#ff0000><b>Entry $patctr: $tmp[0] -- $tmp[1] ($no).</b></font><br>";
			}
			if (7>strlen($tmp[1])) {
				$sevendigit = $sevendigit.$tmp[1]."<br>";
			}
			if ($duplicationcheck == "Y") {
				fputs($fpdup,"$tmp[0],$tmp[1]\n");
			}
	  		if ($no != 14) {
	  			$warning = $warning."<b>$patctr: $no</b><br>";
	  			$rejfld = "";
	  			for ($yy=0; $yy<$no; $yy++) {
	  				$kk = $yy + 1;
	  				$warning = $warning."<b>$kk: ".$str[$yy]."</b>-- ".$tmp[$yy]."<br>";
	  				$rejfld = "$rejfld$kk: $str[$yy] -- $tmp[$yy]\n";
	  			}
	  			$warning = $warning."<br>";
	  			//echo $warning;
	  			fputs($fprejfld,$rejfld."\n");
	  			$dumpctr++;
	 	 	} else {
	 	 		if ($fromentry<=$patctr && $duplicationcheck != "Y") {
	 	 			####check existence
				  	$sql = "SELECT id FROM library.dumppatent WHERE patent_no='$tmp[1]';";
				  	//echo "$sql<br>";
				  	$result = mysql_query($sql);
			  		include("err_msg.inc");
				  	list($id) = mysql_fetch_array($result);
				  	if ($id) {
				  		echo "Patent $tmp[1] exist. <font color=#ff0000>DB ID is $id.</font><br><br>";
				  	} else {
		  				if ($dataconv == "Y" && $duplicationcheck != "Y") {
		  					for ($j=0; $j<$no; $j++) {
			  					$tmp[$j] = trim($tmp[$j]);
							}
			  				##inventor
	  						$j = 4;
				  			$tmp[$j] = ereg_replace(": ", "@", $tmp[$j]);
				  			$tmp[$j] = ereg_replace(":", "@", $tmp[$j]);
				  			$tmp[$j] = ereg_replace("; ", "@", $tmp[$j]);
		  					$tmp[$j] = ereg_replace(";", "@", $tmp[$j]);
	  						$tmp[$j] = ereg_replace(" ET AL", "@ET AL", $tmp[$j]);
	  						$tmp[$j] = ereg_replace("ET AL", "@ET AL", $tmp[$j]);
			  				$tmp[$j] = ereg_replace("\.", "", $tmp[$j]);
			  				$tmp[$j] = ereg_replace("@@", "@", $tmp[$j]);
			  				$namerej = explode("@",$tmp[$j]);
			  				$rejinventor = "";
			  				if (count($namerej) == 1) {
			  					$namestr = ereg_replace(" ", "", $tmp[$j]);
			  					$emptyspace = strlen($tmp[$j]) - strlen($namestr);
			  					if ($emptyspace>2) {
	  								for ($yy=0; $yy<$no-1; $yy++) {
	  									$kk = $yy + 1;
	  									$rejinventor = "$rejinventor$tmp[$yy]*";
	  								}
	  								$rejinventor = $rejinventor.$tmp[$no-1];
	  								fputs($fpname,$rejinventor."\n\n");
	  								$invotherformat++;

				  					## name transfermation
				  					$namelist = explode(",",$tmp[4]);
				  					$newinventorstr = "";
				  					for ($ni=0; $ni<count($namelist); $ni++) {
			  							if ($namelist[$ni]) {
			  								$threenames = explode(" ", trim($namelist[$ni]));
			  								$invl = $threenames[0];
			  								$invf = $threenames[1];
			  								$invm = $threenames[2];
			  								if (count($threenames)>=3) {
			  									for ($mi=3; $mi<count($threenames); $mi++) {
			  										if (trim($threenames[$mi])) {
			  											$invm = $invm."_".$threenames[$mi];
			  										}
				  								}
				  							}
				  							//echo "$invl, $invf $invm<br>";
				  							if ($invl) {
				  								$newinventorstr = $newinventorstr.$invl.",";
				  							}
			  								if ($invf) {
			  									$newinventorstr = $newinventorstr.$invf;
			  								}
			  								if ($invm) {
			  									$newinventorstr = $newinventorstr." ".$invm;
			  								}
			  								$newinventorstr = $newinventorstr."@";
			  							}
			  						}
			  						//$namewarning = $namewarning."<br><font color=#ff0000>".$newinventorstr."</font><br>";
			  						$empty = "&nbsp;";
			  						$tmptmp = ereg_replace("@", "$empty", $newinventorstr);
			  						$tmptmp = ereg_replace(",", ", ", $tmptmp);
			  						$tmp5 = ereg_replace(",", ",--",$newinventorstr);
			  						$tmp5 = ereg_replace("@", "@--",$tmp5);
			  						/*
			  						echo "<br>PN: $tmp[1]. ($invotherformat) Inventor: <br>
			  							$tmp[4]<br><font color=#ff0000>".$tmptmp."</font><br>$tmp5<br><br>";
			  						//*/
			  						if (count($namelist) == 1) {
			  							$namerejctr++;
			  							$namewarning = $namewarning."<br>PN: $tmp[1]. Inventor: ".$tmp[$j];
			  						} else {
			  							$tmp[4] = $newinventorstr;
			  							$rejinventor = "";
			  						}
								}
			  				}

			  				$rejdate = "";
			  				##file_date and patent_date 
			  				if (strlen($tmp[5]) > 8 or strlen($tmp[6]) > 8) {
			  					$datewarning = $datewarning."<b>$patctr</b><br>";
	  							for ($yy=0; $yy<$no-1; $yy++) {
	  								$kk = $yy + 1;
	  								$rejdate = "$rejdate$str[$yy] -- $tmp[$yy]\n";
	  								$datewarning = $datewarning."$str[$yy]: $tmp[$yy]<br>";
	  							}
	  							$rejdate = $rejdate.$str[$no-1]." -- ".$tmp[$no-1];
	  							$datewarning = $datewarning.$str[$no-1].": ".$tmp[$no-1]."<br><br>";
	  							fputs($fpdate,$rejdate."\n\n");
			  					$daterejctr++;
			  				}

				  		//*
							if (!$rejinventor && !$rejdate) {
		  						##keyword or abstract 
 		 						$j = 10;
 								$strt = $tmp[$j];
		 						$abstract = "";
					 			$keywords = "";
					  			if (strtoupper($strt) == $strt) {
					  				$str[$j] = "keyword";
			  						$keywords = $tmp[$j];
								} else {
									$str[$j] = "abstract";
									$abstract = $tmp[$j];
			  						$noabstract++;
				  				}
			 					
			 					$tmp[2] = ereg_replace("'", "\'", $tmp[2]); 
			 					$tmp[3] = ereg_replace("'", "\'", $tmp[3]); 
			 					$tmp[4] = ereg_replace("'", "\'", $tmp[4]); 
			 					$tmp[11] = ereg_replace("'", "\'", $tmp[11]);
			 					$abstract = ereg_replace("'", "\'", $abstract);
			 					$keywords = ereg_replace("'", "\'", $keywords);
			 					
				  				$sql = "INSERT INTO library.dumppatent SET id='NULL', country='$tmp[0]', 
								patent_no='$tmp[1]', patent_title='$tmp[2]', assignee='$tmp[3]', 
								inventor='$tmp[4]', file_date='$tmp[5]', patent_date='$tmp[6]', 
								classification='$tmp[7]', keycode='$tmp[8]', pct='$tmp[9]', 
								keywords='$keywords', abstract='$abstract', citations='$tmp[11]', 
								abstract_only='$tmp[12]', electronic_copy='$tmp[13]';";
				  				echo "$sql<br>";
			  					$result = mysql_query($sql);
			  					include("err_msg.inc");	
			  					$id = mysql_insert_id();
				  				echo "New Patent $tmp[1]. <font color=#0000ff>DB ID is $id.</font><br><br>";
				  			}
				  	//*/
				  		}
					}
				}
		  	}
		  	}
			if ($patctr > $entrystart + $increno) {
				fclose($fpinput);
				
				fputs($fpname,"No rejected duo to inventors entry format: ".$invotherformat);

				fclose($fprejfld);
				fclose($fpname);
				fclose($fpdate);
	
				if ($duplicationcheck == "Y") {
					fclose($fpdup);
				}
				echo "<hr>";
				if ($dumpctr) {
					echo $warning;
					echo "<br><br><b>Number of patent rejected: $dumpctr</b>";
				}
				echo "<br><br><b>Number of Name Rejected: $namerejctr</b>";
				if ($namerejctr) {
					echo "<br><b>Name Rejected: $namewarning</b>";
				}
	
				if ($invotherformat) {
					echo "<br>No rejected duo to inventors entry format: ".$invotherformat;
				}
				
				echo "<br><br><b>Number of Date Rejected: $daterejctr</b>";
				if ($daterejctr) {
					echo "<br><b>Date Rejected: $datewarning</b>";
				}
				echo "<br><hr><br>";
				exit;
		  	}
  			$patctr++;
  			$entry = "";
  			flush();
		}
	}
	fclose($fpinput);
	fclose($fprejfld);
	if ($invotherformat) {
		fputs($fpname,"No rejected duo to inventors entry format: ".$invotherformat);
	}
	fclose($fpname);
	fclose($fpdate);
	
	if ($duplicationcheck == "Y") {
		fclose($fpdup);
	}
	echo "<hr>";
	if ($dumpctr) {
		echo $warning;
		echo "<br><br><b>Number of patent rejected: $dumpctr</b>";
	}
	echo "<br><br><b>Number of Name Rejected: $namerejctr</b>";
	if ($namerejctr) {
		echo "<br><b>Name Rejected: $namewarning</b>";
	}
	echo "<br><br><b>Number of Date Rejected: $daterejctr</b>";
	if ($daterejctr) {
		echo "<br><b>Date Rejected: $datewarning</b>";
	}
	
	if ($invotherformat) {
		echo "<br>No rejected duo to inventors entry format: ".$invotherformat;
	}

	/*
	if ($fourdigit) {
		echo "<br>These patent have less than 7 digits<br>$fourdigit";
	}
	echo "<br><br><b>Number of abstract: $noabstract</b>";
	//*/
}
echo "<hr><a href=#top>Back to top</a><br><br>";
?>
</body>
