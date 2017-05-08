<html>
<?php
$basestr	=	 '&dbname='.$dbname.'&tablename='.$tablename
				.'&fldname='.$fldname.'&fldvalue='.$fldvalue;
				.'&email_name='.$email_name.'&logon_name='.$logon_name
				.'&title='.$title.'&first_name='.$first_name
				.'&middle_name='.$middle_name.'&last_name='.$last_name;
				.'&myid='.$myid.'&myrootid='.$myrootid
$frm_str =   'rcdaction='.$rcdaction.$basestr;
//echo $frm_str.'<br>';   
 
$frm_str	=	base64_encode($frm_str);
echo '<input type="hidden" name="frm_str" size="20" value= "'.$frm_str.'">';
?>

</html>