<html>

<head>
<LINK REL="StyleSheet" HREF="../style/style.css" TYPE="text/css">
<LINK REL="StyleSheet" HREF="file:///F:/rla_intranet/css/style_manual.css" TYPE="text/css">
<title></title>
<title>Project Budget</title>
</head>

<body bgcolor="#C0C0C0" text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="/images/rlaemb.JPG">
<?php
//system("./_mail.pl \"$from\" \"$to\" \"$cc\" \"$subject\" \"$msg\"",$var);
//to fix Return-Path problem for PHP mail function.
	$from = "\@rla.com.au";
	$to = "\@rla.com.au";
	$cc = "";
	//$subject = "";
	$msg = "$";
	system("./_mail.pl \"$from\" \"$to\" \"$cc\" \"$subject\" \"$msg\"",$var);
//$var=0 mail successfully sent $var<>0 failed

include("phpdir.inc"); 
// $phpdir
//$phpdir/
//'.$phpdir.'/
//<?php echo $phpdir ?>
//$PHPconst/
asort ($projcodelist);
reset ($projcodelist);
while (list ($key, $val) = each ($projcodelist)) {
    echo "$key = $val<br>";
}
exit;

echo '
<ul>
  <li>w</li>
  <li><a href="tab_def_out.php" target="main">'.$phpdir.'/tab_def_out.php</a></li>
</ul>';
?>

<p>&nbsp;<font color="#FF0000">red</font></p>

<p>

<table cellSpacing="0" cellPadding="0" width="145" bgColor="#ffffff" border="0">
  <tbody>
   <tr>
      <td width="2" bgColor="#ffffff"><SPACER type="block" width="1" height="1"></td>
      <td bgColor="#959595" width="62"><SPACER type="block" width="1" height="1"></td>
      <td rowSpan="2" width="15"><img src="../images/tab_corner.gif" border="0" width="6" height="18"></td>
      
      <td bgColor="#959595" width="43"><SPACER type="block" width="1" height="1"></td>
      <td rowSpan="2" width="13"><img src="../images/tab_corner.gif" border="0" width="6" height="18"></td>      
    </tr>
    
    <tr>
     <td width="2" bgColor="#353535"><SPACER type="block" width="1" height="1"></td>
     <td onmouseover="this.style.backgroundColor='#CCCCCC'" style="BACKGROUND-COLOR: #eeeeee" onmouseout="this.style.backgroundColor='#EEEEEE'" align="middle" bgColor="#eeeeee" width="62"><a href="http://central.news.com.au/ninnbar/redirect_news.html"></a><a style="FONT-WEIGHT: bold; FONT-SIZE: 11px; COLOR: #000000; TEXT-DECORATION: none" href="http://central.news.com.au/ninnbar/redirect_news.html"><font face="arial,helvetica,sans-serif">&nbsp;News&nbsp;</font></a></td>
     <td onmouseover="this.style.backgroundColor='#CCCCCC'" style="BACKGROUND-COLOR: #eeeeee" onmouseout="this.style.backgroundColor='#EEEEEE'" align="middle" bgColor="#eeeeee" width="43"><a style="FONT-WEIGHT: bold; FONT-SIZE: 11px; COLOR: #000000; TEXT-DECORATION: none" href="http://central.news.com.au/ninnbar/redirect_money.html"><font face="arial,helvetica,sans-serif"><nobr>&nbsp;Money&nbsp;</nobr></font></a></td>
    </tr>
  </tbody>
</table>

<table border="1" width="100%" >
  <tr>
    <td width="20%" bgcolor="#FF0000">&nbsp;</td>
    <td width="20%">&nbsp;</td>
    <td width="20%">&nbsp;</td>
    <td width="20%">&nbsp;</td>
    <td width="20%">&nbsp;</td>
  </tr>
</table>
<p><font size="1" color="#008000"><b>blue</b></font></p>


<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td width="13%">1</td>
    <td width="37%">2</td>
  </tr>
