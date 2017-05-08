<html>
<?php
	$i="";
	$fldname="fldname".$i;
	$datatype="datatype".$i;
	$typewidth="typewidth".$i;
	$typeop="typeop".$i;
	$nullyn="nullyn".$i;
	$key="key".$i;
	$defaultv="defaultv".$i;
?> <b><font face="Courier New">Column or Key Name </font></b><font face="Courier New"><input name=<?php echo $fldname ?> 
 size="20"><br>
	
	<b>Data Type&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; </b><select name=<?php echo $datatype?>>
		<?php include("fldtype.inc") ?>
   </select><br>
   
	<b>Data Width&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; </b><input name=<?php echo $typewidth?>  
 size="13"><br>
           
	<b>Data Options&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </b><select name=<?php echo $typeop?>>
        <option>
        <option>NONE
        <option>UNSIGNED
        <option>AUTO_INCREMENT
        <option>ZEROFILL
   </select><br>
           
<b>Allow Null?</b><b>&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </b><select name=<?php echo $nullyn?>>
        <option>NOT NULL
        <option>NULL
        <option>
   </select><br>

	<b>Key Definition &nbsp;&nbsp;&nbsp; </b><select name=<?php echo $key?>>
		 <option>
        <option>PRIMARY KEY
        <option>INDEX
        <option>UNIQUE INDEX
   </select><br>
	      
<b>Default Value&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </b><input name=<?php echo $defaultv?>  
 size="15">      

</font>
</html>