<html>
<HEAD>
<STYLE>
   .DivMenu {position:absolute;
      left:-200;
      top:-1000;
      width:250;
      z-index:100;
      background-color:darkorange;
      border: 4px groove lightgrey;
   }

   .TDMenu {
      color:darkblue;
      font-family:verdana;
      font-size:70%;
      width:100%;
      cursor:default;
   }
</STYLE>
<?php
	include("connet_root_once.inc");
    	$sql = "SELECT description 
        	FROM logging.sysmastertable 
            WHERE item='hdbldate'";
	$result = mysql_query($sql);
	include("err_msg.inc");
    	list($description) = mysql_fetch_array($result);

	echo "<script language=javascript>";
	echo "var mainTitle, titlestr, htmlstr;";
	echo 'mainTitle= "<p align=middle><font size=6><b>Employee Handbook ('.
		$description.')</b></font><p><hr color=red><p>"';
	echo "</script>";
?>

<script language=javascript>
function writehtml(titlestr,htmlstr) {
   //var resultsFrame = window.resultsFrame.document;
   var resultsFrame = window.top.resultsFrame0.document;
   resultsFrame.open()
   resultsFrame.write('<body background="rlaemb.JPG" leftmargin="20">');
   resultsFrame.write(mainTitle);
   if (titlestr) {
   		resultsFrame.write(titlestr);
   }
   if (htmlstr) {
   		resultsFrame.write(htmlstr);
   }
   resultsFrame.write("<p><hr color=red>");
   resultsFrame.close();
}

function createMenu(menuName, menuItems)
{
   var divHTML = '<DIV ID="' + menuName + 'MenuDiv" CLASS="DivMenu"';
   divHTML = divHTML + ' onmouseout="return hideMenu(this)">';

   var tableHTML = '<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=1 ID="' 
      + menuName + 'Table">';
   var tableRowHTML = "";
   var rowCount;
   var totalNoRows = menuItems.length;
   for (rowCount = 0; rowCount < totalNoRows; rowCount++)
   {
      tableRowHTML = tableRowHTML + '<TR><TD ID="' + 
         menuName + menuItems[rowCount][0] + 
        '" RollOver RollOut';
      tableRowHTML = tableRowHTML + ' onclick="goContentOrganiser(\'' 
         + menuItems[rowCount][1] + '\', \'' + menuItems[rowCount][3] + '\')"';
      tableRowHTML = tableRowHTML 
         + 'CLASS="TDMenu">' + menuItems[rowCount][2] 
         + '</TD></TR>';
   }

   return divHTML + tableHTML + tableRowHTML + '</TABLE></DIV>';
}


function showMenu(menuToShow)
{
   var srcElement = event.srcElement;
   var xPos = parseInt(srcElement.offsetLeft);
   var yPos = parseInt(srcElement.offsetTop);
   	var yoffset = 0;
   	var oset;
   	oset = screen.height - yPos
   //alert(screen.height + "; " + oset );

   //alert(menuToShow.bottom + "; " + oset );
   menuToShow.style.left = xPos + 10;	//(srcElement.width)
   menuToShow.style.top = yPos + yoffset; //srcElement.height;
   //alert(srcElement.height);
}

function hideMenu(menuToHide)
{
   if (event.toElement != menuToHide &&
      menuToHide.contains(event.toElement) == false)
   {
      menuToHide.style.left = -200;
      menuToHide.style.top = -1000;
   }
}

function document_onmouseover() 
{
   var srcElement = event.srcElement;

   if (srcElement.tagName == "TD" && typeof(srcElement.RollOver) != "undefined")
   {
      srcElement.style.color = "white";
      srcElement.style.backgroundColor ="darkblue";
   }
}

function document_onmouseout() 
{
   var srcElement = event.srcElement;
   if (srcElement.tagName == "TD" && typeof(srcElement.RollOut) != "undefined")
   {
      srcElement.style.color = "darkblue";
      srcElement.style.backgroundColor = "darkorange";
   }
}

function goContentOrganiser(intno,head) {
var j = textstr1.length;
var k;
var layer, curtype, curlayer, hdstr;
	layer = parseInt(head.substr(1,1));
	//alert(head);
	k = parseInt(intno) + 1;
	titlestr = textstr1[intno];
	htmlstr = "";
	for (i=k; i<j; i++) {
		if (textstr0[i] == head) {
			break;
		}
		hdstr = textstr0[i];
		curtype = hdstr.substr(0,1);
		if (curtype == "h") {
			curlayer = parseInt(hdstr.substr(1,1));
			//alert("(hdstr, curtype, curlayer) =" + hdstr + ", " + curtype + ", " + curlayer );
			if (curlayer < layer) {
				//alert("(break hdstr, curtype, curlayer) =" + hdstr + ", " + curtype + ", " + curlayer );
				break;
			}
		}

	 	htmlstr = htmlstr + textstr1[i];
	}
	//alert(head + "\n" + intno + "\n" + i);
	writehtml(titlestr,htmlstr);
}

