<html>

<?php
	
	echo '<tr><td colspan=2><hr></td></tr>';
	echo "<input type=hidden name=pubtravel value=$pubtravel>";
	
//if ($priv == "00"){
	include("connet_other_once.inc");
	$sql = "SELECT confirmation FROM timesheet.tsmailconf WHERE email_name='$email_name';";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($confirmation) = mysql_fetch_array($result);
	echo "<tr><th align=left colspan=2>Do you want an electronic receipt ".
		"for this timesheet?  ";
	echo "<select name=confirmation>";
	if ($confirmation == "Y") {
		echo "<option selected>Y";
		echo "<option>N";
	} else {
		echo "<option selected>N";
		echo "<option>Y";
	}
	echo "</option></select></td></tr>";
//}
	
	if ($priv == "1000") {
		echo '<tr><td rowspan=2 align="center">';
		echo '<input onClick="return (keypress());"';
	} else {
		echo '<tr><td rowspan=1 align="center"><input ';
		if ($priv == "1000") {
			echo 'onClick="return (tsconfirm());" ';
		} else {
			echo 'onClick="return (tsconfirm());" ';
		}
	}
	echo 'type="submit" value="Submit" name="sendts"></td>';
	
	echo "<td>"
		."<div ID=\"obtochange\"><b>Time on this sheet is $sumtimedb.</b></div>"
		."</td></tr>";
	
	if ($priv == "1000") {
		echo '<tr><td>';
		echo '<div ID="send" onClick="ChangeYesNo();">'
		.'<b>Do you want to submit? <font color=#ff0000>NO.</font></b></div>';
		echo '</td></tr>';								//NO-YES
	}

	echo '<tr><td colspan=2><hr></td></tr>';
	echo '<tr><td colspan=2>&nbsp;</td></tr>';
	echo '</table></form>';
?>
</html>