<html>

<head>
<meta http-equiv="Content-Language" content="en-au">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<title>Admin main page</title>
</head>

<body background="rlaemb.JPG">

<?php
include('str_decode_parse.inc');
include("phpdir.inc");
echo "<a id=top><h1 align=center><a id=tm>Table Modification ($tablename)</a>";
$refresh = getenv('QUERY_STRING');
echo "<a href=\"$PHP_SELF?$refresh\"><font size=2>[Refresh]</font></a>";
if ($rcdaction == "addrcd" || $rcdaction == "modify"  || $rcdaction == "delete") {
	$refresh = getenv('QUERY_STRING');
	if (ereg_replace("rcdaction", "", "$refresh") == $refresh) {
		$refresh = base64_decode($refresh);
	}
	if ($rcdaction == "addrcd") {
		$main = ereg_replace("rcdaction=addrcd","tabaction=editrcd",$refresh);
	} elseif ($rcdaction == "modify") {
		$main= ereg_replace("rcdaction=modify","tabaction=editrcd",$refresh);
	} elseif ($rcdaction == "delete") {
		$main= ereg_replace("rcdaction=delete","tabaction=editrcd",$refresh);
	}
	echo "<a href=\"".$PHP_SELF."?$main\"><font size=2>[Main]</font></a>";
}
echo "</h1>";

include("admin_access.inc");
	if (!$dbname) {
		exit;
	}

//curline(__LINE__);
//echo __FILE__;
###############################################
# 		parse variables
###############################################
	/*
	echo 'rcdaction: '.$rcdaction.'<br>';
	echo 'tabaction: '.$tabaction.'<br>';
	echo 'dbname: '.$dbname.'<br>';
	echo 'tablename: '.$tablename.'<br>';
	echo 'fldname: '.$fldname.'<br>';
	echo 'fldvalue: '.$fldvalue.'<br>';
	//*/

###############################################
# 		connect to mysql database server
###############################################
include("connet_root.inc");

##############################################
#													#
# 		response to this document				#
#													#
##############################################

###############################################
# 		modify, delete records from table
###############################################
if ($rcdaction) {
	fromme(__LINE__);
	echo $fldname.'<br>';
	echo '<form method="POST" target="main" action= "/'.$phpdir.'/rcd_manipulation.php">';
	//$url	=	getenv('QUERY_STRING');
	include('display_onercd_admin.inc');
	$frm_str	=	'&dbname='.$dbname.'&tablename='.$tablename.'&where='.$where.'&dummy=dummy';

	//echo $where.'<br>';
	//echo $frm_str.'<br>';
	$frm_str	=	base64_encode($frm_str);
	echo '<p align="left"><input type="hidden" value="'.$frm_str.'" name="frm_str">';
	echo '<input type="hidden" name="main" size=100 value="'.$PHP_SELF.'?'.$main.'"><br>';
	echo '<input type="hidden" name="refresh" size=100 value="'.$PHP_SELF.'?'.$refresh.'">';
  	
	if ($rcdaction == 'addrcd') {
  		echo '<p align="left"><input type="submit" value="Add New Record" name="addnewrcd">
  			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp';
	} elseif ($rcdaction == 'modify') {
  		echo '<p align="left"><input type="submit" value="UPDATE RECORD" name="modifyrcd">
  			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp';
	}elseif ($rcdaction == 'delete') {
  		echo '<p align="left"><input type="submit" value="DELETE RECORD" name="deletercd">
  			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp';
	}
  	echo '<input type="submit" value="CANCEL" name="cancel"></p></form>';
       if ($tablename == 'accesslevel') {
           include('display_accesscode.inc');
           echo '<br><br>';
       }
	exit;
}
curline(__LINE__);

