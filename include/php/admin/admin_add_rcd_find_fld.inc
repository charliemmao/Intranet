<html>
<?php
	$result = mysql_list_fields("timesheet", $mantablename);
	$fields = mysql_num_fields($result);
	$i = 0;
	$disp = 0;
	if ($disp) {
		echo "<table border=1><tr><th>NO</th><th>Name</th><th>Type</th><th>Length</th><th>Flag</th></tr>";
	}
	while ($i < $fields) {
    	$name_array[$i]  = mysql_field_name($result, $i);
    	if ($mantablename == "employee") {
    		$name_array[$i] = $name_array[$i]."1";
    	}
    	$type[$i]  = mysql_field_type($result, $i);
    	$len[$i]   = mysql_field_len($result, $i);
    	$flags[$i] = mysql_field_flags($result, $i);
 		if ($len[$i]<=100) {
 			$obj[$i] = "text";
 		} else {
 			$obj[$i] = "textarea";
 		}
 		if ($type[$i] == "int" || $type[$i] == "int" || $type[$i] == "date" || $type[$i] == "int") {
 			$verify[$i] = $type[$i];
 		}
 		if (ereg("auto_increment",$flags[$i])) {
 			$obj[$i] = "hidden";
 			$val[$i] = "NULL";
 		}
	   	$j = $i + 1;
		if ($disp) {
    		echo "<tr><td>$j</td><td>$name_array[$i]</td><td>$type[$i]</td><td>$len[$i]</td><td>$flags[$i]</td></tr>";
    	}
    	$i++;
	}
	if ($disp) {
		echo "</table><p>";
	}
?>
</html>