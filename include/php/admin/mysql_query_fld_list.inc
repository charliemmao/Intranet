<html>
<?php
	$nofields = mysql_num_fields($result);
	if (!$fldas) {
		$fieldsno = $nofields;
	}
	$i = 0;
	while ($i < $nofields) {
    		//echo "Information for column $i:<BR>\n";
    		$meta = mysql_fetch_field ($result);
    		if (!$meta) {
        		echo "No information available<BR>\n";
    		}
		if ($fldas) {
            	$fldproplistas[$i][0] = $meta->name;
		} else {
            	$fldproplist[$i][0] = $meta->name;
		}
            $fldproplist[$i][1] = $meta->type;
            $fldproplist[$i][2] = $meta->max_length;
            $fldproplist[$i][3] = $meta->primary_key;
            $fldproplist[$i][4] = $meta->unique_key;
            $fldproplist[$i][5] = $meta->multiple_key;
            $fldproplist[$i][6] = $meta->not_null;
            $fldproplist[$i][7] = $meta->zerofill;
            $fldproplist[$i][8] = $meta->numeric;
            $fldproplist[$i][9] = $meta->unsigned;
            $fldproplist[$i][10] = $meta->blob;
            $fldproplist[$i][11] = $meta->table;
/*
    		echo "<PRE>
		name:         $meta->name
		type:         $meta->type
		max_length:   $meta->max_length
		primary_key:  $meta->primary_key
		unique_key:   $meta->unique_key
		multiple_key: $meta->multiple_key
		not_null:     $meta->not_null
		zerofill:     $meta->zerofill
		numeric:      $meta->numeric
		unsigned:     $meta->unsigned
		blob:         $meta->blob
		table:        $meta->table
		</PRE>";
//*/
    		$i++;
	}
if (!$fldas) {
	$editdeletefldstr = "";
	$nokey = 1;
	//use primary_key
	$j=3;
	for ($i=0; $i < $nofields; $i++) {
		if ($fldproplist[$i][$j]) {
			$editdeletefldstr = $fldproplist[$i][0];
			$fldcolno = $i;
			break;
		}
	}
	//use unique_key
	if (!$editdeletefldstr) {
		$j=4;
		for ($i=0; $i < $nofields; $i++) {
			if ($fldproplist[$i][$j]) {
				$editdeletefldstr = $fldproplist[$i][0];
				$fldcolno = $i;
				break;
			}
		}
	}
	//echo "<br> <b>$editdeletefldstr</b> <br>";

	//if no primary/unique_key
	if (!$editdeletefldstr) {
		$nokey = 0;
	}
/*
if ($usedb && $usetable) {
	include("connet_root_once.inc");
	$result = mysql_query("SELECT * FROM $usedb.$usetable LIMIT 1");
	$i = 0;
	while ($i < mysql_num_fields ($result)) {
   		$type  = mysql_field_type  ($result, $i);
  		$name  = mysql_field_name  ($result, $i);
   		$len   = mysql_field_len   ($result, $i);
		$fldproplist[$i][2] = $len;
   		$flags = mysql_field_flags ($result, $i);
            $fldproplist[$i][12] = $flags;
   		//echo $type." ".$name." ".$fldproplist[$i][2]." ".$flags."<BR>";
   		$i++;
	}
}
//*/
}
?>
</html>