<html>
<head>
<LINK REL="StyleSheet" HREF="../style/style.css" TYPE="text/css">
<title></title>
</head>
<body background="rlaemb.JPG" leftmargin="20">
<?php
echo "<h2 align=center><a id=top>Goods Price History</a></h2><hr>";
include("connet_root_once.inc");

    $sql = "SELECT name, description, supid, product_code 
        FROM rlafinance.goodsid 
        WHERE goods_id='$goods_id';";
    $result = mysql_query($sql);
    include("err_msg.inc");
    $no = mysql_num_rows($result);
    if (!$no) {
    	echo "<h2>No such a goods exist in the DB</h2>";
    	exit;
    }
    list($name, $description, $supid, $product_code) = mysql_fetch_array($result);
    if (!$product_code) {
    	$product_code = "---";
    }
    echo "<h2>Goods Info</h2>";
    echo "<table>";
    echo "<tr class=tr8><th align=left>Goods Name</th><td>$name</td></tr>";
    echo "<tr class=tr8><th align=left>Description</th><td>$description</td></tr>";
    echo "<tr class=tr8><th align=left>Product Code</th><td>$product_code</td></tr>";
    echo "</table>";

	$sql = "SELECT company, address, contactperson, 
            telno, faxno, mobno, email, www 
        FROM rlafinance.supplierid 
        WHERE supid='$supid';";
    $result = mysql_query($sql);
    include("err_msg.inc");
    $no = mysql_num_rows($result);
    list($company, $address, $contactperson, 
            $telno, $faxno, $mobno, $email, 
            $www) = mysql_fetch_array($result);
    echo "<h2>Supplier Info</h2>";
    echo "<table>";
    echo "<tr class=tr8><th align=left>Company</th><td>$company</td></tr>";
    echo "<tr class=tr8><th align=left>Address</th><td>$address</td></tr>";
    echo "<tr class=tr8><th align=left>Tel</th><td>$telno</td></tr>";
    echo "<tr class=tr8><th align=left>Fax</th><td>$faxno</td></tr>";
    if ($mobno) {
    	echo "<tr class=tr8><th align=left>Mobile</th><td>$mobno</td></tr>";
    }
    if ($email) {
    	echo "<tr class=tr8><th align=left>Email</th><td><a href=\"mailto:$email\">$email</a></td></tr>";
    }
    if ($contactperson) {
    	echo "<tr class=tr8><th align=left>Contact Person</th><td>$contactperson</td></tr>";
    }
    if ($www) {
    	echo "<tr class=tr8><th align=left>WWW</th><td><a href=\"http://$www\" target=\"_new\">$www</a></td></tr>";
    }
    echo "</table>";
	
	$sql = "SELECT price, ymd 
        FROM rlafinance.priceref 
        WHERE goods_id='$goods_id'
        ORDER BY ymd DESC;";
    $result = mysql_query($sql);
    include("err_msg.inc");
    echo "<h2>Price History</h2>";
    echo "<table>";
    echo "<tr><th>Date</th><th>Price</th></tr>";
    while (list($price, $ymd) = mysql_fetch_array($result)) {
    	echo "<tr class=tr8><td>$ymd</td><td aligh=right>&nbsp;&nbsp;$price</td></tr>";
    }
    echo "</table>";

//echo "<hr><br><a href=#top>Back to top</a><br><br>";
?>
</html>
