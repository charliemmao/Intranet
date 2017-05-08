<html>
<?php
//echo "$usedb.$usetable<br>$fldselect<br>$orderby<br>";
$hiddentext = "<input type=\"hidden\" value=\"$usedb\" name=\"usedb\">";
$hiddentext .= "<input type=\"hidden\" value=\"$usetable\" name=\"usetable\">";
$hiddentext .= "<input type=\"hidden\" value=\"$ascdec\" name=\"ascdec\">";
$hiddentext .= "<input type=\"hidden\" value=\"$fldselect\" name=\"fldselect\">";
$hiddentext .= "<input type=\"hidden\" value=\"$orderby\" name=\"orderby\">";
$hiddentext .= "<input type=\"hidden\" value=\"$fldeditconst\" name=\"fldeditconst\">";
$hiddentext .= "<input type=\"hidden\" value=\"$editfldno\" name=\"editfldno\">";

$hiddentext .= "<input type=\"hidden\" size=\"3\" value=\"$where\" name=\"where\">";
$hiddentext .= "<input type=\"hidden\" value=\"$orderby\" name=\"orderby\">";
$hiddentext .= "<input type=\"hidden\" size=\"3\" value=\"$MaxRows\" name=\"MaxRows\">";

//orderby=$sortby;

$frmhead = ""; //<td></td>
$frmhead .= "<td>";
$frmhead .= "<form action=\"$PHP_SELF\" method=\"post\">";
$frmtail = "</form></td>";

$caption = "n";
$tmp = "";
echo "<table border=\"0\">";
echo "<tbody>";
    echo "<tr>";
      echo $frmhead;
      echo $hiddentext;
          echo "<input type=\"submit\" value=\"&lt;&lt; Begin\" name=\"navig\">";
          echo "<input type=\"hidden\" value=\"begin\" name=\"begin\">";
      echo $frmtail;
      
      echo $frmhead;
      echo $hiddentext;
          $poffset = $offset - $MaxRows;
          $poffset = chkvalid($poffset);
          if ($caption == "y") {
          		$tmp = " ($poffset)";
          	}
          echo "<input type=\"hidden\" size=\"3\" value=\"$poffset\" name=\"poffset\">";
          echo "<input type=\"submit\" value=\"&lt; Previous$tmp\" name=\"navig\">&nbsp;";
          echo "<input type=\"hidden\" value=\"prev\" name=\"prev\">";
      echo $frmtail;  

      echo $frmhead;
      echo $hiddentext;
          $coffset = $offset + $MaxRows;
          $coffset = chkmax($coffset,$MaxRows,$norcd);
          	echo "<input type=\"submit\" value=\"Show&nbsp;:\" name=\"navig\">&nbsp;";
          	echo "<input type=\"text\" size=\"3\" value=\"$MaxRows\" name=\"MaxRows\"> rows starting from ";
			echo "<input type=\"text\" size=\"3\" value=\"$coffset\" name=\"coffset\">"; 
         	echo "<input type=\"hidden\" value=\"show\" name=\"show\">";
      echo $frmtail;
      
      echo $frmhead;
      echo $hiddentext;
          $noffset = $offset + $MaxRows;
          $noffset = chkmax($coffset,$MaxRows,$norcd);
          if ($caption == "y") {
          		$tmp = " ($noffset)";
          	}
          echo "<input type=\"hidden\" size=\"3\" value=\"$noffset\" name=\"noffset\">";
          echo "<input type=\"submit\" value=\"&gt; Next$tmp\" name=\"navig\">";
          echo "<input type=\"hidden\" value=\"next\" name=\"next\">";
      echo $frmtail;
      
      echo $frmhead;
      echo $hiddentext;
          echo "<input type=\"submit\" value=\"&gt;&gt; End\" name=\"navig\">";
          echo "<input type=\"hidden\" value=\"end\" name=\"end\">";
      echo $frmtail;

if ($caption == "y") {
   $tmp = " ($offset)";
}
if ($part) {
      echo $frmhead;
      echo $hiddentext;
      echo $hiddenmax;
          echo "<input type=\"submit\" value=\"Full Texts$tmp\" name=\"navig\">";
          echo "<input type=\"hidden\" value=\"full\" name=\"full\">";
      		echo "<input type=\"hidden\" value=\"$offset\" name=\"ppoffset\">";
      echo $frmtail;
} else {
      echo $frmhead;
      echo $hiddentext;
      echo $hiddenmax;
          echo "<input type=\"submit\" value=\"Part Texts$tmp\" name=\"navig\">";
      		echo "<input type=\"hidden\" value=\"$offset\" name=\"ppoffset\">";
          echo "<input type=\"hidden\" value=\"part\" name=\"part\">";
      echo $frmtail;
}
    echo "</tr>";
  echo "</tbody>";
echo "</table>";
?>
</html>
