<?php
/* $Id: config.inc.php,v 1.8 2001/02/06 05:59:43 dwilson Exp $ */

// Set the name and version
$cfgProgName 	= "phpPgAdmin";
$cfgVersion 	= "2.3";

// The default database is used to connect to the database to check the adv_auth
//	This can actually be any database you currently have on your system.  It just
//	needs _a_ database to connect and check the system tables.
$cfgDefaultDB	= "template1";

// You should change the superuser if different from postgres
//	This is just used to filter out the system functions when listing
$cfgSuperUser	= "postgres";

//  Set to true if you want to authenticate against the passwd as well as the username
// 	 In order to use adv_auth, you must update the passwords in the user admin section.
//	 It is suggested that you leave this as false until you are able to get in and update the passwords.
$cfgUsePass		= false;

//	Set to true if you want to use the valuntil field in the pg_user table to verify users.
//		WARNING: you must set the expiration field on the account with which you want to login or else you will not get in.
//			It is suggested that you leave this as false until you are able to get in and update the expire fields.
$cfgUseExpire	= false;

//	If you want to be able to view the contents and structure of the System Catalog/Tables set this to true.  
//	If you are new to Postgres or are not familiar with the system tables, it is suggested you leave this as false
$cfgSysTables 	= false;

//	If you want the web interface to administer postgres user, set this as true.
$cfgUserAdmin 	= true;

//	If you want to enable reporting
$cfgReports		= true;

// If you want to filter the databases to only the ones the user has created or owns.  
// This has no affect if the user is a superuser.
$cfgUserDatabases = true;

//  Set this to true during development of phpPgAdmin
$cfgDebug		= false;

// If you do NOT want to quote all relations, set this to false
$cfgQuotes		= true; 

// Maximum pages to display link in browse
$cfgMaxPages	= 9;

// If you do not want to display the tables of a database in the left frame, add it to this array
//	This feature is very useful when you have a database that has many tables and slowes down the load of the left frame.
// $cfgNoTables[]	= "big_table";

// The $cfgServers array starts with $cfgServers[1].  Do not use $cfgServers[0].
// You can disable a server config entry by setting host to ''.
$cfgServers[1]['local']		= true;
$cfgServers[1]['host']		= 'localhost';
$cfgServers[1]['port']		= '5432';
$cfgServers[1]['adv_auth'] 	= true;
$cfgServers[1]['stduser'] 	= '';
$cfgServers[1]['stdpass'] 	= '';
$cfgServers[1]['user'] 		= '';
$cfgServers[1]['password'] 	= '';
$cfgServers[1]['only_db'] 	= '';	// if set to a db-name, only this db is accessible

$cfgServers[2]['local']		= false;
$cfgServers[2]['host'] 		= ''; 
$cfgServers[2]['port'] 		= '5432';
$cfgServers[2]['adv_auth'] 	= true;
$cfgServers[2]['stduser'] 	= '';
$cfgServers[2]['stdpass'] 	= '';
$cfgServers[2]['user'] 		= '';
$cfgServers[2]['password'] 	= '';
$cfgServers[2]['only_db'] 	= '';	// if set to a db-name, only this db is accessible

$cfgServers[3]['local']		= false;
$cfgServers[3]['host'] 		= '';
$cfgServers[3]['port'] 		= '5432';	// Leave blank for default port
$cfgServers[3]['adv_auth'] 	= true;
$cfgServers[3]['stduser'] 	= '';
$cfgServers[3]['stdpass'] 	= '';
$cfgServers[3]['user'] 		= '';
$cfgServers[3]['password'] 	= '';
$cfgServers[3]['only_db'] 	= '';	// if set to a db-name, only this db is accessible

// If you have more than one server configured, you can set $cfgServerDefault
// to any one of them to autoconnect to that server when phpPGAdmin is started,
// or set it to 0 to be given a list of servers without logging in
// If you have only one server configured, $cfgServerDefault *MUST* be
// set to that server.
$cfgServerDefault 			= 1;	// default server  (0 = no default server)
$cfgServer 					= '';	// the selected server is copied here for easier access
unset($cfgServers[0]);	// Since 0 = no server, $cfgServers[0] must not be used

$cfgManualBase 			= "http://www.postgresql.org/users-lounge/docs/7.0/user";
// $cfgManualBase 			= "file:/usr/local/pgsql/doc/user";

$cfgConfirm 				= true;
$cfgPersistentConnections 	= false;

// If you have a table with several thousands of records, you will want to set this false.
$cfgCountRecs				= true;

// If you want the fields alphabetized when inserting/editing
$cfgDoOrder					= false;

$cfgBorder      			= "0";
$cfgThBgcolor				= "#D3DCE3";
$cfgBgcolorOne				= "#CCCCCC";
$cfgBgcolorTwo				= "#DDDDDD";
$cfgMaxRows					= 30;
$cfgMaxInputsize			= "300px";
$cfgOrder					= "ASC";

$cfgShowBlob				= false;
$cfgShowSQL 				= true;

// Set the maximum characters in any given field to diplay in "browse" mode - 0 is unlimited
$cfgMaxText					= 255;

$cfgMember      			= "#CCCCFF";
$cfgNonMember    			= "#CCCC99";
$cfgMaxTries     			= 10;

// Set your language preferences here.
include("english.inc.php");

?>
