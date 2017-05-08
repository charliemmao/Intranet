<html>
<?php
	echo '<h2>Please enter your data following the rules for table <font color="#0000FF">'
	.$tablename.'</font>:</h2><br>';
echo '<ul>
  <li>Field with "<b><font color="#FF0000">RED</font></b>"&nbsp; face
    has to be filled;</li>
		   <li>Field with "<b><font color="#0000FF">BLUE</font></b>"
            face has a &quot;<b><font color="#0000FF">DATE</font></b>&quot; type,
            <br>has to be in the format of "<font color="#0000FF"><b>YYYY-MM-DD</b></font>",
            <br>for example today is entered as&nbsp;<b><font color="#0000FF">'.date('Y-m-d').'</b></font>.';
     echo '</li><br><br>';
     echo '</ul>';
?>
</html>