document.onmouseover = document_onmouseover;
document.onmouseout = document_onmouseout;

</script>
</HEAD>

<body background="rlaemb.JPG" leftmargin="20">
<H2>Table of Contents</H2><hr color=blue>

<?php
	include("connet_root_once.inc");
	$sql = "SELECT id, style, text FROM library.rlahdbk ORDER BY id";
	$result = mysql_query($sql);
	include("err_msg.inc");
	
	$no = mysql_num_rows($result);
	//echo "Total Entry: $no.<br>";
	//titlestr,htmlstr
	$filestr = "";
	while (list($id, $style, $text) = mysql_fetch_array($result)) {
		$textstr[0][$id] =  $style;
		$textstr[1][$id] =  $text;
		//echo "<b>$id: $style</b><br>";
		//echo $text;
		$filestr= $filestr.$text;
	}
	
	echo "<script language=javascript>";
      	echo "var textstr0 = new Array();\n";
      	echo "var textstr1 = new Array();\n";
	//*
		for ($i=1; $i<=$no; $i++) {
      		$htmlstr = ereg_replace("'", "\'", $textstr[1][$i]);
      		$htmlstr= ereg_replace('"', '\"', $htmlstr);      		
			echo "textstr0[$i] = \"".$textstr[0][$i]."\";\n";
			echo "textstr1[$i] = \"$htmlstr\";\n";
		}
	//*/
	echo "</script>";
	
	$chapter = 0;
	for ($i=1; $i<=$no; $i++) {
		$tmp = substr($textstr[0][$i],0,2);
		if ($tmp == "h1") {
			$chapter++;
			$menu_prex = "C$chapter:&nbsp;&nbsp;";
			$len = strlen($textstr[1][$i]);
			$tmphd = substr($textstr[1][$i],4, $len-9);
			$menuid = "MID$i";
			$menudiv = $menuid."MenuDiv";
			$menuarray = $menuid."array";
			echo "<h4 ID=\"$menuid\" 
				width=\"115\" height=\"20\"
				onmouseover=\"return showMenu($menudiv)\"
   				onmouseout=\"return hideMenu($menudiv)\"
				>$tmphd</h4>";
			//STYLE=\"position:absolute;left:10;top:$top\"
	       echo "<script language=javascript>;";
      		    echo "var $menuarray = new Array();\n";
      		    $menuctr = 0;
      		    $menuindent = "";
      		    echo "$menuarray"."[$menuctr] = new Array();\n";
      		    echo "$menuarray"."[$menuctr][0] = \"$i$tmp\";\n";
      		    echo "$menuarray"."[$menuctr][1] = \"$i\";\n";
      		    echo "$menuarray"."[$menuctr][2] = \"$menuindent$menu_prex$tmphd (View Whole Chapter)\";\n";
      		    echo "$menuarray"."[$menuctr][3] = \"$tmp\";\n";
      		    
      		    $h2 = 0;
      		    $h3 = 0;
      		    for ($j=$i+1; $j<=$no; $j++) {
					$tmp = substr($textstr[0][$j],0,2);
					$newhead = 0;
					if ($tmp == "h1") {
						break;
					} elseif ($tmp == "h2") {
				 		$menuindent = "&nbsp;";
						$h2++;
						$h3 = 0;
						$newhead = 1;
					} elseif ($tmp == "h3") {
				 		$menuindent = "&nbsp;&nbsp;";
						$h3++;
						$newhead = 1;
					}
					if ($newhead == 1) {
						if ($h3 == 0) {
							$menu_prex_down = "$chapter.$h2:&nbsp;&nbsp;";
						} else {
							$menu_prex_down = "$chapter.$h2.$h3:&nbsp;&nbsp;";
						}
						
						$menuctr++;
						$tmphd = $textstr[1][$j];
						$tmphd = ereg_replace("<$tmp>","",$tmphd);
						$tmphd = ereg_replace("</$tmp>","",$tmphd);
      		    		echo "$menuarray"."[$menuctr] = new Array();\n";
      		    		echo "$menuarray"."[$menuctr][0] = \"$j$tmp\";\n";
      		    		echo "$menuarray"."[$menuctr][1] = \"$j\";\n";
      		    		echo "$menuarray"."[$menuctr][2] = \"$menuindent$menu_prex_down$tmphd\";\n";
      		    		echo "$menuarray"."[$menuctr][3] = \"$tmp\";\n";
					}
				}

      		    echo "document.write(createMenu('$menuid', $menuarray));\n";
	       echo "</script>";
		}
	}

######### text for main frame dispaly
//*
	$filestr = ereg_replace("'", "\'", $filestr);
	$filestr = ereg_replace('"', '\"', $filestr);
	echo "<script language=javascript>;";
		//echo "titlestr = \" \";\n";
		//echo "htmlstr = \"$filestr\";\n";
		//echo "writehtml(\"\",\"$filestr\");\n";
	echo "</script>";
//*/
	echo "<hr color=blue>";
?>	
</body>
</html>