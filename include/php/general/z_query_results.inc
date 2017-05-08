<html>
<?php
$fields = mysql_num_fields($result);
$rows   = mysql_num_rows($result);
$table  = mysql_field_table($result, $i);
echo '<b>Table <font color="#0000FF">'.$table.'</font> has ';
echo '<font color="#0000FF">'.$fields.'</font> fields.<br><br></b>';

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
        printf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>\n",
            $name,$type,$len,$flags);
        $i++;
    }
    echo '</table><br>';
}

###############################################
#	display records 
###############################################
if ($rows) {
    echo '<table border="1">';
    echo '<tr>';
    $i=0;
    while ($i < $fields) {
        $name  = mysql_field_name  ($result, $i);
        printf('<td align="center"><b>%s</b></td>',$name);
        $i++;
    }
    echo '</tr>';

    while ($myrow = mysql_fetch_array($result)) {
        echo '<tr>';
        $i=0;
        $i=(int)1.5;
        while (list($key,$val) = each($myrow)) {
            $i +=1;
            $j = 2*(int)($i/2);
            $k = $k+1;
            if ($i == $j) {
                printf('<td>%s</td>',"$val");

            }
        }
        echo '</tr>';
    }
    echo '</table><br>';
}
?>
</html>