<html>

<script language=javascript>
var HelpX, HelpY, HelpText;
var ToShow, ToClear;

function DoFlyOver() {
	if (ToClear != -1) window.clearTimeout(ToClear);
	
	FOArea.innerText	=	HelpText;
	FOArea.style.posLeft	=	HelpX + 10;
	FOArea.style.posTop	=	HelpY + 20;
	FOArea.style.display	=	"";
	
	ToClear	=	setTimeout("ClearFlyOver()", 4000, "JAVASCRIPT");
}

function ClearFlyOver() {
	FOArea.style.display	=	"none";
}

function FlyOver(Text) {
	HelpText	=	Text;
	HelpX	=	window.event.clientX;
	HelpY	=	window.event.clientY;
	ToShow	=	setTimeout("DoFlyOver()", 100, "JAVASCRIPT");
}

</script>

</html>
