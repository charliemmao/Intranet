<html>
  	<!-- second cell <fieldset><legend align=middle><b><font size="2"> -->

    <head>
    <title></title>
    </head>

    <body leftmargin="200">

    <table>
	<td width="35%" valign=\"top\">
       <h2> 
       <?php
       	include("userinfo.inc");
  		include("phpdir.inc");
     		if ($dialin == 1) {
       		echo "<font color=\"#0000FF\">Dial-in Access User</font><br>";
       	} else {
       		echo "<font size=\"5\" face=\"Brush Script MT\">Hi, ".strtoupper($first_name)."</font><br>";
       	}
       echo "</h2>";
       if ( $rlaserver == $thisserver ) {
       	echo "<b>Select One Database and Logon</font></b><br>";
       }
       ?>
		<table border="0" width="301" cellspacing="0" cellpadding="0">
			<tr><td width="100%">
				<?php
					include('connet_root_once.inc');
				if ($priv == "100") {
            		echo '<form method="POST" name="logonform" action="http://'.getenv('server_name').'/'.$phpdir.'/user_new_logon_chk.php" >';
				} else {
            		echo '<form method="POST" action="/'.$phpdir.'/user_new_logon_chk.php" name="logonform">';
            	}
            	
            	echo "<input type=hidden name=winos>";
            	echo "<input type=hidden name=file value=\"".__FILE__."\">";
            	echo "<input type=hidden name=curip value=\"$curip\">";

            	?>

 				<?php include("userstr.inc"); 
 					if ($pwderr == "") {
          				$pwderr = 0;
          			}
          			echo "<input type=\"hidden\" name=\"pwderr\" value=\"$pwderr\">";
              if ( $rlaserver == $thisserver ) {
 					echo '<p style="margin: 10" align="left"><font size="2">
              	<input type="radio" value="timesheet" checked name="db[]"><b>Timesheet</b></font></p>';
					echo '<p style="margin: 10" align="left"><font size="2">
              		<input type="radio" value="library" name="db[]"><b>Library</b></font></p>';
              	echo '<p style="margin: 10" align="left"><font size="2">
              	<input type="radio" value="inventory" name="db[]"><b>Inventory</b></font></p>';
              	echo '<p style="margin: 10" align="left">
              		<font size="2"><input type="radio" value="rlafinance" name="db[]"><b>Order</b></font></p>';
 				} else {
 					echo '<p style="margin: 10" align="left">
              		<input type="hidden" value="timesheet" name="db[]"></p>';
 				}
 				?>
 			</td></tr>
              <td width="100%">
          <?php
				//check whether user has a default password
				include("rla_functions.inc");
				include('search_pwd.inc');
				$full_name = "$first_name $last_name";
				//echo 'password = '.$pws_str.', user ='.$out.'.<br>';
				//$out = 1;
				echo "<tr><td width=\"100%\"><table>";
				$bred = '<font color="#ff0000" size="2" face="Courier New">';
				$b1 = "<font color=\"#0000FF\" size=\"2\" face=\"Courier New\"><b>";
				$b2 = "</b></font>";
       		if ($dialin == 1) {
             		echo "<tr><td>$b1"."Full Name$b2</td>";
       			echo "<input type=hidden name=\"dialin\" value=\"$dialin\">";
              	echo "<td><select name=dialname>";
			include("find_admin_ip.inc");
			if (getenv("remote_addr") == $adminip) {
               		$defsel = "$adminname";
               	} else {
               		$defsel = "$director";
              	}
					include('connet_root_once.inc');
               	$sql = "SELECT email_name, first_name, last_name FROM timesheet.employee 
              		where email_name!='webmaster' ORDER BY first_name;";
              	$result =mysql_query($sql);
              	include("err_msg.inc");
              	$no = mysql_num_rows($result);
              	while (list($email_name, $first_name, $last_name) = mysql_fetch_array($result)) {
              		if ($defsel == $email_name) {
              			echo "<option value=\"$email_name\" selected>$first_name $last_name";
              		} else {
              			echo "<option value=\"$email_name\">$first_name $last_name";
              		}
              	}
              	echo "</option></select></td></tr>";
       		} else {
             		echo "<tr><td>$b1"."<b>Logon Name$b2</td>";
              	echo "<td><font size=\"2\" face=\"Courier New\"><b>$logon_name"."$b2</td></tr>";
       		}
              
              $status = " onMouseOver=\"self.status='Minimum 6 characters and case sensitive.'; return true;\"";
              $status = $status." onMouseOut=\"self.status='Intranet Logon.'; return true;\"";
 			if ($out != "") {
              echo "<tr><td>$b1"."<b>Old Password$b2</td>";
              echo "<td><input type=\"password\" name=\"oldpwd\" size=\"10\" value=\"$email_name"."reslab\">
              	</td></tr>";
              
              if ($pwderr) {
              	echo "<tr><td>$bred";
              } else {
              	echo "<tr><td>$b1";
              }
              echo "<b $status>New Password$b2</td>".
              	"<td><input type=\"password\" name=\"clientpwd\" size=\"10\"></td></tr>";
              	
              if ($pwderr) {
              	echo "<tr><td>$bred";
              } else {
              	echo "<tr><td>$b1";
              }
              echo "<b $status>Confirmation$b2</td>".
               	"<td><input type=\"password\" name=\"clientpwdc\" size=\"10\"></td></tr>";
         } else {
              if ($pwderr) {
              	echo "<tr><td>$bred";
              } else {
              	echo "<tr><td>$b1";
              }
              $status = " onMouseOver=\"self.status='Case sensitive.'; return true;\"";
              $status = $status." onMouseOut=\"self.status='Intranet Logon.'; return true;\"";
            	echo "<b $status>Password$b2</td>".
               	"<td><input type=\"password\" name=\"clientpwd\" size=\"10\"></td></tr>";
          }
          	echo "</table></td></tr>";
              echo '<tr><td width="100%"><p align="left" style="margin: 20">';
              //echo "<button type=\"submit\" name=\"B1\">";
              if ($email_name == "webmaster") {
              echo "<input type=\"submit\" value=\"Logon\" onclick=\"return validatepwd();\" name=\"B1\">";
              } else {
              echo "<button type=\"submit\" onclick=\"return validatepwd();\" name=\"B1\">";
              echo "<font size=3 color=#b016ea><b>Logon</b></font></button>";
              }
          ?>
            </p></td>
            </form>
         </tr>
 <?php	
		include("connet_root_once.inc"); 
		$sql = "SELECT logintime, lastaccess FROM logging.logout WHERE email_name='$email_name';"; 
		$result = mysql_query($sql); 
		include("err_msg.inc"); 
		if (mysql_num_rows($result)) { 
			list($logintime, $lastaccess) = mysql_fetch_array($result); 
			if ($lastaccess) { 
				$t1 = substr($lastaccess, 0, 10); 
				$lastaccess = ereg_replace(" ", "", $lastaccess); 
				$lastaccess = ereg_replace("-", "", $lastaccess); 
				$lastaccess = ereg_replace(":", "", $lastaccess); 
				$lastaccess = substr($lastaccess, 0, 8); 
				$tmp = date("l", $lastaccess);
				include("find_admin_ip.inc");
				if ($email_name != "$adminname") {
					echo "<font size=2><br><b>(Last time accessed on $t1.)</b></font><br>";
				} 
			} 
		} 
		mysql_close(); 