###############################################
# 		execute table field modification
###############################################
if ($addfield) {
	fromme(__LINE__);
    /*
    echo $dbname.'<br>';
    echo $tablename.'<br>';
    echo $fldname.'<br>';
    echo $datatype.'<br>';
    echo $typewidth.'<br>';
    echo $typeop.'<br>';
    echo $defaultv.'<br>';
    echo $nullyn.'<br>';
    echo $key.'<br>';
    echo $fld_new_mod.'<br>';
    echo $addfldpos.'<br>';
    exit;
    */
    
    $sql = 'ALTER TABLE '.$dbname.'.'.$tablename;
    #########################################################################
    if ($fld_new_mod == "ADD COLUMN create_definition AFTER column_name") {
        if ($fldname == "") {
            back();
            exit;
        }
        $sql = $sql.' ADD ';
        
        new_or_ch_col($sql,$fldname,$datatype,$typewidth,$typeop,$nullyn,$key,$defaultv);

        if ($addfldpos != ""){
            $sql = $sql.' AFTER '.$addfldpos.';';
        } else {
            $sql = $sql.';';
        }
		 //add key failed same time
        $result = mysql_db_query($dbname,$sql,$contid);
        if ($result){
            echo '<H2><font color="#0000FF">Successful.</font><br>"'.$fldname.'" has been added.</H2><br>';
        }else{
            echo '<H2><font color="#FF0000">Failed.</font><br>MySQL server has failed to create '.$fldname.' in '.$tablename.'.</H2><br>';
        }
        exit;
    }
    
    #########################################################################
    if ($fld_new_mod == "DROP COLUMN col_name") {
        if ($fldname == "") {
            back();
            exit;
        }
        $sql = $sql.' DROP ';
        $sql = $sql.$fldname;
        //echo $sql.'<br>';
        if ($fldname == "") {
            exit;
            echo '<H2>Please enter correct field name.</H2><br>';
        }
        
        $result = mysql_db_query($dbname,$sql,$contid);
        if ($result){
            echo '<H2><font color="#0000FF">Successful.</font><br>"'.$fldname.'" has been dropped.</H2><br>';
        }else{
            echo '<H2><font color="#FF0000">Failed.</font><br>MySQL server has failed to drop '.$fldname.' in '.$tablename.'.</H2><br>';
        }
        exit;
    }
    
    #########################################################################
    if ($fld_new_mod == "ADD COLUMN create_definition FIRST") {
        if ($fldname == "") {
            back();
            exit;
        }
        $sql = $sql.' ADD ';
        
        new_or_ch_col($sql,$fldname,$datatype,$typewidth,$typeop,$nullyn,$key,$defaultv);

        if ($addfldpos != ""){
            $sql = $sql.' FIRST;';
        } else {
            $sql = $sql.';';
        }
        //echo $sql.'<br>';
        //exit;
        $result = mysql_db_query($dbname,$sql,$contid);
        if ($result){
            echo '<H2><font color="#0000FF">Successful.</font><br>"'.$fldname.'" has been added.</H2><br>';
        }else{
            echo '<H2><font color="#FF0000">Failed.</font><br>MySQL server has failed to create '.$fldname.' in '.$tablename.'.</H2><br>';
        }
        exit;
    }
    
    #########################################################################
    if ($fld_new_mod == "CHANGE COLUMN old_col_name create_definition") {
        if ($fldname == "") {
            back();
            exit;
        }
        $sql = $sql.' CHANGE '.$addfldpos.' ';
        
        new_or_ch_col($sql,$fldname,$datatype,$typewidth,$typeop,$nullyn,$key,$defaultv);

        echo $sql.'<br>';
        //exit;
        $result = mysql_db_query($dbname,$sql,$contid);
        if ($result){
            echo '<H2><font color="#0000FF">Successful.</font><br>"'.$fldname.'" has been changed.</H2><br>';
        }else{
            echo '<H2><font color="#FF0000">Failed.</font><br>MySQL server has failed to change '.$fldname.' in '.$tablename.'.</H2><br>';
        }
    
        exit;
    }
    
    #########################################################################
    if ($fld_new_mod == "ADD INDEX [index_name] (index_col_name,...)") {
        if ($fldname == "") {
            $sql = $sql.' ADD INDEX ('.$addfldpos.');';
            $fldname	=	$addfldpos;
        } else {
            $sql = $sql.' ADD INDEX '.$fldname.' ('.$addfldpos.');';
        }
        echo $sql.'<br>';
        //exit;
        $result = mysql_db_query($dbname,$sql,$contid);
        if ($result){
            echo '<H2><font color="#0000FF">Successful.</font><br>Index "'.$fldname.'" has been added.</H2><br>';
        }else{
            echo '<H2><font color="#FF0000">Failed.</font><br>MySQL server has failed to add index '.$fldname.' in '.$tablename.'.</H2><br>';
        }
    
        exit;
    }
    
    #########################################################################
    if ($fld_new_mod == "DROP INDEX index_name") {
        $sql = $sql.' DROP INDEX '.$fldname.';';
        //echo $sql.'<br>';
        //exit;
        $result = mysql_db_query($dbname,$sql,$contid);
        if ($result){
            echo '<H2><font color="#0000FF">Successful.</font><br>Index "'.$fldname.'" has been dropped.</H2><br>';
        }else{
            echo '<H2><font color="#FF0000">Failed.</font><br>MySQL server has failed to drop index '.$fldname.' in '.$tablename.'.</H2><br>';
        }
        exit;
    }
    
    #########################################################################
    if ($fld_new_mod == "ADD PRIMARY KEY (index_col_name,...)") {
        $sql = $sql.' ADD PRIMARY KEY ('.$fldname.');';
        //echo $sql.'<br>';
        //exit;
        $result = mysql_db_query($dbname,$sql,$contid);
        if ($result){
            echo '<H2><font color="#0000FF">Successful.</font><br>PRIMARY KEY for "'.$fldname.'" has been added.</H2><br>';
        }else{
            echo '<H2><font color="#FF0000">Failed.</font><br>MySQL server has failed to add PRIMARY KEY for '.$fldname.' in '.$tablename.'.</H2><br>';
        }
        exit;
    
    }
    
    #########################################################################
    if ($fld_new_mod == "DROP PRIMARY KEY") {
        $sql = $sql.' DROP PRIMARY KEY;';
        //echo $sql.'<br>';
        //exit;
        $result = mysql_db_query($dbname,$sql,$contid);
        if ($result){
            echo '<H2><font color="#0000FF">Successful.</font><br>PRIMARY KEY for "'.$fldname.'" has been dropped.</H2><br>';
        }else{
            echo '<H2><font color="#FF0000">Failed.</font><br>MySQL server has failed to drop PRIMARY KEY for '.$fldname.' in '.$tablename.'.</H2><br>';
        }    
        exit;
    }
    
    #########################################################################
    if ($fld_new_mod == "RENAME [AS] new_tbl_name") {
        $sql = $sql.' RENAME '.$fldname.';';
        echo $sql.'<br>';
        //exit;
        $result = mysql_db_query($dbname,$sql,$contid);
        if ($result){
            echo '<H2><font color="#0000FF">Successful.</font><br>Table "'.$tablename.'" has been renamed as "'.$fldname.'".</H2><br>';
        }else{
            echo '<H2>Table "'.$tablename.'" has not been renamed.</H2><br>';
        }
    
        //echo '<H2>Under Development.</H2><br>';
        exit;
        
    }
    
    #########################################################################
    if ($fld_new_mod == "ADD UNIQUE [index_name] (index_col_name,...)") {
        echo '<H2>Under Development.</H2><br>';
        exit;
        echo '<H2>Under Development.</H2><br>';
        exit;
        $sql = $sql.' ADD INDEX '.$addfldpos.' ';
        echo $sql.'<br>';
        exit;
        $result = mysql_db_query($dbname,$sql,$contid);
        if ($result){
            echo '<H2><font color="#0000FF">Successful.</font><br>"'.$fldname.'" has been changed.</H2><br>';
        }else{
            echo '<H2><font color="#FF0000">Failed.</font><br>MySQL server has failed to change '.$fldname.' in '.$tablename.'.</H2><br>';
        }
    }
    
    /*
    #########################################################################
    if ($fld_new_mod == "ALTER COLUMN col_name {SET DEFAULT literal | DROP DEFAULT}") {
        echo '<H2>Under Development.</H2><br>';
        exit;
    } elseif ($fld_new_mod == "MODIFY COLUMN create_definition") {
        echo '<H2>Under Development.</H2><br>';
        exit;
    } elseif ($fld_new_mod == "table_options") {
        echo '<H2>Under Development.</H2><br>';
        exit; 
    }
    
    $temp=$fldname." ".$datatype;
    if ($typewidth != "") {
        $temp = $temp."(".$typewidth.")";
    }
    $temp=$temp." DEFAULT '".$defaultv."' ".$nullyn;

    //echo "field ".$i." ".$temp."<br><br>";
    $sql = $sql.$temp;
    //AFTER column_name
    echo $sql.'<br>';
    $result = mysql_db_query($dbname,$sql,$contid);
    if ($result){
        echo '<H2><font color="#0000FF">Successful.</font><br>"'.$fldname.'" has been added.</H2><br>';
    }else{
        echo '<H2><font color="#FF0000">Failed.</font><br>MySQL server has failed to create '.$fldname.' in '.$tablename.'.</H2><br>';
    }
	 //*/
	 
    /*
    ALTER [IGNORE] TABLE tbl_name alter_spec [, alter_spec ...]
	 alter_specification:
          ADD COLUMN create_definition [FIRST | AFTER column_name ]
    or    ADD INDEX [index_name] (index_col_name,...)
    or    ADD PRIMARY KEY (index_col_name,...)
    or    ADD UNIQUE [index_name] (index_col_name,...)
    or    ALTER COLUMN col_name {SET DEFAULT literal | DROP DEFAULT}
    or    CHANGE COLUMN old_col_name create_definition
    or    MODIFY COLUMN create_definition
    or    DROP COLUMN col_name
    or    DROP PRIMARY KEY
    or    DROP INDEX index_name
    or    RENAME [AS] new_tbl_name
    or    table_options
    */
}
curline(__LINE__);

