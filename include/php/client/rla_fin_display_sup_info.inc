<html>
<?php
	echo "<h4>Supplier Info:</h4>";
	include("one_supplier_info.inc");
	$noitemordered = 0;
	for ($i=0; $i<$noitem; $i++) {
		$good1 	= "goods$i";
		$quan1		= "quantity$i";
		$uprice1 	= "unitprice$i";
		$charge1 	= "rlacharge$i";		
		if ($$good1 && $$quan1 && $$uprice1) {
			$item[$noitemordered] 		= $$good1;
			$noquan[$noitemordered]		= $$quan1;
			$priceunit[$noitemordered]	= $$uprice1;
			$charge[$noitemordered]		= $$charge1;
			if ($charge[$noitemordered] == "saa") {
				$charge[$noitemordered] = $charge[$noitemordered-1];
			}
			$charge1 = $charge[$noitemordered];
			$noitemordered++;
			//echo "goods=".$$good1."; quan=".$$quan1."; uprice=".$$uprice1."; charge=".$charge1."<br>";
		}
	}

?>
</html>
