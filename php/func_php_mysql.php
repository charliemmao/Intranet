<html>

<b><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/index.html">Home</a></b>
<p>
<pre>
mysql_affected_rows -- Get number of affected rows in previous MySQL operation
	int mysql_affected_rows ([$conn_id])
mysql_change_user --  Change logged in user of the active connection 
	int mysql_change_user (string user, string password [, string database [, $conn_id]])
mysql_close -- Close MySQL connection
	bool mysql_close ([$conn_id])
mysql_connect -- Open a connection to a MySQL Server
	resource mysql_connect ([string hostname [:port] [:/path/to/socket] [, string username [, string password]]])
mysql_create_db -- Create a MySQL database
	int mysql_create_db (string database name [, $conn_id])
mysql_data_seek -- Move internal result pointer
	bool mysql_data_seek (resource result_identifier, int row_number)
mysql_db_name -- Get result data
	string mysql_db_name (resource result, int row [, mixed field])
mysql_db_query -- Send a MySQL query
	resource mysql_db_query (string database, string query [, $conn_id])
mysql_drop_db -- Drop (delete) a MySQL database
	bool mysql_drop_db (string database_name [, $conn_id])
mysql_errno -- Returns the numerical value of the error message from previous MySQL operation
	int mysql_errno ([$conn_id])
mysql_error -- Returns the text of the error message from previous MySQL operation
	string mysql_error ([$conn_id])
mysql_escape_string --  Escapes a string for use in a mysql_query. 
	string mysql_escape_string (string unescaped_string)
mysql_fetch_array --  Fetch a result row as an associative array, a numeric array, or both. 
	array mysql_fetch_array (resource result [, int result_type])
mysql_fetch_assoc --  Fetch a result row as an associative array 
	array mysql_fetch_assoc (resource result)
mysql_fetch_field --  Get column information from a result and return as an object 
	object mysql_fetch_field (resource result [, int field_offset])
mysql_fetch_lengths --  Get the length of each output in a result 
	array mysql_fetch_lengths (resource result)
mysql_fetch_object -- Fetch a result row as an object
	object mysql_fetch_object (resource result [, int result_type])
mysql_fetch_row -- Get a result row as an enumerated array
	array mysql_fetch_row (resource result)
mysql_field_flags --  Get the flags associated with the specified field in a result 
	string mysql_field_flags (resource result, int field_offset)
mysql_field_name --  Get the name of the specified field in a result 
	string mysql_field_name (resource result, int field_index)
mysql_field_len --  Returns the length of the specified field 
	int mysql_field_len (resource result, int field_offset)
mysql_field_seek --  Set result pointer to a specified field offset 
	int mysql_field_seek (resource result, int field_offset)
mysql_field_table --  Get name of the table the specified field is in 
	string mysql_field_table (resource result, int field_offset)
mysql_field_type --  Get the type of the specified field in a result 
	string mysql_field_type (iresource result, int field_offset)
mysql_free_result -- Free result memory
	int mysql_free_result (resource result)
mysql_insert_id --  Get the id generated from the previous INSERT operation 
	int mysql_insert_id ([$conn_id])
mysql_list_dbs --  List databases available on a MySQL server 
	resource mysql_list_dbs ([$conn_id])
mysql_list_fields -- List MySQL result fields
	resource mysql_list_fields (string database_name, string table_name [, $conn_id])
mysql_list_tables -- List tables in a MySQL database
	resource mysql_list_tables (string database [, $conn_id])
mysql_num_fields -- Get number of fields in result
	int mysql_num_fields (resource result)
mysql_num_rows -- Get number of rows in result
	int mysql_num_rows (resource result)
mysql_pconnect --  Open a persistent connection to a MySQL Server 
	resource mysql_pconnect ([string hostname [:port] [:/path/to/socket] [, string username [, string password]]])
mysql_query -- Send a MySQL query
	resource mysql_query (string query [, $conn_id])
mysql_unbuffered_query -- Send an SQL query to MySQL, without fetching and buffering the result rows
	resource mysql_unbuffered_query (string query [, $conn_id [, int result_mode]])
mysql_result -- Get result data
	mixed mysql_result (resource result, int row [, mixed field])
mysql_select_db -- Select a MySQL database
	bool mysql_select_db (string database_name [, $conn_id])
mysql_tablename -- Get table name of field
	string mysql_tablename (resource result, int i)
mysql_get_client_info -- Get MySQL client info
	string mysql_get_client_info (void)
