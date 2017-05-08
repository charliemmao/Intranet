<html>
<?php
	$jfld=0;
	for ($ifld=0; $ifld<$nofields; $ifld++) {
		$tmp = "ckfldlist$ifld";
		if ($$tmp) {
			$showfld[$jfld] = $$tmp;
			//echo $jfld."---".$showfld[$jfld]."<br>";
			$jfld++;
		}
		$tmp = "ckeditlist$ifld";
		$editfld[$ifld] = $$tmp;
	}

	$jfld=0;
	for ($ifld=0; $ifld<3; $ifld++) {
		$tmp = "rdofldsort$ifld";
		if ($$tmp) {
			$new = 1;
			for ($kfld=0; $kfld<$ifld; $kfld++) {
				if ($fldorderby[$kfld] == $$tmp) {
					$new = 0;
				}
			}
			if ($new == 1) {
				$fldorderby[$jfld] = $$tmp;
				//echo $jfld."---".$fldorderby[$jfld]."<br>";
				$jfld++;
			}
		}
	}	
	//ckfldlist$i	rdofldsort0	rdofldsort1	rdofldsort2 

	$fldselect = $showfld[0];
	for ($ifld=1; $ifld<count($showfld); $ifld++) {
		$fldselect .= ", ".$showfld[$ifld];
	}

	$editfldno = 0;
	for ($ifld=0; $ifld<count($editfld); $ifld++) {
		if ($ifld == 0) {
			$fldeditconst = $editfld[$ifld];
		} else {
			$fldeditconst .= "@".$editfld[$ifld];
		}
		if ($editfld[$ifld]) {
			$editfldno++;
		}
	}
	if ($fldeditconst) {
		//echo "fldeditconst = $fldeditconst <br>";
	}
	if ($fldorderby[0]) {
		$orderby = $fldorderby[0];
	}
	for ($ifld=1; $ifld<count($fldorderby); $ifld++) {
		$orderby .= ", ".$fldorderby[$ifld];
		//$orderby .= " and ".$fldorderby[$ifld];
	}

?>
</html>