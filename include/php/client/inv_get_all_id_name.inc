<html>
<?php
	$msg = '';
##	get catname	

	if ($catid1 == 0) {
		// check whether it is really new entry
		$new = "y";
		$catname = trim($catname);
		if ($catname == "") {
			exit;
		}
		$temp = strtoupper($catname);
		$j = count($catlist);
		for ($i=1; $i<=$j; $i++) {
			if ($temp == strtoupper(trim($catlist[$i]))) {
				$new = "n";
				$catid1	=	$i;
				$catname = $catlist[$catid1];
				$msg = $msg."This category exists as: $catname<Br>";
				$msg = $msg."This category ID exists as: $catid1<Br>";
				break;
			}
		} 
		if ($new == "y") {
			// make new entry to category table
			$sql	=	"INSERT INTO inventory.category VALUES('NULL', '$catname');";
			newinvid($sql, &$catid1, "catid", "category");
			$msg = $msg."New category name: $catname<Br>";
			$msg = $msg."New category ID: $catid1<Br>";
			$catlist[$catid1] = $catname;
		}
	} else {
		$catname = $catlist[$catid1];
	}

##	get brandname	
	if ($brandid1 == 0) {
		// check whether it is really new entry
		$new = "y";
		$brandname = trim($brandname);
		/*
		if ($brandname == "") {
			exit;
		}
		*/
		$temp = strtoupper($brandname);
		$j = count($brandlist);
		for ($i=1; $i<=$j; $i++) {
			if ($temp == strtoupper(trim($brandlist[$i]))) {
				$new = "n";
				$brandid1	=	$i;
				$brandname = $brandlist[$brandid1];
				$msg = $msg."This brand exists as: $brandname<Br>";
				$msg = $msg."This brand ID exists as: $brandid1<Br>";
				break;
			}
		} 
		if ($new == "y") {
			// make new entry to category table
			$sql	=	"INSERT INTO inventory.brandname VALUES('NULL', '$brandname');";
			newinvid($sql, &$brandid1, "brandid", "brandname");
			$msg = $msg."New brand name: $brandname<Br>";
			$msg = $msg."New brand ID: $brandid1<Br>";
			$brandlist[$brandid1] = $brandname;
		}
	} else {
		$brandname = $brandlist[$brandid1];
	}

##	get manfname	
	if ($manid1 == 0) {
		// check whether it is really new entry
		$new = "y";
		$manfname = trim($manfname);
		if ($manfname == "") {
			exit;
		}
		$temp = strtoupper($manfname);
		$j = count($manflist);
		for ($i=1; $i<=$j; $i++) {
			if ($temp == strtoupper(trim($manflist[$i]))) {
				$new = "n";
				$manid1	=	$i;
				$manfname = $manflist[$manid1];
				$msg = $msg."This manufacture exists as: $manfname<Br>";
				$msg = $msg."This manufacture ID exists as: $manid1<Br>";
				break;
			}
		} 
		if ($new == "y") {
			// make new entry to category table
			$sql	=	"INSERT INTO inventory.manufacture VALUES('NULL', '$manfname');";
			newinvid($sql, &$manid1, "manid", "manufacture");
			$manflist[$manid1] = $manfname;
			$msg = $msg."New manufacture name: $manfname<Br>";
			$msg = $msg."New manufacture ID: $manid1<Br>";
		}
	} else {
		$manfname = $manflist[$manid1];
	}
	//echo  $msg;
?>
</html>