?>
          </td>
        </tr>
      </table>
    </td>
<script language="JAVASCRIPT">
function validatepwd() {
var pwd, target, val1, val2;
	//window.alert("password.");
	target = "oldpwd";
	target = document.all(target);
	if (target == null) {
		//window.alert("validate password.");
		target = "clientpwd";
		target = document.all(target);
		if (target == null) {
			firstele();
			return false;
		}
		//window.alert(target.value.length);
		if (target.value.length <= 0) {
			window.alert("Please enter a no empty password!");
			firstele;
			return false;
		}
	} else {
		//window.alert("validate new password.");
		target = "clientpwd";
		target = document.all(target);
		if (target == null) {
			return false;
		} else {
			val1 = target.value;
		}
		target = "clientpwdc";
		target = document.all(target);
		if (target == null) {
			return false;
		} else {
			val2 = target.value;
		}
		if (val1 != val2) {
			window.alert("Please type same string in \"New password\" and \"Confirmation\" boxes!");
			return false;
		}
		if (val1.length < 6) {
			window.alert("Password has to be at least 6 characters long.");
			return false;
		}
	}
	return true;
}
function firstele() {
var uagent;

	document.logonform.clientpwd.focus(); 
	document.logonform.clientpwd.select();
	if (navigator.userAgent != "") {
		uagent = navigator.userAgent;
	} else {
		uagent="unknown";
	}
	document.logonform.winos.value= uagent;
}
</script>

