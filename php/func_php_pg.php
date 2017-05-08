<html>

  <a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/index.html">Home</a>
  <pre>
pg_close -- Close a PostgreSQL connection
	bool pg_close ($conn)
pg_cmdtuples -- Returns number of affected tuples
	int pg_cmdtuples ($result)
pg_connect -- Open a PostgreSQL connection
	int pg_connect (string host, string port, string dbname)
	int pg_connect (string host, string port, string options, string dbname)
	int pg_connect (string host, string port, string options, string tty, string dbname)
	int pg_connect (string conn_string)
pg_dbname -- Get the database name
	string pg_dbname ($conn)
pg_end_copy -- Sync with PostgreSQL backend
	bool pg_end_copy ([resource connection])
pg_errormessage -- Get the error message string
	string pg_errormessage ($conn)
pg_exec -- Execute a query
	int $result = pg_exec ($conn, $sql);
pg_fetch_array -- Fetch a row as an array
	array pg_fetch_array (int result, int row [, int result_type])
pg_fetch_object -- Fetch a row as an object
	object pg_fetch_object (int result, int row [, int result_type])
pg_fetch_row -- Get a row as an enumerated array
	array pg_fetch_row (int result, int row)
pg_fieldisnull -- Test if a field is NULL
	int pg_fieldisnull ($result, int row, mixed field)
pg_fieldname -- Returns the name of a field
	string pg_fieldname ($result, int field_number)
pg_fieldnum -- Returns the field number of the named field
	int pg_fieldnum ($result, string field_name)
pg_fieldprtlen -- Returns the printed length
	int pg_fieldprtlen ($result, int row_number, string field_name)
pg_fieldsize --  Returns the internal storage size of the named field 
	int pg_fieldsize ($result, int field_number)
pg_fieldtype --  Returns the type name for the corresponding field number 
	string pg_fieldtype ($result, int field_number)
pg_freeresult -- Free result memory
	int pg_freeresult ($result)
pg_getlastoid -- Returns the last object identifier
	int pg_getlastoid ($result)
pg_host --  Returns the host name associated with the connection 
	string pg_host ($conn_id)
pg_loclose -- Close a large object
	void pg_loclose (int fd)
pg_locreate -- Create a large object
	int pg_locreate (int conn)
pg_loexport -- Export a large object to file
	bool pg_loexport (int oid, int file [, $conn_id])
pg_loimport -- Import a large object from file
	int pg_loimport (int file [, $conn_id])
pg_loopen -- Open a large object
	int pg_loopen (int conn, int objoid, string mode)
pg_loread -- Read a large object
	string pg_loread (int fd, int len)
pg_loreadall --  Read a entire large object and send straight to browser 
	void pg_loreadall (int fd)
pg_lounlink -- Delete a large object
	void pg_lounlink (int conn, int lobjid)
pg_lowrite -- Write a large object
	int pg_lowrite (int fd, string buf)
pg_numfields -- Returns the number of fields
	int pg_numfields ($result)
pg_numrows -- Returns the number of rows
	int pg_numrows ($result)
pg_options -- Get the options associated with the connection
	string pg_options ($conn_id)
pg_pconnect -- Open a persistent PostgreSQL connection
	int pg_pconnect (string conn_string)
pg_port --  Return the port number associated with the connection 
	int pg_port ($conn_id)
pg_put_line -- Send a NULL-terminated string to PostgreSQL backend
	bool pg_put_line ([resource connection_id, string data])
pg_result -- Returns values from a result identifier
	mixed pg_result ($result, int row_number, mixed fieldname)
pg_set_client_encoding --  Set the client encoding 
	int pg_set_client_encoding ([$conn, string encoding])
pg_client_encoding --  Get the client encoding 
	string pg_client_encoding ([$conn])
pg_trace -- Enable tracing a PostgreSQL connection
	bool pg_trace (string filename [, string mode [, $conn]])