</table>
<table border="1" width="100%">
  <tr>
    <td width="25%" valign="top">1</td>
    <td width="25%">2</td>
  </tr>
</table>
<form method="POST" action="">

  <p><textarea rows="2" name="S1" cols="20" tabindex="35">tt</textarea><input type="checkbox" name="ckname" value="ckvalue" checked>&nbsp;&nbsp;&nbsp;
  <input type="checkbox" name="ckname1" value="ckvalue">&nbsp;&nbsp;&nbsp; <select size="1" name="D1" multiple>
    <option selected value="ddd">Charlie MAO</option>
    <option value="ttt">ggggg</option>
  </select>&nbsp;&nbsp;&nbsp; <input type="text" name="T1" size="20" value="tt" tabindex="45">
  &nbsp;&nbsp;&nbsp;<select size="1" name="D2[]" multiple>
     <option >multiple</option>
 </select>
  <input type="password" name="T2" size="20">password
  </p>

  <p><input type="submit" value="Submit" name="B1" tabindex="100">
  <input type="reset" value="Reset" name="B2">&nbsp;&nbsp;&nbsp;
  <input type="radio" value="V1" checked name="R1">&nbsp;&nbsp;&nbsp; 
  <input type="radio" name="R1" value="V2"></p>
</form>

<p><a href="mailto:mail@rla.com.au">mail@rla.com.au</a></p>

<table border="1" width="2000">
  <tr>
    <td width="800" height="10">L1</td>
    <td width="1200" height="20">R1</td>
  </tr>
</table>

<table border="1" width="100%">
  <tr>
    <td width="25%" rowspan="3" valign="middle">345</td>
    <td width="25%">1</td>
    <td width="25%">2</td>
    <td width="25%">3</td>
  </tr>
  <tr>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
  </tr>
  <tr>
    <td width="20">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
  </tr>
</table>

<table border="1" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50%">&nbsp;</td>
    <td width="50%" rowspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>

<p>&nbsp;</p>

<table border="1" width="200" height="100" bgcolor="#FFFFCC">
  <tr>
    <td width="25%">
      <table border="1" width="100%" height="20">
        <tr>
          <td width="100%"><b>WWW</b></td>
        </tr>
        <tr>
          <td width="100%"><font size="1">WWW</font></td>
        </tr>
        <tr>
          <td width="100%"><font size="1">WWW</font></td>
        </tr>
        <tr>
          <td width="100%"><font size="1">WWW</font></td>
        </tr>
        <tr>
          <td width="100%"><font size="1">WWW</font></td>
        </tr>
      </table>
    </td>
    <td width="25%">
      <table border="1" width="100%" height="15">
        <tr>
          <td width="100%"><b>WWW</b></td>
        </tr>
        <tr>
          <td width="100%"><font size="1">WWW</font></td>
        </tr>
        <tr>
          <td width="100%"><font size="1">WWW</font></td>
        </tr>
        <tr>
          <td width="100%"><font size="1">WWW</font></td>
        </tr>
        <tr>
          <td width="100%"><font size="1">WWW</font></td>
        </tr>
      </table>
    </td>
    <td width="25%" bgcolor="#008000">&nbsp;</td>
    <td width="25%">&nbsp;</td>
  </tr>
</table>
.='<form><button type="submit" onClick="window.close();true;"><b>Close this window</b></button> 
</form>'
<--
<?php
########################################
##		Access control
########################################
include('str_decode_parse.inc');

include("userinfo.inc");
$qry	=	"?".base64_encode($userinfo);
include("find_domain.inc");	
echo '<h2 align=center>Project Budget</h2>';// onMouseOver=\"window.status='This is a test.'; return true;\"
$statuscontext = "Refresh";
include("self_status.inc");
echo "<a id=top><p align=center><a href=\"".$PHP_SELF."$qry\" $status>[Refresh]</a><hr>";
include('connet_root_once.inc');

$statuscontext = "Back To Top";
include("self_status.inc");
echo "<hr><a href=#top $status>Back to top</a><br>";
?>
<p>
-->
</body>

</html>

