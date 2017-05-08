<html>
<?php
	$proddesc = trim($proddesc);
	$proddesclength = strlen("$proddesc");
	//echo $proddesc;
	if ($ghr) {
		$linelen = 30;
	} else {
		$linelen = 40;
	}
	if ($linelen < $proddesclength ) {
		$extraline++;
		for ($pi = $linelen; $pi<$proddesclength; $pi++) {
			$isspace= substr($proddesc, $pi, 1);
			if ($isspace == " ") {
				$newstr1 = substr($proddesc, 0, $pi);
				$newstr2 = substr($proddesc, $pi+1, $proddesclength-$pi-1);
				$proddesc = $newstr1."<br>".$newstr2;
				//echo "$proddesclength: $newstr1-------$newstr2<br>";
				break;
			}
		}
	}	
?>
</html>