pg_tty --  Return the tty name associated with the connection 
	string pg_tty ($conn_id)
pg_untrace -- Disable tracing of a PostgreSQL connection
	bool pg_untrace ([$conn])
</pre>
  <ul>
  <li><b>Table of Contents</b></dt>
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.pg-close.html">pg_close</a>
  --Close a PostgreSQL connection
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.pg-cmdtuples.html">pg_cmdtuples</a>
  --Returns number of affected tuples
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.pg-connect.html">pg_connect</a>
  --Open a PostgreSQL connection
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.pg-dbname.html">pg_dbname</a>
  --Get the database name
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.pg-end-copy.html">pg_end_copy</a>
  --Sync with PostgreSQL backend
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.pg-errormessage.html">pg_errormessage</a>
  --Get the error message string
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.pg-exec.html">pg_exec</a>
  --Execute a query
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.pg-fetch-array.html">pg_fetch_array</a>
  --Fetch a row as an array
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.pg-fetch-object.html">pg_fetch_object</a>
  --Fetch a row as an object
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.pg-fetch-row.html">pg_fetch_row</a>
  --Get a row as an enumerated array
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.pg-fieldisnull.html">pg_fieldisnull</a>
  --Test if a field is <tt class="constant"><b>NULL</b></tt>
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.pg-fieldname.html">pg_fieldname</a>
  --Returns the name of a field
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.pg-fieldnum.html">pg_fieldnum</a>
  --Returns the field number of the named field
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.pg-fieldprtlen.html">pg_fieldprtlen</a>
  --Returns the printed length
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.pg-fieldsize.html">pg_fieldsize</a>
  --Returns the internal storage size of the named field
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.pg-fieldtype.html">pg_fieldtype</a>
  --Returns the type name for the corresponding field number
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.pg-freeresult.html">pg_freeresult</a>
  --Free result memory
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.pg-getlastoid.html">pg_getlastoid</a>
  --Returns the last object identifier
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.pg-host.html">pg_host</a>
  --Returns the host name associated with the connection
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.pg-loclose.html">pg_loclose</a>
  --Close a large object
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.pg-locreate.html">pg_locreate</a>
  --Create a large object
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.pg-loexport.html">pg_loexport</a>
  --Export a large object to file
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.pg-loimport.html">pg_loimport</a>
  --Import a large object from file
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.pg-loopen.html">pg_loopen</a>
  --Open a large object
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.pg-loread.html">pg_loread</a>
  --Read a large object
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.pg-loreadall.html">pg_loreadall</a>
  --Read a entire large object and send straight to browser
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.pg-lounlink.html">pg_lounlink</a>
  --Delete a large object
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.pg-lowrite.html">pg_lowrite</a>
  --Write a large object
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.pg-numfields.html">pg_numfields</a>
  --Returns the number of fields
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.pg-numrows.html">pg_numrows</a>
  --Returns the number of rows
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.pg-options.html">pg_options</a>
  --Get the options associated with the connection
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.pg-pconnect.html">pg_pconnect</a>
  --Open a persistent PostgreSQL connection
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.pg-port.html">pg_port</a>
  --Return the port number associated with the connection
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.pg-put-line.html">pg_put_line</a>
  --Send a NULL-terminated string to PostgreSQL backend
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.pg-result.html">pg_result</a>
  --Returns values from a result identifier
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.pg-set-client-encoding.html">pg_set_client_encoding</a>
  --Set the client encoding
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.pg-client-encoding.html">pg_client_encoding</a>
  --Get the client encoding
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.pg-trace.html">pg_trace</a>
  --Enable tracing a PostgreSQL connection
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.pg-tty.html">pg_tty</a>
  --Return the tty name associated with the connection
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.pg-untrace.html">pg_untrace</a>
  --Disable tracing of a PostgreSQL connection</dt>
<p><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/index.html">Home</a></p>

</html>