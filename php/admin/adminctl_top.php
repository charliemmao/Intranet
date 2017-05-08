<html>

<head>
<title>Administrator's Tool</title>
</head>

<body background="../images/rlaemb.JPG">

<?php
include("admin_access.inc");

echo '<p align="center"><b><font size="7">'.
	"Administrator's Tool".'</font>'.$entrypoint.'</b></p><hr>';

echo '<ul>';

	echo '
  <li>
    <h5 style="margin-left: 0; margin-top: 6; margin-bottom: 6" align="left">';
	echo '
    <a href="admin_mysql_sys.php'.$admininfo.'" target=_top>
    <font size="3">[MySQL Admin (My NEW)]</font></a>
  </li>';
    //<br><a href="mysqladmin.php'.$admininfo.'"><font size="3">
    //[MySQL Admin (Modified From Others)]</font></a></h5>    

echo '
  <li>
    <h5 style="margin-left: 0; margin-top: 6; margin-bottom: 6" align="left">
    <a href="admin_adduser.php'.$admininfo.'">
    <font size="3">[User and Privilege]</font></a>    
    <a href="admin_resetpwd.php'.$admininfo.'"><font size="3">[Reset Password]</font></a>
    <a href="admin_logbook_sort.php'.$admininfo.'" ><font size="3">[View Logbook]</font></a> 
    </h5>
  </li>
  
  <li>
    <h5 style="margin-left: 0; margin-top: 6; margin-bottom: 6" align="left">
    <font size="3">DBs</font>';
    $qrylogging	=	'?'.base64_encode($userinfo."&dbname=logging&ww=ww");
    $qrytimesheet	=	'?'.base64_encode($userinfo."&dbname=timesheet&ww=ww");
    $qryinventory	=	'?'.base64_encode($userinfo."&dbname=inventory&ww=ww");
    $qrylibrary	=	'?'.base64_encode($userinfo."&dbname=library&ww=ww");
    $qryordfin	=	'?'.base64_encode($userinfo."&dbname=rlafinance&ww=ww");
    $qrymysql	=	'?'.base64_encode($userinfo."&dbname=mysql&ww=ww");
   echo '
    <a href="admin_dbedit.php'.$qrylogging.'" target="_top"><font size=2>[Log Book]</font></a>    
    <a href="admin_dbedit.php'.$qryordfin.'" target="_top"><font size=2>[Finance]</font></a>
    <a href="admin_dbedit.php'.$qrytimesheet.'" target="_top"><font size=2>[Timesheet]</font></a>
    <a href="admin_dbedit.php'.$qryinventory.'" target="_top"><font size=2>[ASSETS]</font></a>
    <a href="admin_dbedit.php'.$qrylibrary.'" target="_top"><font size=2>[Library]</font></a>
    <a href="admin_dbedit.php'.$qrymysql.'" target="_top"><font size=2>[MySQL]</font></a>    
    <font size="3">Management</font>
	</h5>
  </li>

  <li>
    <h5 style="margin-left: 0; margin-top: 6; margin-bottom: 6" align="left">
    <font size="3">Del</font>
    <a href="admin_delete_logging.php'.$admininfo.'"><font size=2>[Log Book]</font></a>
    <a href="admin_delete_finance.php'.$admininfo.'"><font size=2>[Finance]</font></a>
    <a href="admin_delete_timesheet.php'.$admininfo.'"><font size=2>[Timesheet]</font></a>
    <a href="admin_delete_inventroy.php'.$admininfo.'"><font size=2>[ASSETS]</font></a>
    <a href="admin_delete_library.php'.$admininfo.'"><font size=2>[Library]</font></a>
    <font size="3">Records</font></h5>
  </li>';
  
	echo '
  <li>
    <h5 style="margin-left: 0; margin-top: 6; margin-bottom: 6" align="left">';
	echo '
    <a href="admin_timesheet_checkcode.php'.$admininfo.'">
    <font size="3">Check Project Code</font></a>    
	</h5>
  </li>';
  
  echo '<li>
    <h5 style="margin-left: 0; margin-top: 6; margin-bottom: 6" align="left">
    <a href="admin_ctable_outputdata.php'.$admininfo.'" >
    <font size="3">Create Table and Output Table To File (MySQL)</font></a></h5>
  </li>';
  
  echo '<li>
    <h5 style="margin-left: 0; margin-top: 6; margin-bottom: 6" align="left">
    <a href="admin_staff_ip.php'.$admininfo.'" >
    <font size="3">IP List</font></a></h5>
  </li>';

	$sql = "SELECT description as tshconvert FROM logging.sysmastertable WHERE item='tshconvert';";
	$result = mysql_query($sql);
    include("err_msg.inc");
	list($tshconvert) = mysql_fetch_array($result);
	//echo "<li>$tshconvert ff</li>";