###############################################
# 		Build new table form
###############################################
if ($fieldqry) {
    //echo 'Database: '.$dbname.'<br>';
    //echo 'No of fields: '.$nofields.'<br>';
    //echo 'Data base: '.$dbname.'<br>';
?>
<form method="post" action= <?php echo $PHP_SELF ?>>
    <h2 align="left"><b><font face="Courier New" size="5">Create New Table</font></b></h2>
    <input name="dbname" type="hidden" value="<?php echo $dbname ?>">
    <input name="nofields" type="hidden" value="<?php echo $nofields?>">
    <b>New table name</b>
    <input name="tablename" size="20" value=""><br><br>
    <table border="0" width="360" cellspacing="1" cellpadding="0">
    <tr>
        <?php
            $colw[1]=80;    //Field name
            $colw[2]=70;    //Data type
            $colw[3]=70;    //Width
            $colw[4]=80;    //Options
            $colw[5]=80;    //Allow nulls
            $colw[6]=40;    //Key
            $colw[7]=40;    //Default
        ?>
        <td width=<?php echo $colw[1] ?> align="center"><b>Field name</b></td>
        <td width=<?php echo $colw[2] ?> align="center"><b>Data type</b></td>
        <td width=<?php echo $colw[3] ?> align="center"><b>Width</b></td>
        <td width=<?php echo $colw[5] ?> align="center"><b>Allow nulls?</b></td>
        <td width=<?php echo $colw[6] ?> align="center"><b>Key</b></td>
        <td width=<?php echo $colw[7] ?> align="center"><b>Default</b></td>
        <td width=<?php echo $colw[4] ?> align="center"><b>Options</b></td>
    </tr>

    <?php
        //echo $nofields.'<br>';
        $i=1;
        while ($i <= $nofields){
            $fldname="fldname".$i;
            $datatype="datatype".$i;
            $typewidth="typewidth".$i;
            $nullyn="nullyn".$i;
            $key="key".$i;
            $defaultv="defaultv".$i;
            $typeop="typeop".$i;
            include("flddef.inc") ;
            $i=$i+1;
        }
    ?>
    </table>
    <p align="left"><b>Special definition:</b> </p>

    <p align="left">&nbsp; <textarea rows="4" name="special" cols="70"></textarea></p>
    <p align="left"><input type="submit" value="Create New Table" name="createtable"></a></p>
</form>

<?php
	include('fldtype_detailed_def.inc');
	exit;
}
curline(__LINE__);

