<html>
<head>
<title>Library Book Loan Process</title>
</head>
<body leftmargin="20" bgcolor="#FFFFCC" text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="rlaemb.JPG">

<?php
	include('rla_functions.inc');
	include("connet_root_once.inc");
	include('str_decode_parse.inc');
	
	$frm_str="barcode=$barcode&ename=$ename&pwd=$pwd";
	$frm_str	=	base64_encode($frm_str);
	echo "<id=top><h2 align=center>Loan a Book From Library";
	echo "<a href=\"$PHP_SELF?$frm_str\"><font size=2>[Refresh]</font></a>";
//	echo "<a href=\"lib_book_return.php\"><font size=2>[Go to Book Return]</font></a>";
	echo "</h2><hr>";
	echo "$msg";

//################################################################
//book loan form
//################################################################
	echo "<br><h3>Book to Loan</h3>";	
	echo '<form name=bookloan method="POST" action="'.$PHP_SELF.'">';
	echo "<table border=1>";
	if ($barcode) {
		echo "<tr><th align=left>Book Barcode</th>";
	} else {
		echo "<tr><th align=left><font color=#ff0000>Book Barcode</font></th>";
	}
	echo "<td><input type=\"text\" name=\"barcode\" size=\"20\" value=\"$barcode\"></td></tr>";
	
	$ip = getenv("remote_addr");
	$sql = "SELECT email_name as me, first_name as fname, last_name as lname 
		FROM timesheet.employee WHERE computer_ip_addr='$ip'";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($me,$fname,$lname) = mysql_fetch_array($result);
	echo "<tr><th align=left>My Name</th>";
	if (!$ename) {
		$ename = $me;
	}
		
	echo "<td><select name=ename>";
	$sql = "SELECT email_name as me, first_name as fname, last_name as lname 
		FROM timesheet.employee WHERE date_unemployed='0000-00-00' order by fname";
	$result = mysql_query($sql);
	include("err_msg.inc");
	while (list($me,$fname,$lname) = mysql_fetch_array($result) ) {
		if ($me == $ename) {
			echo "<option value=$me selected>$fname $lname";
			$fullname = "$fname $lname";
		} else {
			echo "<option value=$me>$fname $lname";
		}
	}
   echo "</option></select><br>";

	echo "<tr><th align=left>";
	if ($checkout) {
		//check password
		if (!$pwd) {
			echo "<font color=#ff0000>My Passsword</font>";
			$pwdconf ="Empty password";
		} else {
			$sql = 'select user from mysql.user where password=password("'.$pwd.'")';
			//echo $sql.'<br>';
			$result	=	mysql_query($sql);
			include('err_msg.inc');
			list($user) = mysql_fetch_array($result);
			if (!$user || $user != $ename) {
				echo "<font color=#ff0000>My Passsword</font>";
				$pwdconf ="Wrong password";
			} else {
				echo "My Passsword";
				$pwdconf="";
			}
		}
	} else {
		echo "My Passsword";
	}
	echo "</th>";
	echo "<td><input type=\"password\" name=\"pwd\" value=\"$pwd\" size=\"20\"></td></tr>";
	echo "<tr><td colspan=2 align=center><button type=submit name=\"checkout\"><b>Check Out</b></button></td></tr>";
	echo "</form>";
	echo "</table>";
	
