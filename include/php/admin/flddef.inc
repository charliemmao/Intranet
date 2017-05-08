<!--<table border="0" width="100%" cellspacing="1" cellpadding="0"> 
				$fldname="fldname".$i;
				$datatype="datatype".$i;
				$typewidth="typewidth".$i;
				$typeop="typeop".$i;
				$nullyn="nullyn".$i;
				$key="key".$i;
				$defaultv="defaultv".$i;
-->
<tr>
	<td width=<?php echo $colw[1] ?>><input name=<?php echo $fldname ?> size="15"></td>
	
	<td width=<?php echo $colw[2] ?>><select name=<?php echo $datatype?>>
		<?php include("fldtype.inc") ?>
   </select>
	</td>

	<td width=<?php echo $colw[3] ?>><input name=<?php echo $typewidth?> size="5"></td>
           
	<td width=<?php echo $colw[5] ?>><select name=<?php echo $nullyn?>>
        <option>NOT NULL
        <option>NULL
        </select>
	</td>
	
	<td width=<?php echo $colw[6] ?>><select name=<?php echo $key?>>
		 <option>
        <option>PRIMARY KEY
        <option>INDEX
        <option>UNIQUE INDEX
        </select>
	</td>
	      
	<!--<td width=<?php echo $colw[6] ?>><input name=<?php echo $key?> size="8"></td>  -->    

	<td width=<?php echo $colw[7] ?>><input name=<?php echo $defaultv?> size="8"></td>      
            
	<td width=<?php echo $colw[4] ?>><select name=<?php echo $typeop?>>
        <option>
        <option>NONE
        <option>UNSIGNED
        <option>AUTO_INCREMENT
        <option>ZEROFILL
        </select>
	</td>
</tr>
