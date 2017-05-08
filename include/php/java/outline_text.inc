<html>

<?php
##Overll
	$nodigits = 4;
	include("outline_js.inc");
	include("rla_functions.inc");
	$cell = "cellspacing=\"0\" cellpadding=\"0\"";
	$click = " onClick=\"tabolclick('$nodigits');\" class=\"outline\" ";
	$hide = " style=\"display: none\"";
	$OLstr = "OL";
	$OL = $OLstr;
	$sta = "id=\"$OL\"$click onMouseOver=\"self.status='$OL'\";";
	//echo $sta."<br><br>";
	echo "<table border=0 $cell><tr><th><div $sta>[Click to show]&nbsp;</div></th>
		<th><font color=#0000ff><div id=layer onClick=\"changeno()\">yearly</div></font></th>
		<th>&nbspreport.</th></tr></table><p>";
	
	for ($i=1; $i<5; $i++) {
		$L1ctr = zerofill($i, $nodigits);
		//echo $L1ctr."<br>";
		
##1st layer:1
	echo "<table border=1 $cell>";
	$OL = $OLstr.$L1ctr;
	$sta = "id=\"$OL\"$click onMouseOver=\"self.status='$OL'\";";
	//echo $sta."<br><br>";
	echo "<tr><td $sta>First Layer $L1ctr</td></tr>";
##2nd layer:1-1
	$L2ctr = zerofill(1, $nodigits);
	$OL = $OLstr.$L1ctr.$L2ctr;
	$sta = "id=\"$OL\"$click$sta0'$OL';";
	//echo $sta."<br><br>";
	echo "<tr><td $sta $hide>".space_n(2)."Second Layer $L1ctr-$L2ctr</td></tr>";
##3rd layer:1-1-1
	$L3ctr = zerofill(1, $nodigits);
	$OL = $OLstr.$L1ctr.$L2ctr.$L3ctr;
	$sta = "id=\"$OL\"$click onMouseOver=\"self.status='$OL'\";";
	//echo $sta."<br><br>";
	echo "<tr><td $sta $hide>".space_n(4)."Third Layer $L1ctr-$L2ctr-$L3ctr.</td></tr>";
##4th layer:1-1-1
	$L4ctr = zerofill(1, $nodigits);
	$OL = $OLstr.$L1ctr.$L2ctr.$L3ctr.$L4ctr;
	$sta = "id=\"$OL\"$click onMouseOver=\"self.status='$OL'\";";
	//echo $sta."<br><br>";
	echo "<tr><td $sta $hide>".space_n(6)."Fourth Layer $L1ctr-$L2ctr-$L3ctr-$L4ctr.</td></tr>";
##5th layer:1-1-1-1-1
	$L5ctr = zerofill(1, $nodigits);
	$OL = $OLstr.$L1ctr.$L2ctr.$L3ctr.$L4ctr.$L5ctr;;
	$sta = "id=\"$OL\"$click onMouseOver=\"self.status='$OL'\";";
	//echo $sta."<br><br>";
	echo "<tr><td $sta $hide>
		<table border=0 $cell>
			<tr><td>".space_n(8)."Fifth Layer $L1ctr-$L2ctr-$L3ctr-$L4ctr-$L5ctr.</td></tr>
			<tr><td>".space_n(8)."Fifth Layer $L1ctr-$L2ctr-$L3ctr-$L4ctr-$L5ctr.</td></tr>
			<tr><td>".space_n(8)."Fifth Layer $L1ctr-$L2ctr-$L3ctr-$L4ctr-$L5ctr.</td></tr>
			<tr><td>".space_n(8)."Fifth Layer $L1ctr-$L2ctr-$L3ctr-$L4ctr-$L5ctr.</td></tr>
			<tr><td>".space_n(8)."Fifth Layer $L1ctr-$L2ctr-$L3ctr-$L4ctr-$L5ctr.</td></tr>
		</table>
	</td></tr>";
	}
echo "</table>";
?>
<!--
<div onClick="outlineclick();">
	<ul id="ol" class="outline">
		Outline 1 (Click to expand outline)
		<ul id="OL1" onClick="outlineclick();" class="outline" style="display: none">
			First Outline Item
				<li id="OL11" onClick="outlineclick();"  class="outline" style="display: none" onMouseOver="self.status='OL11';">
					You can nest content as deep as you like within the outline.<br>
				</li>
				<li id="OL12" onClick="outlineclick();"  class="outline" style="display: none" onMouseOver="self.status='OL12';">
					A sub-item underneath the first outline item
				</li>
				<li id="OL13" onClick="outlineclick();"  class="outline" style="display: none" onMouseOver="self.status='OL13';">
					Another sub-item undernearth the first outline item
				</li>
					<ul id="OL131" onClick="outlineclick();"  class="outline" style="display: none" onMouseOver="self.status='OL131';">
						Test 1
					</ul>
		</ul>
		<br>
		Outline 2 (Click to expand outline)
		<ul id="OL2" onClick="outlineclick();" class="outline" style="display: none">
			First Outline Item
				<li id="OL21" onClick="outlineclick();"  class="outline" style="display: none" onMouseOver="self.status='OL11';">
					You can nest content as deep as you like within the outline.<br>
				</li>
				<li id="OL22" onClick="outlineclick();"  class="outline" style="display: none" onMouseOver="self.status='OL12';">
					A sub-item underneath the first outline item
				</li>
				<li id="OL23" onClick="outlineclick();"  class="outline" style="display: none" onMouseOver="self.status='OL13';">
					Another sub-item undernearth the first outline item
				</li>
					<ul id="OL231" onClick="outlineclick();"  class="outline" style="display: none" onMouseOver="self.status='OL131';">
						Test 2
					</ul>
		</ul>
	</ul>
</div>
-->
</html>