//################################################################
//process book loan form
//################################################################
	if ($checkout) {
		echo "<br><hr><br>";
		echo "<h3>Hi $fullname</h3>";
		if ($pwdconf) {
			echo "<h4><font color=#ff0000>$pwdconf.</font><br>Please retype your password to the box provided.</h4>";
  			echo "<hr><a href=#top>Back to top</a><br><br>";
			exit;
		}
		if (!$barcode) {
			echo "<h4><font color=#ff0000>Barcode box is empty.</font></h4>";
  			echo "<hr><a href=#top>Back to top</a><br><br>";
			exit;
		}
		//validate the barcode
		$sql = "SELECT book_id 
				FROM library.for_book  
				WHERE barcode='$barcode'";
		$restatus = mysql_query($sql);
		include("err_msg.inc");
		list($book_id) = mysql_fetch_array($restatus);
		if (!$book_id) {
			echo "<h4><font color=#ff0000>No such a book with barcode of $barcode.</font></h4>";
  			echo "<hr><a href=#top>Back to top</a><br><br>";
			exit;
		}
		
		//book details
		echo "<table border=1>";
		echo "<tr><th align=left>Barcode</th><td>$barcode</td></tr>";
		$sql = "SELECT t1.lib_item_id, t2.libtitle, "
		."t1.dewey, t1.isbn, t1.edition, t1.volume, t1.no_pages, t1.year_published "
		."FROM library.for_book as t1, library.lib_primlist as t2 "
		."WHERE t1.barcode='$barcode' and t1.lib_item_id=t2.lib_item_id;";
	
		$result = mysql_query($sql);
		include("err_msg.inc");
		list($lib_item_id, $libtitle, $dewey, $isbn, $edition, $volume, $no_pages, $year_published) = mysql_fetch_array($result);
		echo "<tr><th align=left>Book Title</th><td>$libtitle</td></tr>";
		if ($dewey) {
			echo "<tr><th align=left>DEWEY</th><td>$dewey</td></tr>";
		}
		if ($isbn) {
			echo "<tr><th align=left>ISBN</th><td>$isbn</td></tr>";
		}
		if ($edition) {
			echo "<tr><th align=left>EDITION</th><td>$edition</td></tr>";
		}
		if ($volume) {
			echo "<tr><th align=left>VOLUME</th><td>$volume</td></tr>";
		}
		if ($no_pages) {
			echo "<tr><th align=left>PAGES</th><td>$no_pages</td></tr>";
		}
		if ($year_published) {
			echo "<tr><th align=left>YEAR</th><td>$year_published</td></tr>";
		}
		echo "</table><p>";

		//check book status
		$sqlstatus = "SELECT status_id, borrower_email_name as bname, date_out 
				FROM library.lib_item_status  
				WHERE barcode='$barcode' and date_return='0000-00-00'
				ORDER BY status_id;";
		$restatus = mysql_query($sqlstatus);
		include("err_msg.inc");
		list($status_id, $bname, $date_out) = mysql_fetch_array($restatus);
		if ($status_id) {
			echo "<h3><font color=#ff0000>";
			if ($ename == $bname) {
				echo "You borrowed the above book on $date_out.";
			} else {
				$sql = "SELECT first_name as fname, last_name as lname from timesheet.employee 
					where email_name='$bname'";
				$restatus = mysql_query($sql);
				include("err_msg.inc");
				list($fname, $lname) = mysql_fetch_array($restatus);
				echo "The book was out on $date_out, please see $fname $lname if you want it.";
			}
			echo "</font></h3>";
		} else {
			$date_out = date("Y-m-d");
			$outip = getenv("remote_addr");
			$sql = "INSERT INTO library.lib_item_status 
			SET lib_item_id='$lib_item_id', barcode='$barcode', 
			borrower_email_name='$ename', date_out='$date_out', 
			outip='$outip'";
			//echo "$sql<br>";
			$restatus = mysql_query($sql);
			include("err_msg.inc");
			echo "<h4>This above item has been checked out successfully.</h4>";
		}
	}
	
	if ($checkin) {
		echo "<br><hr><br><h3>Book Return</h3>";
		if (count($retbookid)) {
			echo "<table border=1>";
			echo "<tr><th>No</th><th>Barcode</th><th>Title</th><th>Date</th></tr>";
			for ($i=0; $i<count($retbookid); $i++) {
				$j=$i+1;
				$status_id = $retbookid[$i];
				$sql = "SELECT lib_item_id as itemid, barcode as bcode, date_out FROM library.lib_item_status WHERE 
					status_id='$status_id'";
				$result0= mysql_query($sql);
				include("err_msg.inc");
				while (list($itemid, $bcode, $date_out) = mysql_fetch_array($result0)) {
					//lib_primlist. columns: lib_item_id, cat_id, author_id, libtitle
					$sql = "SELECT libtitle FROM library.lib_primlist WHERE lib_item_id='$itemid'";
    				$result1 = mysql_query($sql);
    				include("err_msg.inc");
    				list($libtitle) = mysql_fetch_array($result1);
					echo "<tr><td>$j</td><td>$bcode</td><td>$libtitle</td><td>$date_out</td>";
					echo "</tr>";
					
					$date_return = date("Y-m-d");
					$retip = getenv("remote_addr");
					$sql = "UPDATE library.lib_item_status SET 
							date_return='$date_return', retip='$retip' 
							where status_id='$status_id'";
    				$result1 = mysql_query($sql);
    				include("err_msg.inc");
				}
			}
			echo "</table>";
			
			if (count($retbookid)>1) {
				echo "<h3>The above books have been successfully checked in.</h3>";
			} else {
				echo "<h3>The above book has been successfully checked in.</h3>";			
			}
		} else {
			echo "<h3><font color=#ff0000>You didn't check any box.</font></h3>";
		}
	}
	
	if ($ename && $pwd){
		//Current borrowing status
		echo "<br><hr><h3>Current Loan Details</h3>";
		$sql = "SELECT status_id, lib_item_id as itemid, barcode as bcode, date_out 
				FROM library.lib_item_status  
				WHERE borrower_email_name='$ename' and date_return='0000-00-00'
				ORDER BY date_out desc;";
		//status_id, lib_item_id, barcode, borrower_email_name, date_out, date_return
		$result= mysql_query($sql);
		include("err_msg.inc");
		$no = mysql_num_rows($result);
		if ($no) {
			echo "You are currently borrowing $no book(s) from library.<br><br>";
			echo '<form name=bookreturn method="POST" action="'.$PHP_SELF.'">';
			echo "<table border=1>";
			echo "<tr><th>No</th><th>Barcode</th><th>Title</th><th>Date</th>";
			echo "<th>Return?</th>";
			echo "</tr>";
			$frm_str="barcode=$barcode&ename=$ename&pwd=$pwd";
			$frm_str	=	base64_encode($frm_str);
			echo "<input type=hidden name='frm_str' value='$frm_str'>";
			$i=1;
			while (list($status_id, $itemid, $bcode, $date_out) = mysql_fetch_array($result)) {
				//lib_primlist. columns: lib_item_id, cat_id, author_id, libtitle
				$sql = "SELECT libtitle FROM library.lib_primlist WHERE lib_item_id='$itemid'";
    			$result1 = mysql_query($sql);
    			include("err_msg.inc");
    			list($libtitle) = mysql_fetch_array($result1);
				echo "<tr><td>$i</td><td>$bcode</td><td>$libtitle</td><td>$date_out</td>";
				echo "<td align=center><input type=\"checkbox\" name=\"retbookid[]\" value=\"$status_id\"></td>";
				echo "</tr>";
				$i++;
			}
			echo "<tr><td colspan=5 align=center>
			<button type=submit name=\"checkin\"><b>Check In</b></button></td></tr>";	
			echo "</table>";
			echo "</form>";
		} else {
			echo "You are not borrowing any book from library.<br>";
		}
}	
	echo "<br>";

	echo "<hr><a href=#top>Back to top</a><br><br>";
	
	/*
	$sql = "DELETE FROM library.lib_item_status ";
	$result= mysql_query($sql);
	include("err_msg.inc");
	//*/

?>
</body>
