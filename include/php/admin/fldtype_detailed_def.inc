<html>
<form>
<font face="Courier New" color="#FF0000"><b>Detailed Data Type Reference<br>
</b>
</font>
  <b>
<select name=&quot;detailedtypedef&gt; <select>
        <option>TINYINT[(M)] [UNSIGNED] [ZEROFILL], 	1 byte
        <option>SMALLINT[(M)] [UNSIGNED] [ZEROFILL], 	2 bytes
        <option>MEDIUMINT[(M)] [UNSIGNED] [ZEROFILL], 	3 bytes
        <option>INT[(M)] [UNSIGNED] [ZEROFILL], 		4 bytes
        <option>INTEGER[(M)] [UNSIGNED] [ZEROFILL],		4 bytes
        <option>BIGINT[(M)] [UNSIGNED] [ZEROFILL],		8 bytes
        <option>
        <option>FLOAT[(M,D)] [ZEROFILL], 4 if M <= 24 or 8 if 25 <= M <= 53
        <option>DOUBLE[(M,D)] [ZEROFILL]
        <option>DOUBLE PRECISION[(M,D)] [ZEROFILL]
        <option>REAL[(M,D)] [ZEROFILL],					8 bytes
        <option>DECIMAL[(M[,D])] [ZEROFILL],				8 bytes
        <option>NUMERIC(M,D) [ZEROFILL],					8 bytes
        <option>
        <option>DATE,										3 bytes
        <option>DATETIME,									8 bytes
        <option>TIMESTAMP[(M)],							4 bytes
        <option>TIME,										3 bytes
        <option>YEAR[(2|4)],								1 bytes	
        <option>
        <option>CHAR(M) [BINARY],	M bytes, 1 <= M <= 255
        <option>[NATIONAL] VARCHAR(M) [BINARY], L+1 bytes, where L <= M and 1 <= M <= 255
        <option>TINYBLOB or TINYTEXT, L+1 bytes, where L < 2^8
        <option>BLOB or TEXT, L+2 bytes, where L < 2^16
        <option>MEDIUMBLOB or MEDIUMTEXT, L+3 bytes, where L < 2^24
        <option>LONGBLOB or LONGTEXT, L+4 bytes, where L < 2^32
        <option>
        <option>ENUM('value1','value2',...), 1 or 2 bytes, maximum of 65535 distinct values
        <option>SET('value1','value2',...), 1, 2, 3, 4 or 8 bytes, maximum of 64 members
</select>
</form>
<br>
</html>