mysql_get_host_info -- Get MySQL host info
	string mysql_get_host_info ([$conn_id])
mysql_get_proto_info -- Get MySQL protocol info
	int mysql_get_proto_info ([$conn_id])
mysql_get_server_info -- Get MySQL server info
	int mysql_get_server_info ([$conn_id])

</pre>
<p><b>Table of Contents</b>
<ul>
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.mysql-affected-rows.html">mysql_affected_rows</a>
  --Get number of affected rows in previous MySQL operation
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.mysql-change-user.html">mysql_change_user</a>
  --Change logged in user of the active connection
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.mysql-close.html">mysql_close</a>
  --Close MySQL connection
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.mysql-connect.html">mysql_connect</a>
  --Open a connection to a MySQL Server
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.mysql-create-db.html">mysql_create_db</a>
  --Create a MySQL database
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.mysql-data-seek.html">mysql_data_seek</a>
  --Move internal result pointer
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.mysql-db-name.html">mysql_db_name</a>
  --Get result data
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.mysql-db-query.html">mysql_db_query</a>
  --Send a MySQL query
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.mysql-drop-db.html">mysql_drop_db</a>
  --Drop (delete) a MySQL database
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.mysql-errno.html">mysql_errno</a>
  --Returns the numerical value of the error message from previous MySQL
    operation
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.mysql-error.html">mysql_error</a>
  --Returns the text of the error message from previous MySQL operation
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.mysql-escape-string.html">mysql_escape_string</a>
  --Escapes a string for use in a mysql_query.
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.mysql-fetch-array.html">mysql_fetch_array</a>
  --Fetch a result row as an associative array, a numeric array, or both.
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.mysql-fetch-assoc.html">mysql_fetch_assoc</a>
  --Fetch a result row as an associative array
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.mysql-fetch-field.html">mysql_fetch_field</a>
  --Get column information from a result and return as an object
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.mysql-fetch-lengths.html">mysql_fetch_lengths</a>
  --Get the length of each output in a result
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.mysql-fetch-object.html">mysql_fetch_object</a>
  --Fetch a result row as an object
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.mysql-fetch-row.html">mysql_fetch_row</a>
  --Get a result row as an enumerated array
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.mysql-field-flags.html">mysql_field_flags</a>
  --Get the flags associated with the specified field in a result
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.mysql-field-name.html">mysql_field_name</a>
  --Get the name of the specified field in a result
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.mysql-field-len.html">mysql_field_len</a>
  --Returns the length of the specified field
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.mysql-field-seek.html">mysql_field_seek</a>
  --Set result pointer to a specified field offset
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.mysql-field-table.html">mysql_field_table</a>
  --Get name of the table the specified field is in
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.mysql-field-type.html">mysql_field_type</a>
  --Get the type of the specified field in a result
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.mysql-free-result.html">mysql_free_result</a>
  --Free result memory
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.mysql-insert-id.html">mysql_insert_id</a>
  --Get the id generated from the previous INSERT operation
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.mysql-list-dbs.html">mysql_list_dbs</a>
  --List databases available on a MySQL server
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.mysql-list-fields.html">mysql_list_fields</a>
  --List MySQL result fields
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.mysql-list-tables.html">mysql_list_tables</a>
  --List tables in a MySQL database
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.mysql-num-fields.html">mysql_num_fields</a>
  --Get number of fields in result
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.mysql-num-rows.html">mysql_num_rows</a>
  --Get number of rows in result
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.mysql-pconnect.html">mysql_pconnect</a>
  --Open a persistent connection to a MySQL Server
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.mysql-query.html">mysql_query</a>
  --Send a MySQL query
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.mysql-unbuffered-query.html">mysql_unbuffered_query</a>
  --Send an SQL query to MySQL, without fetching and buffering the result
    rows
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.mysql-result.html">mysql_result</a>
  --Get result data
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.mysql-select-db.html">mysql_select_db</a>
  --Select a MySQL database
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.mysql-tablename.html">mysql_tablename</a>
  --Get table name of field
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.mysql-get-client-info.html">mysql_get_client_info</a>
  --Get MySQL client info
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.mysql-get-host-info.html">mysql_get_host_info</a>
  --Get MySQL host info
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.mysql-get-proto-info.html">mysql_get_proto_info</a>
  --Get MySQL protocol info
  <li><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/function.mysql-get-server-info.html">mysql_get_server_info</a>
  --Get MySQL server info</dt>

<p><a href="../../0FreeSoftware/01Linux/Intranet/PHP/manual/index.html">Home</a></p>

</html>