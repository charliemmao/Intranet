<html>
<?php
echo " <tr><th class=td1>Data Type</th><td><select name=datatype onclick=\"return copydatatype();\">";
echo "<option value=\"TEXT\">TEXT				variable storage length";
echo "<option value=\"VARCHAR(length)\">VARCHAR(length)	variable storage length with maximum length";
echo "<option value=\"CHAR(length)\">CHAR (length)		fixed storage length, blank-padded to length, internally BPCHAR";
echo "<option value=\"INTEGER\">INTEGER			integer, (+/-) 2 billion range, internally INT4";
echo "<option value=\"INT2\">INT2				integer, 32 thousand range";
echo "<option value=\"INT8\">INT8				integer, (+/-) 4x10^18 range";
echo "<option value=\"OID\">OID					object identifier";
echo "<option value=\"NUMERIC(,)\">NUMERIC(precision, decimal)	number, user-defined precision and decimal location";
echo "<option value=\"FLOAT\">FLOAT				floating-point number, 15-digit precision, internally FLOAT8";
echo "<option value=\"FLOAT4\">FLOAT4				floating-point number, 6-digit precision";
echo "<option value=\"DATE\">DATE				date";
echo "<option value=\"TIME\">TIME				time";
echo "<option value=\"TIMESTAMP\">TIMESTAMP			date and time";
echo "<option value=\"INTERVAL\">INTERVAL			interval of time";
echo "<option value=\"BOOLEAN\">BOOLEAN			boolean, true or false";
echo "<option value=\"POINT\">POINT				point";
echo "<option value=\"LSEG\">LSEG				line segment";
echo "<option value=\"PATH\">PATH				list of points";
echo "<option value=\"BOX\">BOX					rectangle";
echo "<option value=\"CIRCLE\">CIRCLE				circle";
echo "<option value=\"POLYGON\">POLYGON			polygon";
echo "<option value=\"INET\">INET				IP address with optional netmask";
echo "<option value=\"CIDR\">CIDR				IP network address";
echo "<option value=\"MACADDR\">MACADDR			Ethernet MAC address";
echo "</option></select></td></tr>";
?>
</html>
