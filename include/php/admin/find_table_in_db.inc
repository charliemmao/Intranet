<html>
<?php
	include("connet_root_once.inc");
  	$resulttable = mysql_list_tables($usedb);
	$no = mysql_num_rows ($resulttable);
	$itable = 0;
	while ($itable < mysql_num_rows ($resulttable)) {
    		$tablelist[$itable] = mysql_tablename ($resulttable, $itable);
		$tabtmp = $tablelist[$itable];
		
		## find number of fields and records in table 
		$result = mysql_list_fields($usedb, $tabtmp);
		include("mysql_query_fld_list.inc");
		$keyexist[$itable] = $nokey;
		$tablefldnnum[$itable] = $nofields;
		
		## find number of records in table $nofields
		$fldtmp = $fldproplist[0][0] ;
		$tmpsql = "SELECT COUNT(*) as norcd FROM $usedb.$tabtmp";
		//echo "$tmpsql <br>";
		$result = mysql_query($tmpsql);
  		list($norcd) = mysql_fetch_array($result);
		$tablercdno[$itable] = $norcd;
    		$itable++;
    }
?>
</html>