###############################################
# 		delete table
###############################################
if ($deletetable) {
    //echo 'Delete table <b>'.$tablename.'</b> from database <b>'.$dbname.'.</b><br>';
    if ($tablename != "") {
    	$deletetable = ""; 
    	//$deletetable = "yes";
    	if ($deletetable == "yes") {
        	$sql = "DROP TABLE IF EXISTS $tablename;";
        	$result = mysql_db_query($dbname,$sql,$contid);
        	if ($result){
        	    echo '<H2><font color="#0000FF">Successful.</font><br>"'.$tablename.'" has been deleted.</H2><br>';
       	}else{
       	     echo '<H2><font color="#FF0000">Failed.</font><br>MySQL server has failed to delete table "'.$tablename.'".</H2><br>';
      	  	}
      	 } else {
        	echo '<H2>"'.$tablename.'" can\'t be deleted from MySQL without administrator\'s personal intervention.</H2><br>';
    	 }
    }
    exit;
}

###############################################
# 		create new table: execution
###############################################
if ($createtable) {
    if (trim($tablename) == "") {
    	echo "<h2>Please enter table name</h2>";
		echo '<h2>To return previous page click "BACK" on the toolbar.</h2>';
		exit;
    }
    $nofld = 0;	    
	
    $sql = "CREATE TABLE $tablename (";
    $prikey="";
    $i=1;
    while ($i <= $nofields){
        $fldname="fldname".$i;
        if ($$fldname) {
            $datatype	=	"datatype".$i;
            $typewidth	=	"typewidth".$i;
            $typeop		=	"typeop".$i;
            $defaultv	=	"defaultv".$i;
            $nullyn		=	"nullyn".$i;
            $key			=	"key".$i;
            
            /*
                echo 'Field name is '.$$fldname.'<br>';
                echo 'Data type is '.$$datatype.'<br>';
                echo 'Data width is '.$$typewidth.'<br>';
                echo 'Allow null is '.$$nullyn.'<br>';
                echo 'Key is '.$$key.'<br>';
                echo 'Default is '.$$defaultv.'<br>';
                echo 'Type option is '.$$typeop.'<br>';
            //*/

 				//col_name type [NOT NULL | NULL] [DEFAULT default_value] [AUTO_INCREMENT]
           $temp			=	$$fldname." ".$$datatype;
            if ($$typewidth != "") {
                $temp = $temp."(".$$typewidth.")";
            }
            
            if (trim($$typeop)) {
                	$temp   =   $temp." ".$$nullyn." DEFAULT '".$$defaultv."' ".$$typeop;
              } else {
                	$temp   =   $temp." ".$$nullyn." DEFAULT '".$$defaultv."'";
            }
            $sql = $sql.$temp;
            
            if ($$key != "") {		
                $prikey = $prikey.$$key." (".$$fldname."),";
                //key name is same as column name
            }    
             $sql = $sql.', ';
        	  $nofld += 1;
    		  //echo '<br>'.$nofld.': '.$sql.'<br>';
        }
			//CREATE TABLE employees (id tinyint(4) DEFAULT '0' NOT NULL AUTO_INCREMENT,
			//  first varchar(20),  last varchar(20),  address varchar(255),  
			//position varchar(50),  PRIMARY KEY (id),  UNIQUE id (id));
        /*
        $sql = $sql."logname    VARCHAR(20)             DEFAULT ''              NOT NULL,";
        $sql = $sql."ip         VARCHAR(20)             DEFAULT '' NOT NULL,";
        $sql = $sql."fname      VARCHAR(50)             DEFAULT ''              NOT NULL,";
        $sql = $sql."intime     DATE                    DEFAULT ''              NOT NULL,";
        $sql = $sql."PRIMARY KEY(logname));";
    	 */

        $i=$i+1;
    }
    
    //echo '<br>'.$sql.'<br><br>';
    if ($nofld == 0) {
    	echo '<h2>New table <b>'.$tablename.'</b> contains '.$nofld.' fields.</h2>';
		echo '<h2>To return previous page click "BACK" on the toolbar.</h2>';
		exit;
    }
	
	$sql 		=	trim($sql );
	$str_temp	=	$sql ;
	$chr_move		=	",";
	$pos			=	"$";
	include('remove_ch.inc');
     
	/*       
	echo "before ".$sql.'<br>';
	echo "after ".$str_temp.'<br>';
	//*/
	
	$sql	=	$str_temp;
	//echo '$prikey '.$prikey.'<br>';
	
    if ($prikey != "") {
			$str_temp	=	trim($prikey);
			$chr_move		=	",";
			$pos			=	"$";
			include('remove_ch.inc');
			$prikey	=	$str_temp;
        	$sql = $sql.",".$prikey.");";
    } else {
        $sql = $sql.");";
    }

    //echo 'Special Definition:'.$special.'.<br>';
    echo    'Create new table <b>'.$tablename.'</b> in database <b>'.$dbname.
            '</b> with '.$nofld.' fields.<br><br>';
    
    echo '<br>'.$sql.'<br>';
    //echo 'dbname '.$dbname.'<br>';
    //exit;
    $result = mysql_db_query($dbname,$sql,$contid);
    if ($result){
        echo '<H2><font color="#0000FF">Successful.</font><br>"'.$tablename.'" has been created.</H2><br>';
    }else{
        echo '<H2><font color="#FF0000">Failed.</font><br>MySQL server has failed to create table "'.$tablename.'".</H2><br>';
    }
    exit;
}

