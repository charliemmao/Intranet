<html>
<?php
	$novalonform	= count($HTTP_POST_VARS)-$offset;
	$frmj	= 0;
	while (list($key, $val) = each($HTTP_POST_VARS)) {
		$val	=	trim($val);
		if ($frmj <= $novalonform) {
			$frmj1	=	$frmj+1;
			//echo $frmj1.'  '.$key.' = '.$val.'<br>';
			if ($frmj <$novalonform) {
				$frm_key_val_list	=	$frm_key_val_list.$key."='".$val."',";
			} else {
				$frm_key_val_list	=	$frm_key_val_list.$key."='".$val."'";
			}
		}
		$frmj++;
	}
	//include('str_decode_parse.inc');
?>
</html>
