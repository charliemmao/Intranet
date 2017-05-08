<html>

<?php
echo "<a id=top><h1 align=center>Friday Food Order</h1></a>";
echo "<p align=center><a href=\"".$PHP_SELF."\">[Refresh]</a>
	<a href=\"".$PHP_SELF."?listmyorder=yes\">[List All My Orders]</a>
	<a href=\"".$PHP_SELF."?listallorder=yes\">[List All Orders For this Week]</a>
	";

echo "<hr>";

include("connet_root_once.inc");
if ($listmyorder == "yes" || $listallorder == "yes") {
	$sql = "SELECT foodid, name, price 
        FROM timesheet.foodlist 
        ORDER BY foodid;";
   	$result = mysql_query($sql);
   	include("err_msg.inc");
   	while (list($foodid, $name, $price) = mysql_fetch_array($result)) {
   		$flist[$foodid][0] = $name;
   		$flist[$foodid][1] = $price;
   	}
        
	if ($listmyorder == "yes") {
		$ip = getenv("remote_addr");
		$sql = "SELECT first_name FROM timesheet.employee where computer_ip_addr='$ip'";
   		$result = mysql_query($sql);
   		include("err_msg.inc");
   		list($first_name) = mysql_fetch_array($result);
		echo "<h2>$first_name's Order</h2>";
		$sql = "SELECT foodid, fordate, remarks, orderby 
        FROM timesheet.foodorder 
        WHERE email_name='$email_name' ORDER BY fordate DESC;";
   		$result = mysql_query($sql);
   		include("err_msg.inc");
   		$no = mysql_num_rows($result);
   		if ($no) {
   			echo "<table border=1>";
   			echo "<tr><th>Date</th><th>Food</th><th>Price</th><th>Remarks</th></tr>";
   			while (list($foodid, $fordate, $remarks) = mysql_fetch_array($result)) {
   				echo "<tr><td>fordate</td><td>".$flist[$foodid][0]."</td><td>".$flist[$foodid][1]."</td><td>$remarks</td></tr>";
			}
			echo "</table>";
		} else {
			echo "<b>No order has been found for $first_name.</b>";
		}
	}
	
	if ($listallorder == "yes") {

	}
	echo "<hr>";
}

if ($myorder) {
/*
	echo "\$foodid==\"$foodid\"<br>";
	echo "email_name=$email_name<br>";
	echo "calfriday=$calfriday<br>";
	echo "remarks=$remarks<br>";
	echo "orderby=$orderby<br>";
//*/
	$fordate = $calfriday;
	#check if an order existing for this week
	$sql = "SELECT email_name as fn
        FROM timesheet.foodorder 
        WHERE email_name='$email_name' and fordate='$fordate'";
   	$result = mysql_query($sql);
   	include("err_msg.inc");
   	list($fn) = mysql_fetch_array($result);

	if ($foodid=="---select one---") {
		echo "<b><font color=#ff0000>Please select food from the list.</font></b>";
	} elseif ($foodid=="delete") {
		if ($fn) {
			$sql = "DELETE FROM timesheet.foodorder 
        		WHERE email_name='$email_name' and fordate='$fordate';";
   			$result = mysql_query($sql);
   			include("err_msg.inc");
			echo "<b><font color=#ff0000>Your order for the week $fordate has been cancelled.</font></b>";
		} else {
			echo "<b><font color=#ff0000>You don't have order for the week $fordate.</font></b>";
		}
	} else {
   		if ($fn) {   		
    		$sql = "UPDATE timesheet.foodorder 
        		SET foodid='$foodid', remarks='$remarks', orderby='$orderby'  
            	where email_name='$email_name' and fordate='$fordate';"; 		
          $result = mysql_query($sql);
   			include("err_msg.inc");
			echo "<b><font color=#0000ff>Your order for the week $fordate has been modified.</font></b>";
		} else {
			$sql = "INSERT INTO timesheet.foodorder
   	     		VALUES('$email_name', '$foodid', '$fordate', '$remarks', '$orderby');";
   			$result = mysql_query($sql);
   			include("err_msg.inc");
			echo "<b><font color=#0000ff>Your order for the week $fordate has been sent.</font></b>";
   		}
   	}
   	//echo "<br><br>\$sql = $sql<br>fn=$fn<br>";
   	echo "<hr>";
}

###Food order form            
	echo "<h2>Friday Food Order Form</h2>";
	echo "<form method=post action=\"$PHP_SELF\">";
	echo "<table border=1>";
	echo "<tr><th align=left>Order For</th>";
	$ip = getenv("remote_addr");
	echo "<td><select name=\"email_name\">";
		$sql = "SELECT first_name, last_name, computer_ip_addr FROM timesheet.employee";
   		$result = mysql_query($sql);
   		include("err_msg.inc");
   		while (list($first_name, $last_name, $computer_ip_addr) = mysql_fetch_array($result)) {
   			if ($ip == $computer_ip_addr) {
   				echo "<option value=\"$first_name\" selected>$first_name, $last_name";
   				$orderby = $first_name;
   			} else {
   				echo "<option value=\"$first_name\">$first_name, $last_name";
   			}
   		}
   	echo "</option></select></td></tr>";
   	echo "<input type=hidden name=orderby value=\"$orderby\">";
	echo "<tr><th align=left>Food List</th>";
	echo "<td><select name=\"foodid\">";
	$sql = "SELECT foodid, name, price 
        FROM timesheet.foodlist ORDER BY name;";
    $result = mysql_query($sql);
    include("err_msg.inc");
    echo "<option>---select one---";
    echo "<option value=delete>---cancel my order---";
   	 while (list($foodid, $name, $price) = mysql_fetch_array($result)) {
   		echo "<option value=\"$foodid\">$name (\$$price)";
    }
   	echo "</option></select></td></tr>";
    
	echo "<tr><th align=left>Friday</th><td>";
   include("friday_select.inc");
   echo "</td></tr>";
	echo "<tr><th align=left>Remarks</th><td>";
	echo "<textarea name=remarks rows=4 cols=20></textarea>";
   echo "</td></tr>";
   
	echo "<tr><td colspan=2 align=middle><input type=submit name=\"myorder\" value=\"Submit\"></td></tr>";
   echo "</table>";
	echo "<hr>";

?>