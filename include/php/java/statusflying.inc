<html>

<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
var Message="Feel free to browse. Check out the Quick Links & Reference Sites. E-Mail us if you find a broken link. Ta & Bye :-)";
var place=10;
function scrollIn() {
	window.status=Message.substring(0, place);
	if (place >= Message.length) {
		place=1;
		window.setTimeout("scrollOut()",300); 
	} else {
		place++;
		window.setTimeout("scrollIn()",10); 
   } 
}
function scrollOut() {
	window.status=Message.substring(place, Message.length);
	if (place >= Message.length) {
		place=1;
		window.setTimeout("scrollIn()", 5000);
	} else {
		place++;
		window.setTimeout("scrollOut()", 50);
   }
}
// End -->
</SCRIPT>
</html>

