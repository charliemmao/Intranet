<html>

<?php
echo "<a id=top><h1 align=center>Friday Food List</h1></a>";
echo "<p align=center><a href=\"".$PHP_SELF."\">[Refresh]</a>";

echo "<hr>";

include("connet_root_once.inc");

/*
echo "foodid = $foodid <br>";
echo "name  = $name  <br>";
echo "price = $price <br>";
echo "action  = $action  <br>";
echo "newentry = $newentry <br>";
echo "actiontype  = $actiontype  <br>";
//*/
###process delete
	if ($action == "delete" && $foodid >0) {//<
		echo "<h2>Delete Food Entry</h2>";
		$sql = "SELECT name, price 
        FROM timesheet.foodlist 
        WHERE foodid='$foodid'";
   		$result = mysql_query($sql);
   		include("err_msg.inc");
   		list($name, $price) = mysql_fetch_array($result);
    	$sql = "DELETE FROM timesheet.foodlist 
        WHERE foodid='$foodid'";
       $result = mysql_query($sql);
    	include("err_msg.inc");        
		echo "<b>Food $name (\$$price) has been removed from the list.</b><br>";
		echo "<hr>";
	}
		
###process entry (new + modify)
	if ($newentry) {
		if ($actiontype == "new") {
			echo "<h2>Process New Food Entry</h2>";
			if (!$name && $price == 0) {
				echo "<b><font color=#ff0000>Please enter name and price in the boxes.</font></b>";
			} elseif (!$name) {
				echo "<b><font color=#ff0000>Please enter name in the boxes.</font></b>";
			} elseif ($price == 0) {
				echo "<b><font color=#ff0000>Please enter price in the boxes.</font></b>";
			} else {
				#check existence
				$sql = "SELECT foodid, price 
        			FROM timesheet.foodlist 
        			WHERE name='$name'";
				$result = mysql_query($sql);
    			include("err_msg.inc");
				$no = mysql_num_rows($result);
				if ($no) {        			 
        			echo "<b>Food $name is already in the food list, use modify to change the price</b><br>";
        		} else {
					$sql = "INSERT INTO timesheet.foodlist 
     		   		SET foodid='null', name='$name', price='$price';";
					$result = mysql_query($sql);
    				include("err_msg.inc");
					echo "<b>New food entry has been processed sucessfully.</b>";
				}
    		}
		} elseif ($actiontype == "modify") { 
			echo "<h2>Modify Food Entry</h2>";
			if (!$name || $price == 0) {
				echo "<b><font color=#ff0000>Please enter name and price in the boxes.</font></b>";
			} else {
				$sql = "UPDATE timesheet.foodlist 
       	 		SET name='$name', price='$price' 
      		  		where foodid='$foodid';";
				$result = mysql_query($sql);
    			include("err_msg.inc");
				echo "<b>Food entry has been modified sucessfully.</b>";
			}
    	}
    	echo "<hr>";
	}

###form to manipulate entry
	echo "<form method=post action=\"$PHP_SELF\">";
	if ($newentry == "Modify Food Record") {
		$action = "";
	}
	if ($action == "modify") {
		$header = "Modify Food Record";
		$caption = "Modify Food Record";
		$actiontype = "modify";
		echo "<input type=hidden name=\"foodid\" value=\"$foodid\">";
		$sql = "SELECT name, price 
        FROM timesheet.foodlist 
        WHERE foodid='$foodid'";
   		$result = mysql_query($sql);
   		include("err_msg.inc");
   		list($name, $price) = mysql_fetch_array($result);
	} else {
		$header = "Add New Entry to Food List";
		$caption = "Add New Entry";
		$name = "";
		$price= "0.00";
		$actiontype = "new";
	}
	
	echo "<input type=hidden name=\"actiontype\" value=\"$actiontype\">";
	echo "<h2>$header</h2>";
	echo "<table border=1>";
	echo "<tr><th align=left>Name</th><td><input type=text name=\"name\" value=\"$name\" size=20></td></tr>";
	echo "<tr><th align=left>Price</th><td><input type=text name=\"price\" value=\"$price\"></td></tr>";
	echo "<tr><td colspan=2 align=middle><input type=submit name=\"newentry\" value=\"$caption\"></td></tr>";
	echo "</table>";
	echo "</form>";
	echo "<hr>";
	
###Current Food List
	echo "<h2>Current Food List</h2>";
	$sql = "SELECT foodid, name, price 
        FROM timesheet.foodlist ORDER BY name;";

    $result = mysql_query($sql);
    include("err_msg.inc");
    $no = mysql_num_rows($result);
	if ($no == 0) {
		echo "<b>No entry found in food list.</b>";
	} else {	
		echo "<table border=1>";
		echo "<tr><th>Name</th><th>Price</th><th>Action</th></tr>";
    	while (list($foodid, $name, $price) = mysql_fetch_array($result)) {
        	echo "<tr><td>$name</td><td>$price</td>";
        	echo "<td><a href=\"$PHP_SEFT?action=modify&foodid=$foodid\">[Modify]</a>
        	<a href=\"$PHP_SEFT?action=delete&foodid=$foodid\">[Delete]</a>
        	</td></tr>";
    	}
    	echo "</table>";
	}
	echo "<hr>";

?>
</html>