##############################################
#													#
# 		response to left document				#
#													#
##############################################

###############################################
# get database, table name and action instruction
# response to left frame request
###############################################
//curline(__LINE__);
	$url	=	getenv('QUERY_STRING');
	if ($url) {
		$url	=	strtok($url,'?');
		$url	=	strtok('?');
		parse_str($url);
	
		/*
		echo 'QUERY_STRING: '.getenv('QUERY_STRING').'<br>';
		echo 'REQUEST_URI: '.getenv('REQUEST_URI').'<br>';
		echo 'SCRIPT_NAME: '.getenv('SCRIPT_NAME').'<br>';
		echo "tabaction is ".$tabaction.'<br>';
		//*/
	}
	
	if ($dbname == "") {
		# set default dispaly
    	//echo 'no db and table are selected.<br>';
		$dbname	=	'timesheet';
		$tablename	=	'employee';
		$tabaction	=	'editrcd';	//$tabaction == "editrcd"	$tabaction == "editfield"
   }
   //echo 'Select database: '.$dbname.'.<br>Select table: '.$tablename.'.<br>';
	
###############################################
# $tabaction == "deltable" | $tabaction == 'createtable'
###############################################	
//echo 'tabaction '.$tabaction.'<br>';
if ($tabaction == 'deltable' or $tabaction == 'createtable') {
	echo '<hr>';
	echo '<form method="post" action="'.$PHP_SELF.'" >';
  	echo '<input name="dbname" type="hidden" value="'.$dbname.'">';
}
//curline(__LINE__);

