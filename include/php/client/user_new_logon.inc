<html>

<head>
<title></title>
</head>
<body onLoad="firstele();" topmargin="20" leftmargin="0">
<script language=javascript>
</script>

<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>

<?php
	#echo "b email_name = $email_name; curip = $curip<br>";

	include("phpdir.inc");
	include("find_domain.inc");	
	if ( $rlaserver != $thisserver ) {
		include("demologon.inc");
		exit;
	}
	#echo "$basestr<br>";

?>

	<td align=middle width="45%" valign=\"top\">
     <b>
      <p style="margin-left: 10; margin-bottom: 0" align="center">
      <font size="4" face="Brush Script MT">
      RESEARCH LABORATORIES<br>OF AUSTRALIA PTY LTD<br></font></p>
      <p style="margin-left: 10; margin-top: 10; margin-bottom: 0" align="center">
      <i><font color="#663333" face="Brush Script MT" size="4">EXCELLENCE IN DIGITAL
      PRINTING INNOVATION</font></i></b>
     </p>
     <p align="center">&nbsp;
     <p align="center"><font face="Brush Script MT" size="4"><br></font>
     <p align="center"><font face="Brush Script MT"><br>
     </font>
      <?php
	include("find_admin_ip.inc");
	if (getenv("remote_addr") == $adminip."000") {
      		srand((double)microtime()*1000000);
			$i = rand(1,59);
			if ($i<10) {
				$i="00$i";
			} else {
			$i="0$i";
			}
			$images= "/images/rlapeople/Pface$i.jpg";
			//echo getenv('SERVER_NAME').$images."<br>";
      		echo "<img src=\"$images\"></p><p>";
     	} else {
           echo  '<img height="214" src="../../../images/rla_front.jpg" width="318">';
     	}
      ?>
     <p></p>
    </td>

  	<!-- second cell <fieldset><legend align=middle><b><font size="2"> -->
	<td width="35%" valign=\"top\">
       <h2> 
       <?php
       	include("userinfo.inc");
       	if ($dialin == 1) {
       		echo "<font color=\"#0000FF\">Dial-in Access User</font><br>";
       	} else {
       		echo "<font size=\"5\" face=\"Brush Script MT\">Hi, ".strtoupper($first_name)."</font><br>";
       	}
       ?>
       </h2>
       <b>Select One Database and Logon</font></b><br>
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
            	if ($curip == "174" || $curip == "143") {
            		#echo "ip=$curip";            	
            	}

            	?>

 				<?php include("userstr.inc"); 
					
 					if ($pwderr == "") {
          				$pwderr = 0;
          			}
          			echo "<input type=\"hidden\" name=\"pwderr\" value=\"$pwderr\">";
 				echo '
              <p style="margin: 10" align="left"><font size="2"><input type="radio" value="timesheet" checked name="db[]"><b>Timesheet</b></font></p>
              <p style="margin: 10" align="left"><font size="2"><input type="radio" value="library" name="db[]"><b>Library</b></font></p>
              <p style="margin: 10" align="left"><font size="2"><input type="radio" value="inventory" name="db[]"><b>Inventory</b></font></p>';
              
              echo '
              <p style="margin: 10" align="left"><font size="2">
              <input type="radio" value="rlafinance" name="db[]"><b>Order</b></font></p>';
 				//}
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
               		$defsel = $adminname;
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
         
         <tr><td>&nbsp;</td></tr>
         <tr><td colspan=2><font size=3><b>Or Without Login to Visit</b></font>
         <ul>
         <li><font size=2>
         <a 	onMouseOver="self.status='Policy Document'; return true"
         		onMouseOut="self.status=''; return true"
         		href="/<?php echo $phpdir ?>/viewrladoc.php" target=_top>
         		<b>Read Policies</b></a></font></li>

         <li><font size=2>
         <a 	onMouseOver="self.status='World Time Converter'; return true"
         		onMouseOut="self.status=''; return true"
         		href="/<?php echo $phpdir ?>/WorldTimeConverterFramset.php" target=_top>
         		<b>World Time Converter</b></a></font></li>
         </ul></td></tr>

        <tr><td height="5%" width="100%">
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
				if ($email_name != $adminname) {
					#echo "$sql";
					echo "<font size=2><br><b>(Last time/$email_name accessed on $t1.)</b></font><br>";
				} 
			} 
		} 
		mysql_close(); 
