<html>
<?php
function number_verify($val,$formatstr,$field) {
	$msg = 0;
	if ($formatstr == "year") {
		if (strlen($val) == 2 && (($val>0 && $val<=99) || $val =="0" || $val == "00")) {
			$msg = 1;
		} elseif (strlen($val) == 4  && ($val>=1900 && $val<=date("Y"))) {
			$msg = 1;
		}
		if ($msg == 0) {
			echo "<h2><font color=#ff0000>\"$val\" is not valid number for field \"$field\". "
			."It should be 2 digits (00-99) or 4 digits (1900-".date("Y").").</font></h2>";
			exit;
		}
	} elseif ($formatstr == "int") {
		$msg = 1;
		for ($i=0; $i<strlen($val); $i++) {
			$tmp = substr($val, $i, 1);
			if (!ereg("([0-9]{1})", $tmp)) {
				$msg = 0;
				break;
			}
		}
		if ($msg == 0) {
			echo "<h2><font color=#ff0000>\"$val\" is not valid integer number required by field \"$field\".</font></h2>";
			exit;
		}
	}
}

function data_validation($var, $type) {
	if ($type == "empty") {
		if (empty($var)) {
			return true;
		} else {
			return false;
		}	
	} elseif ($type == "array") {
		if (is_array($var)) {
			return true;
		} else {
			return false;
		}
	} elseif ($type == "double") {
		if (is_double($var)) {
			return true;
		} else {
			return false;
		}
	} elseif ($type == "float") {
		if (is_float($var)) {
			return true;
		} else {
			return false;
		}
	} elseif ($type == "int") {
		//echo $var.": ".is_integer($var).".<br>";
		if (is_int($var)) {
			return true;
		} else {
			return false;
		}
	} elseif ($type == "long") {
		if (is_long($var)) {
			return true;
		} else {
			return false;
		}
	} elseif ($type == "object") {
		if (is_object($var)) {
			return true;
		} else {
			return false;
		}
	} elseif ($type == "real") {
		if (is_real($var)) {
			return true;
		} else {
			return false;
		}
	} elseif ($type == "string") {
		if (is_string($var)) {
			return true;
		} else {
			return false;
		}
	}
}

function back() {
	echo "<br><b>Please click \"Back\" on your BROWSER toolbar returning to previous page.</b>";
}
?>
</html>