###############################################
# response to left frame request: create new table
###############################################	
if ($tabaction == "createtable") {
	fromleft(__LINE__);
    echo '
    <p align="left"><b><font face="Courier New" size="5">Create New Table</font></b></p>
    <b>How many fields do you want to be included?</b>
    <input name="nofields" size="20" value="20">
    <p align="left"><input type="submit" value="Next Step" name="fieldqry"></p>';
	
    /*
    <input name="dbname" type="hidden" value="<?php echo $dbname ?>">
    <p align="left"><b><font face="Courier New" size="7">Create New Table</font></b></p>
        <b>New table name</b>
        <input name="tablename" size="20" value="employee">
    <p align="left"><input type="submit" value="Create Table" name="createtable"></p>
    */
}
###############################################
# response to left frame request: delete table
###############################################	
if ($tabaction == "deltable") {
	fromleft(__LINE__);
	echo '<p align="left"><b><font face="Courier New" size="5">Delete Table</font></b></p>';
	echo '<b>Tabel to delete is '.$tablename.'</b>';
	echo '<input name="tablename" type="hidden" value="'. $tablename.'">';
   	echo '<p align="left"><input type="submit" value="Delete Table" name="deletetable"></p>';
}
if ($tabaction == "deltable" || $tabaction == 'createtable') {
	echo '</form>';
	echo '<hr>';
	exit;
}

#########################################
# response to left frame request:
# build new or modify field form
#########################################	
if ($tabaction == 'editfield') {
	fromleft(__LINE__);
	//echo $tablename.'<br';
    echo '<a href="#tm_sec_a">Section A: Field/Column Alteration<br>
    		<a href="#tm_sec_b">Section B: List of Current Table Fields</a><br>
    		<a href="#tm_sec_c">Section C: Other Options</a><br>';
    		$qry0   =   '/'.$phpdir.'/tab_def_out.php?dbname='.$dbname.'&tablename='.$tablename;	
        	$qry    =   $qry0.'&action=fldlist&file='.$PHP_SELF;
        	echo '<a href="'.$qry.'" target="main">Section D: List All Table Fields</a><br>';

    echo '<hr><font color="#000000"><h3><a id=tm_sec_a>Section A: Field/Column Alteration</a></h3>';
    echo '<form method="post" action='.$PHP_SELF.'>';
    echo '<input name="dbname" type="hidden" value="'.$dbname.'">';
    echo '<input name="tablename" type="hidden" value="'.$tablename.'">';
    
    
    echo '<font ><b>Available Choices:</b></font><br>';
    echo '<select name= "fld_new_mod">
            <option>ADD COLUMN create_definition AFTER column_name
            <option>ADD COLUMN create_definition FIRST
            <option>CHANGE COLUMN old_col_name create_definition
            <option>DROP COLUMN col_name
            <option>
            <option>ADD INDEX [index_name] (index_col_name,...)
            <option>DROP INDEX index_name
            <option>
            <option>RENAME [AS] new_tbl_name
            <option>
            <option>ADD PRIMARY KEY (index_col_name,...)
            <option>DROP PRIMARY KEY
            <option>
            <option>ADD UNIQUE [index_name] (index_col_name,...)
 			';
    /*
    echo '<option>ALTER COLUMN col_name {SET DEFAULT literal | DROP DEFAULT}
            <option>MODIFY COLUMN create_definition
            <option>
            <option>table_options';
    */
    
    echo '</select><br>';
    echo '<font face="Courier New"><b>Current Field List </b></font>';
    $boxname = "addfldpos";
    if ($tablename =='') {
    	echo 'No table name is given.<br>';
    } else {
    	fldlist($tablename,$boxname);
    }
    /*
    echo '<font face="Courier New">
    <b>Field List&nbsp;&nbsp; </b></font>';
    $boxname = "listfld";
    fldlist($tablename,$boxname);
    */
    
    include("flddef_onevert.inc");
    echo '<br><input name="addfield" type="submit" value="Action">';
    echo '</form>';

    include('fldtype_detailed_def.inc');
    echo '<hr><font color="#000000"><h3><a id=tm_sec_b>Section B: List of Current Table Fields</a></h3>';
    echo 'Back to&nbsp;<a href="#tm">Top</a><br>';
    ###############################################
    /* response to left frame request:
    1.  build table alteration form
    2.  display current field from table
    */
    ###############################################
    mysql_select_db($dbname);    
    include('fieldinfo.inc');
    include('indexinfo.inc');
	/*
	$result = mysql_query("SELECT * FROM $tablename");
    $fields = mysql_num_fields($result);
    $rows   = mysql_num_rows($result);
    $table  = mysql_field_table($result, $i);
    echo '<b>Table <font color="#0000FF">'.$table.'</font> has ';
    echo '<font color="#0000FF">'.$fields.'</font> fields.<br><br></b>';
    
    if ($fields) {
        echo '<table border="1">';
        echo '<tr>';
            echo '<td align="center"><p align="center"><b>Name</b></p></td>';
            echo '<td align="center"><b>Type</b></td>';
            echo '<td align="center"><b>Length</b></td>';
            echo '<td align="center"><b>Flags</b></td>';
        echo '</tr>';
                
        $i = 0;
        while ($i < $fields) {
            $name  = mysql_field_name  ($result, $i);
            $type  = mysql_field_type  ($result, $i);
            $len   = mysql_field_len   ($result, $i);
            $flags = mysql_field_flags ($result, $i);
            printf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>\n",
                $name,$type,$len,$flags);
            $i++;
        }
        echo '</table><br>';
    }
    */
    echo '<hr><font color="#000000"><h3><a id=tm_sec_c>Section C: Other Options</a></h3></font>';
    echo 'Back to&nbsp;<a href="#tm">Top</a><br>';
    echo '<ul>';
        $qry0   =   '/'.$phpdir.'/tab_def_out.php?dbname='.$dbname.'&tablename='.$tablename;
     	 	
        $qry    =   $qry0.'&action=fldlist';
        echo '<li><a href="'.$qry.'" target="main">
        Show all table fields.</a><br></li>';
        
        $qry    =   $qry0.'&action=readfrm';
        echo '<li><a href="'.$qry.'" target="main">
        Show a text to read form value.</a><br></li>';
       
        /*
        $qry    =   $qry0.'&action=tabdef';
        echo '<li><a href="'.$qry.'" target="main">
        Save a text file to create current table.</a><br></li>';
    
        $qry    =   $qry0.'&action=rcd_lable';
        echo '<li><a href="'.$qry.'" target="main">
        Show a text to control record lable color based on data type.</a><br></li>';
    	 //*/
    echo '</ul>';
    echo '<hr>';
    exit;
}			