?>
          </td>
        </tr>
      </table>
    </td>
  	<!-- third cell -->
        <td width="20%" valign="top">
        <p align="right"><img border="0" src="/images/rla_tr_small.gif" width="100" height="73"></p>
<?php
	echo '<p align=right><h2><font face="Brush Script MT" size="4">&nbsp; Staff List</font></h2>';
	$sql = "select email_name, first_name, last_name, rla_ph_ext 
		from timesheet.employee where date_unemployed='0000-00-00' and email_name!='webmaster' 
		order by first_name;";
	include("connet_root_once.inc");
	$result = mysql_query($sql);
	include("err_msg.inc");
	echo "<p align=right><table border=1 cellspacing=\"0\" cellpadding=\"0\">";
	$b1 = "<font size=2>";
	$b2 = "</font>";
	echo "<tr><th>".$b1."Name$b2</th><th>".$b1."Ext$b2</th><th>".$b1."Name$b2</th><th>".$b1."Ext$b2</th></tr>";
	$i = 0;
	while (list($email_name, $first_name, $last_name, $rla_ph_ext) = mysql_fetch_array($result)) {
		$status = "mailto: $last_name, $first_name";
		$status = " onMouseOver=\"self.status='$status.'; return true;\"";
		$status = $status." onMouseOut=\"self.status='Intranet Logon.'; return true;\"";
		if (!$rla_ph_ext) {
			$rla_ph_ext = "&nbsp;";
		}
		$j = (int)($i/2);
		if($j*2 == $i) {
			echo "<tr>	<td><a href=\"mailto:$email_name@rla.com.au\"><font size=2 $status>$email_name$b2</a></td>
			<td>$b1$rla_ph_ext$b2</td>";
		} else {
			echo "<td><a href=\"mailto:$email_name@rla.com.au\"><font size=2 $status>$email_name$b2</a></td>
			<td>$b1$rla_ph_ext$b2</td></tr>";
		}
		$i++;
	}
	echo "</table>";
?>
  </td></tr>
<tr><td colspan=3 width="100%" align=middle><br><br>
	<font size="4" face="Brush Script MT" color=#0000ff><b>
	<marquee scrolldelay="50" width="80%">
	<?php
	       if ($dialin == 1) {
       		echo "Dial-in access user, welcome you to use Intranet";
       	} else {
				echo "$full_name, welcome you to use Intranet"; 
       	}
	?></marquee> 
	</b></font>
</td></tr>
  <tr><td colspan=3 width="100%"><br><hr>
 <table border="0" width="80%" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td width="40%"><font size="2">
    <?php
		echo 'Research Laboratories of Australia<br>'
		.'7 Valetta Rd, Kidman Park, SA 5025, Australia<br>'
		.'Tel: 61-8-83521322	    Fax: 61-8-83521359';
		echo '</font></td>';
		
		echo '<td width="40%"><font size="2">';
    	$str	=	getenv('SERVER_NAME');
    	//echo $str.'<br>';
		//echo 'Return to <a href="http://'.$str.'" target="_top">Intranet</a>. ';
		echo 'Visit Web Page [<a href="http://'.$str.'/rla_pub_www" target="_blank">Internal</a>] or 
			[<a href="http://www.rla.com.au" target="_blank">External</a>].<br>';
		echo 'Please email your comments to <a href="mailto:admin@'.$str.'">Intranet Developer</a>.<br>';

		echo 'Intranet was last modified on ';
		print (date("F d, Y"));
	?>
</font></td></tr></table><hr>
  </td></tr>
</table>
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

