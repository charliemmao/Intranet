<html>
<?php
	echo 'The rules for filling data outlined below should be followed:<br>';
	echo '
<ul>
  <li>Field with &quot;<b><font color="#FF0000">RED</font></b>&quot;&nbsp; face
    has to be filled;</li>
		   <li>Field with &quot;<b><font color="#0000FF">BLUE</font></b>&quot;
            face has a &quot;<b><font color="#0000FF">DATE</font></b>&quot; type,
            <br>has to be in the format of &quot;<font color="#0000FF"><b>YYYY-MM-DD</b></font>&quot;,
            <br>for example today is entered as&nbsp;<b><font color="#0000FF">'.date('Y-m-d').'</b></font>.';
     echo '</li><br><br>';
     echo '</ul>';
?>
</html>