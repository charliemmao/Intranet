<html>
<?php
	/* table: code_prefix. columns: codehead_id, code_prefix, */
	$sql = "SELECT codehead_id as id, code_prefix as name, codelable FROM timesheet.code_prefix ORDER BY name;";
	include('connet_root_once.inc');
	$result = mysql_query($sql,$contid);
	include("err_msg.inc");
	$no_prefix = 0;
	if ($result) {
		while (list($id, $name, $codelable) = mysql_fetch_array($result)) {
			$codeprefix_list[$no_prefix][0]	=	strlen($name);
			$codeprefix_list[$no_prefix][1]	=	$name;	//ts_codeprefix_list.inc $no_prefix
			$codeprefix_list[$no_prefix][2]	=	$codelable;	//ts_codeprefix_list.inc $no_prefix
			//echo $no_prefix.": ".$codeprefix_list[$no_prefix][0]."-".$codeprefix_list[$no_prefix][1]."<br>";
			$no_prefix++;
		}
	}
	
?>
</html>