if  ($tabaction == 'editrcd') {
    fromleft(__LINE__);
    ###############################################
    /* response to left frame request:
    1.  add new record
    2.  display current records from table
    */
    ###############################################
        //echo $dbname.'<br>';
        //echo $tablename.'<br>';
        
        $file_main      =   $PHP_SELF;      //"admin_dbedit_main.php";
        
        //echo '$file_main  '.$file_main.'<br>';
        
        ####################################################
        //Procedure 1.  add new record
        $qrystr =   'rcdaction=addrcd&dbname='.$dbname.'&tablename='.$tablename;
        //$qrystr =   base64_encode($qrystr);
        //echo $qrystr.'<br>';
        
        //echo 'Before '.$file_main.'<br>';
        echo '<a href="'.$file_main.'?'.$qrystr.'">';
        echo '<h3>Add New Record</h3></a><br>';
        //echo 'After '.$file_main.'<br>';
    
        ####################################################
        //Procedure 2.  display current records from table
        include("connet_root.inc");
        mysql_select_db($dbname);
        
        include('admin_rcd_query_string.inc');
    	 //echo  $sql.' test<br>';
        $result = mysql_query($sql);
        include("err_msg.inc");
        $fields = mysql_num_fields($result);
        $rows   = mysql_num_rows($result);
        //$table = mysql_field_table($result, $i);  // same as $tablename
        echo '<b>There are <font color="#0000FF">'.$rows.'</font> record(s) in the table "';
        echo '<font color="#0000FF">'.$tablename.'"</font>.<br><br>';
        
        /* display partial fields
        if ($rows) {
            echo '<table border="1">';
            echo '<tr>';
            $name  = mysql_field_name  ($result, 0);
            echo '<td align="center"><b>'.$name.'</td>';
            echo '<td align="center"><b>Modify</td>';
            echo '<td align="center"><b>Delete</td>';
            echo '</tr>';
            
            $db_fld_tab =   '&dbname='.$dbname.'&tablename='.$tablename.'&fldname='.$fldtoquery;
            while ($myrow = mysql_fetch_array($result)) {
                echo '<tr>';
                $i=1;
                while (list($key,$val) = each($myrow)) {
                    $i +=1;
                    $j = 2*(int)($i/2);
                    if ($i == $j && $i == 2) {
                        printf('<td>%s</td>',"$val");
                        $qrystr1    =   $db_fld_tab.'&fldvalue='.$val;
                            
                        echo '<td>';
                            $qrystr =   'rcdaction=modify&dbname='.$qrystr1;
                            //$qrystr =   base64_encode($qrystr);
                            echo '<a href='.$file_main.'?'.$qrystr;
                        echo '>Modify</a></td>';
                        
                        echo '<td>';
                            $qrystr =   'rcdaction=delete&dbname='.$qrystr1;
                            //$qrystr =   base64_encode($qrystr);
                            echo '<a href='.$file_main.'?'.$qrystr;
                        echo '>Delete</a></td>';
                        
                        break;
                    }
                }
                echo '</tr>';
            }
            echo '</table><br>';
            //echo $val;
        }
		//*/
		
    	//* display all queried fields
    	//echo "test<br>";
       if ($tablename == 'accesslevel') {
           include('display_accesscode.inc');
       }
       echo $db_fld_tab.'<br>';
        if ($rows) {
            echo '<table border="1">';
            echo '<tr>';
            $i=0;
            while ($i < $fields) {
                $name  = mysql_field_name  ($result, $i);
                printf('<th>%s</b></th>',$name);
                $i++;
            }
            echo '<th>Modify</th>';
            echo '<th>Delete</th>';
            echo '</tr>';
            
        	 $db_fld_tab =   '&dbname='.$dbname.'&tablename='.$tablename;
        	 $fldname =	'';
            while ($myrow = mysql_fetch_array($result)) {
           	$qrystr1	=	'';
                echo '<tr>';
                $i=1;
                while (list($key,$val) = each($myrow)) {
                    $i +=1;
                    $j = 2*(int)($i/2);
                    $k = $k+1;
                    //$key	=	trim($key);
                    //$val	=	trim($val);
                    if ($i != $j) {
                        $jj		=	(int)($i/2);
                        printf('<td>%s</td>',"$val");
                        if ($key == 'activity') {
                        } else {
                        	$t1		=	"&fldname".$jj."=$key";
                        	$t2 	= 	"&fldvalue".$jj."=$val";
                        	//printf('<td>%s</td>',"$t1 $t2");
       						$qrystr1 =   $qrystr1.$t1.$t2;
       					}
                    }
                }
                $qrystr1	=	$qrystr1.'&end=end';                            
			 	//echo $qrystr1;
                echo '<td>';
                     $qrystr =   'rcdaction=modify'.$db_fld_tab.$qrystr1;
                     $qrystr =   base64_encode($qrystr);
                     echo '<a href='.$file_main.'?'.$qrystr;
                echo '>Modify</a></td>';
                        
                echo '<td>';
                     $qrystr =   'rcdaction=delete'.$db_fld_tab.$qrystr1;
                     $qrystr =   base64_encode($qrystr);
                     echo '<a href='.$file_main.'?'.$qrystr;
                echo '>Delete</a></td>';
                echo '</tr>';
            }            
            echo '</table><br>';
        }
    //*/
    }
    
