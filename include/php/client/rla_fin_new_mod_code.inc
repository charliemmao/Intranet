<html>
<?php
if (!$code_id) {
	echo "<hr>";
}
echo "<form name=codeman>";
echo '<input type="hidden" name="frm_str" value= "'.$userstr.'">';
if ($code_id) {
	echo '<input type="hidden" name="code_id" value= "'.$code_id.'">';
	echo "<hr><h2>Modify following code</h2>";
} else {
	echo "<h2>Add new code</h2>";
}
echo "<table border=1>";
echo "<tr><th>Item</th><th>Value</th></tr>";
echo "<tr><th align=left>Description</th><td>
	<input type=text name=description value=\"$description\" size=50></td></tr>";
echo "<tr><th align=left>Code</th><td>
	<input type=text name=codes value=\"$codes\" size=10></td></tr>";
echo "<tr><th align=left>Category</th><td><select name=category>";
	$j=0;
	$cat[$j][0]="ac"; $cat[$j][1]="Account"; $j++;
	$cat[$j][0]="sa"; $cat[$j][1]="Subaccount"; $j++;
	$cat[$j][0]="cc"; $cat[$j][1]="Cost Center"; $j++;
	for ($i=0; $i<$j; $i++) {
		if ($cat[$i][0] == $category) {
			echo "<option selected value=\"".$cat[$i][0]."\">".$cat[$i][1];
		} else {
			echo "<option value=\"".$cat[$i][0]."\">".$cat[$i][1];
		}
	}
echo "</option></select></td></tr>";
echo "<tr><th align=left>Is Active?</th><td><select name=rlaactive>";
	$j=0;
	$cat[$j]="y"; $j++;
	$cat[$j]="n"; $j++;
	for ($i=0; $i<$j; $i++) {
		if ($cat[$i][0] == $rlaactive) {
			echo "<option selected>".$cat[$i];
		} else {
			echo "<option>".$cat[$i];
		}
	}
echo "</option></select></td></tr>";
echo "<tr><td colspan=2 align=middle>";
echo "<input type=submit name=codemanipulation ";
if ($code_id) {
	echo "value=\"Modify Code\">";
} else {
	echo "value=\"Add New Code\">";
}
echo "</td></tr></table>";
echo "</form>";
?>
</html>