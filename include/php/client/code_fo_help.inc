<html>
<?php
//include('user_info.inc');
		echo '<h1>Current project codes definition</h1>';
		echo '<table border="1" width=100%>';
		$no_in_col 	=	6;
		echo '<tr>';
		$no_row	=	1;
		for ($i=0; $i<$codeno; $i++) {
			echo '<td ';
			echo 'onMouseOut="ClearFlyOver();"';
			echo 'onMouseOver="FlyOver('."'".$codelist[3][$i]."');".'">';
			echo $codelist[0][$i].'</td>';
			if ( ($i+1) == ($no_in_col * $no_row)) {
				echo '</tr><tr>';
				$no_row++;
			}
		}
		echo '</tr></table>';
?>
</html>
