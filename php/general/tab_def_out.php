<html>
<body text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="rlaemb.JPG">
<?php
include("phpdir.inc"); 
$h="h3";
echo "<a id=top><$h align=center></a><a id=tm>Query String for $dbname.$tablename</a>";
echo "<a href=\"".$PHP_SELF."?".getenv('QUERY_STRING')."\"><font size=2>[Refresh]";
$t =explode("&file", getenv('QUERY_STRING'));
echo "</a><a href=\"$file?tabaction=editfield&".$t[0]."\"><font size=2>[Back]";
echo "</font></a></$h>";
	/*
	echo $dbname.'<br>';
	echo $tablename.'<br>';
	echo $action.'<br>';
	include("url_vars.inc");
	$result = mysql_query("SELECT * FROM $tablename");
	mysql_select_db($dbname);
	*/
	
  	include("connet_root.inc");
	$result = mysql_list_fields($dbname, $tablename);
	$fields = mysql_num_fields($result);
	$rows   = mysql_num_rows($result);
	$table  = mysql_field_table($result, $i);
    
if ($action=='tabdef') {
	echo '<b>Table <font color="#0000FF">'.$table.'</font> has ';
	echo '<font color="#0000FF">'.$fields.'</font> fields.<br><br></b>';
	$str_w	=	'CREATE TABLE '.$tablename.' (';
	//$str_w	=	'CREATE TABLE '.$dbname.'.'.$tablename.' (';
	/*
    $sql = "CREATE TABLE $tablename (";
    $sql = $sql."logname    VARCHAR(20)             DEFAULT ''              NOT NULL,";
    $sql = $sql."PRIMARY KEY(logname));";
	*/

    if ($fields) {
        echo '<table border="1">';
        echo '<tr>';
            echo '<td align="center"><p align="center"><b>Name</b></p></td>';
            echo '<td align="center"><b>Type</b></td>';
            echo '<td align="center"><b>Length</b></td>';
            echo '<td align="center"><b>Flags</b></td>';
        echo '</tr>';
                
        $i = 0;
        while ($i < $fields) {
            $name  = mysql_field_name  ($result, $i);
            $type  = mysql_field_type  ($result, $i);
            $len   = mysql_field_len   ($result, $i);
            $flags = mysql_field_flags ($result, $i);
            
            $str_w = $str_w.$name.' ';
            if ($len <= 4 && strtolower($type) == 'string') {
            	$str_w = $str_w.'CHAR('.$len.') ';
            } elseif ($len > 4 && strtolower($type) == 'string') {
            	$str_w = $str_w.'VARCHAR('.$len.') ';
            } else {
            	$str_w = $str_w.$type.' ';
            }
            if (strtolower($flags) == "not_null") {
            	$flags	=	"NOT NULL";
            } elseif (strtolower($flags) == "not_null primary_key") {
            	$flags	=	"NOT NULL PRIMARY KEY";
            /*} elseif(strtolower($flags) == "not_null") {
            	$flags	=	"NOT NULL";
            } elseif(strtolower($flags) == "not_null") {
            	$flags	=	"NOT NULL";
            */
            }
            
            if ($i < $fields-1){
            	$str_w = $str_w.$flags.",";
            } else {
            	$str_w = $str_w.$flags.");";
            }
            printf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>\n",
                $name,$type,$len,$flags);
            $i++;
        }
		echo '</table><br>';
		$basefile = 'define_'.$tablename.'_in_'.$dbname.'.html';
		$newfile	=	"/tmp/".$basefile;
		echo $str_w;
		$fp	=	fopen($newfile,'w+');
		if (!$fp) {
			echo 'Problem to open file '.$newfile.'<br>';
			exit;		
		}
		fputs($fp,$str_w);
		fclose($fp);
		
		$newfile	=	"/tmp/tab_def.txt";
		$fp	=	fopen($newfile,'w+');
		if (!$fp) {
			echo 'Problem to open file '.$newfile.'<br>';
			exit;		
		}
		fputs($fp,$str_w);
		fclose($fp);
    }
} elseif ($action=='rcd_lable') {
		$basefile = 'lable_'.$tablename.'_in_'.$dbname.'.inc';
		include("find_admin_ip.inc");
		$newfile	=	"/home/$adminname/rla/include/$phpdir/".$basefile;
		//echo $newfile;
		$fp	=	fopen($newfile,'w+');
		if (!$fp) {
			echo 'Problem to open file '.$newfile.'<br>';
			exit;		
		}
		fputs($fp,'<?php');
		fputs($fp,$newfile);
		fputs($fp,'?>');
		fclose($fp);

		//rewind($fp);
		$fp	=	fopen($newfile,'r+');
		if (!$fp) {
			echo 'Problem to open file '.$newfile.'<br>';
			exit;		
		}
		while ($buffer = fgets($fp,1024)) {	//!feof($fp)
			echo $buffer;
		}

		fclose($fp);
		echo '<h2>Field list for '.strtoupper($tablename).' in DB '.strtoupper($dbname).'</h2>';
		echo $str;
} elseif ($action=='fldlist') {
		$br = ";\n\n";
		$p= ";\n";
		$n="\n";
		$sp="&nbsp;&nbsp;&nbsp;&nbsp;";
		$ret = "$n$sp$sp";
		$ret2 = "$n$sp$sp$sp";
		$str_w=$n.'<html';
		$str_w= $str_w.'>'.$n;
		$str= $str_w;
		$insertstr1 = "$"."sql = \"INSERT INTO $dbname.$tablename$ret"."VALUES(";
		$insertstr2 = "";//"\".\"";
		$strcomma0	=	$sp.$sp.'/* table: '.$tablename.'. columns: ';
		$strcomma = "";
		$selas = "";
		$passstr = "$"."querystring = \"";
		$comatmp = "";
		
		$onetablehd = "\n<table border=1><tr>";
		$onetablerow = "<tr>";
       $i = 0;
       $strbyline = "<br>";
       $strtab = "\n<table>";
       while ($i < $fields) {
           $name  = mysql_field_name  ($result, $i);
           if ($tablename == "employee") {
           	//$name = $name."1";
           }
           $str_w = $str_w.$name.'\n\n';
           $str = $sp.$str.$name.$n;
           $strtab .= $ret2.	"<tr><td><b>".strtoupper($name)."</b></td><td>\$$name</td></tr>";	
           $onetablehd .= "<th>".strtoupper($name)."</th>";
           $onetablerow .= "<td>\$$name</td>";

           //$strtab .= $ret2.	"<tr><td><b>$name</b></td><td>\$$name</td></tr>";	
           if ($i>0 && (int)($i/4)*4 == $i) {
	          $insertstr1 = $insertstr1.$ret2."'$".$name."', ";
           	$strcomma	=	$strcomma.$ret2."$".$name.', ';
           	$comatmp	=	$comatmp.$ret2.$name.', ';
           	$strbyline .= $ret2."$name=\$$name<br>";
           } else {
	          $insertstr1 = $insertstr1."'$".$name."', ";
           	$strcomma	=	$strcomma."$".$name.', ';
           	$comatmp	=	$comatmp.$name.', ';           	
           	$strbyline .= "$name=\$$name<br>";    	
           }
           if ($i>0 && (int)($i/2)*2 == $i) {
           	$insertstr2 = $insertstr2.$ret2.$name."='$".$name."', ";
           } else {
           	$insertstr2 = $insertstr2.$name."='$".$name."', ";
           }
           $selas = $selas."t.$name as $name, ";
           $passstr = $passstr."&$name=$".$name;
           $i +=1;
       }
       $onetablehd .= "</tr>";
       $onetablerow .= "</tr>";
       $strtab .= "</table><p>\n";
       /*
       $str_e='<br></ht';
       $str_e= $str_e.'ml>';
       $str_w= $str_w.$str_e;
       $str= $str.$str_e;
       
		$basefile = 'fldlist_of_'.$tablename.'_in_'.$dbname.'.html';
		$newfile	=	"/tmp/".$basefile;
		$fp	=	fopen($newfile,'w+');
		if (!$fp) {
			echo 'Problem to open file '.$newfile.'<br>';
			exit;		
		}

		fputs($fp,$str);
		fclose($fp);

		$basefile = 'fieldlist_of_'.$tablename.'_in_'.$dbname.'.txt';
		$newfile	=	"/tmp/fieldlist.txt";
		$fp	=	fopen($newfile,'w+');
		if (!$fp) {
			echo 'Problem to open file '.$newfile.'<br>';
			exit;		
		}
		fputs($fp,$str);
		fclose($fp);
		//*/
		
		//echo '<h2>List for '.strtoupper($tablename).' in DB '.strtoupper($dbname).'</h2>';
		$msg = "";'Field list for '.strtoupper($tablename).' in DB '.strtoupper($dbname).$p;
		$msg = $msg.$strcomma0.$comatmp.' */'."\n\n";
		$msg = $msg.$sp.'include("connet_root_once.inc")'.$p;
		$msg = $msg.$sp.'include("connet_other_once.inc")'.$br;
		$strcomma = substr($strcomma, 0, strlen($strcomma)-2);	
		
		$insertstr1 = substr($insertstr1, 0, strlen($insertstr1)-2);
		$msg = $msg.$sp.$insertstr1.');"'.$p;
		$insertstr2 = substr($insertstr2, 0, strlen($insertstr2)-2);
		$msg = $msg.$sp."$"."sql = \"INSERT INTO $dbname.$tablename $ret"."SET $insertstr2;\"$p";
		$msg = $msg.$sp."$"."sql = \"UPDATE $dbname.$tablename $ret"."SET $insertstr2 $ret"."WHERE LIMIT #;\"$p";
		$insertstr2 = ereg_replace(",", " and", $insertstr2);
		$msg = $msg.$sp."$"."sql = \"DELETE FROM $dbname.$tablename $ret"."WHERE $insertstr2;\"$p";
		$msg = $msg.$sp."$"."sql = \"SELECT * FROM $dbname.$tablename $ret"."WHERE $insertstr2 ORDER BY DESC;\"$p";
		$comatmp = substr($comatmp, 0, strlen($comatmp)-2);
		$msg = $msg.$sp."$"."sql = \"SELECT $comatmp $ret"."FROM $dbname.$tablename $ret"."WHERE $insertstr2 ORDER BY DESC;\"$br";

		$msg = $msg.$sp."$"."result = mysql_query($"."sql)$p";
		$msg = $msg.$sp.'include("err_msg.inc")'.$p;
		$msg = $msg.$sp.'$'.'no = mysql_num_rows($'.'result)'.$p;
		$msg .= $sp."echo \"".$onetablehd."\"" .$p;
		//$strcomma = ereg_replace(", ", ", $", $strcomma);
		$msg = $msg.$sp."list(".$strcomma.") = mysql_fetch_array($"."result)".$br;
		$msg = $msg.$sp."while (list(".$strcomma.
			") = mysql_fetch_array($"."result)) {".
			"\n$sp$sp"."echo \"".$onetablerow."\"$p".
			"$sp}$n".
			"\n$sp"."echo \"</table>\";$n".
			"\n$sp$sp"."echo \"".$strbyline."\"$p".
			"\n$sp$sp"."echo \"".$strcomma."\"$p".
			"\n$sp$sp"."echo \"".$strtab."\"$p";

		$msg = $msg.$sp."$"."filelog = __FI"."LE__$p";
		$msg = $msg.$sp."$"."linelog = __LI"."NE__$p";
		$msg .= $sp."\$fh = fopen(\"filename\", \"rw\")$p";
		$msg .= $sp."fput(\$fh, \"$strcomma\\n\")$p\n";

		$msg = $msg.$sp.substr($selas, 0, strlen($selas)-2).$p;
		$msg = $msg.$sp.$passstr.'"'.$p;
		$msg = $msg.$sp.$str;
		echo "<form><textarea cols=85 rows=25 bgcolor=\"#bbbbbb\">$msg</textarea></form>";
		//echo " FROM $dbname.$tablename WHERE ;<p>";
} elseif ($action=='readfrm') {
		$str_screen= "$"."rcdstr = ".'" SET ';
		$str = " SET ";
       $i = 0;
       while ($i < $fields) {
           $name  = mysql_field_name  ($result, $i);
           $str = $str.$name."='$".$name."',";
           if ($i<$fields-1) {
           	$str_screen = $str_screen.$name."='$".$name."',<br>";
           } else {
           	$str_screen = $str_screen.$name."='$".$name."'".'";<br>';
           }
           $i +=1;
       }
       
       /*
       echo $str_screen.'<br>';
		$basefile = 'rcdset_of_'.$tablename.'_in_'.$dbname.'.txt';
		$newfile	=	"/tmp/".$basefile;
		$fp	=	fopen($newfile,'w+');
		if (!$fp) {
			echo 'Problem to open file '.$newfile.'<br>';
			exit;		
		}
		fputs($fp,$str);
		fclose($fp);
		//*/
		echo '<h2>Section of a statement for INSERT or UPDATE record to '.strtoupper($tablename).' in DB '.strtoupper($dbname).'</h2>';
		echo $str;
}

?>