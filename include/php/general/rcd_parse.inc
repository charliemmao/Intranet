<html>
<?php
$fields = mysql_num_fields($result);
$i = 0;
while ($i < $fields) {
    $name  = mysql_field_name  ($result, $i);
    //$type  = mysql_field_type  ($result, $i);
    //$len   = mysql_field_len   ($result, $i);
    //$flags = mysql_field_flags ($result, $i);
    $fld_name[$i]   =   $name;
    $i++;
}

while ($myrow = mysql_fetch_array($result)) {
    $m=(int)1.5;
    $k=0;
    while (list($key,$val) = each($myrow)) {
        $m +=1;
        $j = 2*(int)($m/2);
        if ($m == $j) {
                $fld_value[$k]   =   $val;
                //echo "No ".$k.' '.$fld_name[$k].' = "'.$fld_value[$k].'"<br>';
                $k = $k+1;
        }
    }
}
/* echo "<h3>No           fld_name      fld_value</h3><br>";
for ($i=0; $i<$k; $i +=1)
echo '$'.$fld_name[$i].' =  $fld_value['.$i.'];  //='.$fld_value[$i].'<br>';
//*/
?>
</html>