#######################################
#											#
# 			function section 				#
#											#
#######################################
function fldlist($tablename,$boxname) {	
static $i=0;
	if ($i==2) {
		$i=0;
	}
	$result = mysql_query("SELECT * FROM $tablename");
	if (!mysql_errno()){
		$fields = mysql_num_fields($result);
		$rows   = mysql_num_rows($result);
		$i = 0;
		echo '<select name = '.$boxname.'>';
		while ($i < $fields) {
    		$name  = mysql_field_name  ($result, $i);
  			echo "<option>".$name;
    		$i++;
		}
		echo '</select><br>';
	} else {
		$i++;
		if ($i <2) {
			fldlist($tablename,$boxname);
			//echo 'try '.$i.'<br>';
		} else {
			echo ' Error to find fields from '.$tablename.', please try again.<br>';
		}
	}
	//echo "SELECT * FROM $tablename".'<br>';
}

function back() {
	echo '<H2>Please enter a no-empty field name.</H2>';
	echo '<h2>To return previous page click "BACK" on the toolbar.</h2>';
}

function fromleft($line) {
	//echo '<H2>Response to left, check line '.$line.'.</H2>';
}

function fromme($line) {
	//echo '<H2>Response to this document, check line '.$line.'.</H2>';
}

function curline($line) {
	//echo 'On line '.$line.'.<br>';
}

function new_or_ch_col($sql,$fldname,$datatype,$typewidth,$typeop,$nullyn,$key,$defaultv) {
	//echo 'new_or_ch_col '.$sql.'.<br>';
	$sql = $sql.$fldname." ".$datatype;
	if ($typewidth != "") {
		$sql = $sql."(".$typewidth.")";
	}
	if (trim($typeop)) {
		$sql = $sql." ".$nullyn." DEFAULT '".$defaultv."' ".$typeop;
	} else {
		$sql =   $sql." ".$nullyn." DEFAULT '".$defaultv."'";
	}
	/*
	if ($key) {
		$sql = $sql.' '.$key.' ('.$fldname.')';
        //PRIMARY KEY         INDEX        UNIQUE INDEX
	}
	//*/
	$GLOBALS['sql']	=	$sql;
	//echo 'new_or_ch_col '.$sql.'.<br><br>';
}
?>
</body>
