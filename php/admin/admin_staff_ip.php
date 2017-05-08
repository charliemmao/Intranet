<html>

<head>
<title>IP List</title>
</head>

<body background="../images/rlaemb.JPG">

<?php
include("admin_access.inc");
    $sql = "SELECT email_name, logon_name, title, first_name, 
            middle_name, last_name, computer_ip_addr 
        FROM timesheet.employee 
        WHERE date_unemployed='0000-00-00'
        ORDER BY computer_ip_addr;"; //computer_ip_addr !='' and 

    $result = mysql_query($sql);
    include("err_msg.inc");
    $no = mysql_num_rows($result);
    echo "<h2>RLA staff PC IP List ($no)</h2>";
    $i = 1;
    echo "<table border=1><tr><th>No</th><th>EMAIL_NAME</th><th>NAME</th><th>COMPUTER_IP_ADDR</th></tr>";

    while (list($email_name, $logon_name, $title, $first_name, 
            $middle_name, $last_name, $computer_ip_addr) = mysql_fetch_array($result)) {
        echo "<tr><td>$i</td><td>$email_name</td>
			<td>$title $first_name $middle_name $last_name</td><td>$computer_ip_addr</td></tr>";
		$i++;
    }

    echo "</table>";

?>
</body>
