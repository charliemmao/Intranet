<html>
<?php
	//$str_temp	$pos	$chr_move
	//echo "before ".$str_temp.'<br>';
	if ($pos == "$") {
		$pat	=	$chr_move.$pos;
	} elseif ($pos == "^") {
		$pat	=	$pos.$chr_move;
	} else {
		$pat	=	$chr_move;
	}
	
	$len	= strlen($str_temp);
	$no_to_move	= strlen($chr_move);
	
	if ( ereg($pat,$str_temp) ) {
			//echo $len.'<br><br>';
        if ($pos == "$") {
        	  $ll	=	$len-$no_to_move;
			  //echo $ll.'<br><br>';
            $str_temp	=   substr($str_temp, 0, $ll);
        } elseif ($pos == "^") {
            //$str_temp	=   substr($str_temp,$no_to_move+1,$len-$no_to_move);
        } else {
            //$str_temp	=   substr($str_temp,1,$len-$no_to_move);
        }
	}
	//echo "after ".$str_temp.'<br>';
?>
</html>
