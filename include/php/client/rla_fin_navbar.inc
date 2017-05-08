<?php
	#include("rla_fin_navbar.inc");
	$userstrbegin	=	"?".base64_encode($userinfo."&ordoffset=0");
	$userstrbegin = "<a href=\"".$PHP_SELF."$userstrbegin\"><font size=2><< Begin|</font></a>";

	$nxtbat = 	$ordoffset + $norcdtofind;
	$userstrnext	=	"?".base64_encode($userinfo."&ordoffset=$nxtbat");
	$prebat= 	$ordoffset - $norcdtofind;
	$userstrprev	=	"?".base64_encode($userinfo."&ordoffset=$prebat");

	$userstrprev = "<a href=\"".$PHP_SELF."$userstrprev\"><font size=2>< Previous $norcdtofind</font></a>";
	$userstrnext = "<a href=\"".$PHP_SELF."$userstrnext\"><font size=2>> Next $norcdtofind</font></a>";

	$endbat = 	$totalno - $norcdtofind;
	$userstrend	=	"?".base64_encode($userinfo."&ordoffset=$endbat");
	$userstrend = "<a href=\"".$PHP_SELF."$userstrend\"><font size=2>>> End</font></a>";

	if ($ordoffset == 0) {
		$navbar = "$userstrnext";
	} elseif ($totalno == $ordoffset + $no) {
		$navbar = "$userstrprev";
	} else {
		$navbar = "$userstrprev || $userstrnext";
	}
	$navbar = "<b>|| $userstrbegin || $navbar || $userstrend ||</b>";
?>