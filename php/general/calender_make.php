<html>
<?php
echo '<form method="POST" action="">';
  	echo '<table border="1">';// width="50%"
  	$moffset	=	1;
	include('calender_from.inc');
	include('calender_to.inc');
	include('calender_friday.inc');
	include('calender_stamp.inc');
	echo '<tr><td colspan="2">
        <p align="center"><input type="submit" value="Submit" name="B1"></p>
      </td>';
	echo '<td colspan="2">
        <p align="center"><input type="reset" value="Reset" name="B2"></p>
      </td></tr>';
  	echo '</table>';
echo '</form>'
?>
</html>
