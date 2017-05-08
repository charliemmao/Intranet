<html>
<?php
include("phpdir.inc");

#######################################################
#       find out client registration status
#######################################################
include('find_reg.inc');

#######################################################
#		for registered client
#######################################################
if ($email_name) {
	include("user_new_logon.inc");
	exit;

    $qrystr =   'rcdaction=logon'.$basestr;
    //echo $qrystr.'<br>';
    $qrystr =   '?'.base64_encode($qrystr);
    $privstr = "100";
	if ($priv != $privstr) {
		//echo "priv = $priv. In file \"".__FILE__."\" on line ".__LINE__.".<br>";
		if ($email_name == "webmaster") {
			//echo "priv = $priv. In file \"".__FILE__."\" on line ".__LINE__.".<br>";
    	}
		include("user_logon.inc");
	} else {
		echo '<h1 align="center">&nbsp;</h1>
		<h1 align="center">&nbsp;</h1>
		<h1 align="center">&nbsp;</h1>
		<h1 align="center"><b><font size="7">Welcome to Intranet.</font></b></h1> 
		<h1 align="center">&nbsp;</h1>';
    	echo '<h1 align="center"><a href="/'.$phpdir.'/user_logon.php'.$qrystr.'">Logon</a><br></h1>';
    	echo "<h1>&nbsp;</h1>";
    }
}
	
#######################################################
#		for non-registered client
#######################################################
if (!$email_name) {
	echo '<h1align="center">&nbsp;</h1>
		<h1 align="center">&nbsp;</h1>
		<h1 align="center">&nbsp;</h1>
		<h1 align="center"><b><font size="7">Welcome to Intranet.</font></b></h1> 
		<h1 align="center">&nbsp;</h1>';
    $qrystr =   'rcdaction=addrcd'.$basestr;
    //echo $qrystr.'<br>';   
    $qrystr =   '?'.base64_encode($qrystr);
    echo '<h1 align="center"><a href="/'.$phpdir.'/user_data_collect.php'.$qrystr.'">
    Intranet User Registration </a><br></h1>';
    echo '<h1 align="center"><a href="/'.$phpdir.'/user_new_pc.php">
    I Have A New Computer</a><br></h1>';
}
?>
</html>