if (getenv("server_name") != $tshconvert) {
  echo '<li>
    <h5 style="margin-left: 0; margin-top: 6; margin-bottom: 6" align="left">
    <a href="admin_ctable_outputdata_pg.php'.$admininfo.'" >
    <font size="3">Create Table and Output Table To File (PG)</font></a></h5>
  </li>';
//*
	echo '
  <li>
    <h5 style="margin-left: 0; margin-top: 6; margin-bottom: 6" align="left">';
	echo '
    <a href="admin_timesheet_converter.php'.$admininfo.'">
    <font size="3">Convert Timesheet Database to Demo DB</font></a>    
	</h5>
  </li>';
//*/
}

  echo '<hr>
  <li>
    <h5 style="margin-left: 0; margin-top: 6; margin-bottom: 6" align="left">
    <a href="search_engine.php'.$admininfo.'" ><font size="3">Search Engines</font></a></h5>
  </li>

  <li>
    <h5 style="margin-left: 0; margin-top: 6; margin-bottom: 6" align="left">
    <a href="/cgi-bin/printenv" ><font size="3">Perl Script</font></a></h5>
  </li>
  <li>
    <h5 style="margin-left: 0; margin-top: 6; margin-bottom: 6" align="left">
    <a href="/cgi-bin/test-cgi" ><font size="3">Shell Script</font></a></h5>
  </li>

  <li>
    <h5 style="margin-left: 0; margin-top: 6; margin-bottom: 6" align="left">
    <a href="WorldTimeConverterFramset.php" target="_blank">
    <font size="3">World Time Converter</font></a></h5>
  </li>  
  <li>
    <h5 style="margin-left: 0; margin-top: 6; margin-bottom: 6" align="left">
    <a href="iptraflog.php'.$admininfo.'" ><font size="3">IPTraf Log Analyser</font></a></h5>
  </li>

  <!--

  <li>
    <h5 style="margin-left: 0; margin-top: 6; margin-bottom: 6" align="left">
    <a href="admin_dbrcd_man.php" target="_top">   
    <font size="3" color=#ff0000>DB Records Manipulation</font></a> 
	</h5>
  </li>

  <li>
    <h5 style="margin-left: 0; margin-top: 6; margin-bottom: 6" align="left">
    <a href="admin_initnewtable.php'.$admininfo.'" >
    <font size="3">New Table Initialisation</font></a></h5>
  </li>

  <li>
    <h5 style="margin-left: 0; margin-top: 6; margin-bottom: 6" align="left">
    <a href="authentication.php" ><font size="3">User Authentication</font></a></h5>
  </li>
  
  <li>
    <h5 style="margin-left: 0; margin-top: 6; margin-bottom: 6" align="left">
    <a href="admin_dbmaintenance.php'.$admininfo.'" ><font size="3">Database Mintenance</font></a></h5>
  </li>

  <li>
    <h5 style="margin-left: 0; margin-top: 6; margin-bottom: 6" align="left">
    <a href="admin_create_db_tab_fld_list.php'.$admininfo.'" ><font size="3">List DB, Table, Field</font></a></h5>
  </li>
  
  <li>
    <h5 style="margin-left: 0; margin-top: 6; margin-bottom: 6" align="left">
    <font size="3">Set Privilege</font>';
	echo '
    <a href="admin_privilege_batch.php'.$admininfo.'"><font size=2>[Batch]</font></a>    
	<a href="admin_privilege_one.php'.$admininfo.'"><font size=2>[Individual Setup]</font></a>
	</h5>
  </li>
  -->
 
</ul>';
/*
	echo "PHP_AUTH_USER; $PHP_AUTH_USER";
	echo "PHP_AUTH_PW; $PHP_AUTH_PW";
//*/
//<a href="javascript:window.close();"><font color="#ffc244">Close window</font></a>

echo "<hr>";
include("admin_find_sys.inc");
	echo "Server: ".getenv('SERVER_NAME')."<br>";
	echo "Domain: ".$momainname."<br>";
	echo "Network IP: ".$netipprefix."<br>";
	echo "Is he/she administrator? ".$adminyes."<br>";
	echo "Client's IP: ".$thisip."<br>";

?>
</body>
