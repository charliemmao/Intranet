<html>
<?php
		echo '<h1>Current project codes list</h1>';
		echo '<table border="1" width=100%>';
		echo '<tr>';
		for ($ilist=0; $ilist<count($itemlist); $ilist++) {
			echo '<th width='.$width[$ilist].'>'.$itemlist[$ilist].'</th>';
		}
		echo '</tr>';

		for ($i=0; $i<$codeno; $i++) {
				echo '<tr>';
				for ($j=0; $j<4; $j++) {
                  echo '<td>'.$codelist[$j][$i].'</td>';
				}
				echo '</tr>';
		}
		echo '</table>';
?>
</html>
