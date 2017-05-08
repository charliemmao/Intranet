<html>
<head>
<style>
	.outline {cursor: hand}
</style>
</head>
<script language=javascript>
interval = new Array();
interval[1] = "yearly";
interval[2] = "half yearly";
interval[3] = "quarterly";
interval[4] = "monthly";
interval[5] = "weekly";
interval[6] = "project group";
interval[7] = "detailed project";

function expcol() {
	source = window.event.srcElement;
	if (source.innerText == "Expand") {
		source.innerText = "Collapse";
	} else {
		source.innerText = "Expand";
	}
}

function changeno() {
var txt, i;
	txt = layer.innerText;
	for (i=1; i<8; i++) {
		if (txt == interval[i]) {
			i++;
			break;
		}
	}
	if (i == 8) {
		i = 1;
	}
	layer.innerText = interval[i];
}

function zerofill(no, digits) {
var nolen;
	nolen = eval(digits - no.toString().length);
	return zero_n(nolen) + no.toString();
}

function zero_n(n) {
var zero = "";
	for (i=0; i<n; i++) {
		zero = zero + "0";
	}
	return zero;
}

function tabcollective(source, digits) {
var target, i, j, disp;
var msg, layno, layerctr;
var cursrc, src1, src;
var txt;
	txt = layer.innerText;
	for (i=1; i<8; i++) {
		if (txt == interval[i]) {
			layno = i;
			break;
		}
	}
	if (layno == 6) {
		layno = 1;
	} else if (layno == 7) {
		layno = 2;
	}
	//window.alert("Show up to " + layno + " layer.");
	
	//second layer exist?
	cursrc = source;
	for (i=1; i<=2; i++){
		cursrc = cursrc + zerofill(1, digits);
	}
	target = document.all(cursrc);
	while (target == null) {
		return true;
	}
	//window.alert("cursrc = " + cursrc + ".");
	
	i = 1;
	cursrc = source + zerofill(i, digits); //first layer
	target = document.all(cursrc);
	while (target != null) {
		tabexpand(cursrc, digits);
		//window.alert(cursrc);
		for (layerctr=1; layerctr<layno-1; layerctr++) {// second layer and up
			cursrc = cursrc + zerofill(1, digits);
			tabexpand(cursrc, digits);
		}
		i++;
		cursrc = source + zerofill(i, digits);
		target = document.all(cursrc);
	}
	
	//Collapse all after layno
	j = 1;
	cursrc = source + zerofill(j, digits)
	for (i=1; i<layno; i++){
		cursrc = cursrc + zerofill(1, digits);
	}
	//window.alert(cursrc);
	target = document.all(cursrc);
	while (target != null) {
		//window.alert(cursrc);
		tabcollapse(cursrc, digits);
		j++;
		cursrc = source + zerofill(j, digits)
		for (i=1; i<layno; i++){
			cursrc = cursrc + zerofill(1, digits);
		}
		//window.alert(cursrc);
		target = document.all(cursrc);
	}
}

function tabolclick(digits) {
var source, i;
	source = window.event.srcElement;
	if (source.id.length == 2) {
		tabcollective(source.id, digits);
		return true;
	}

	if (source.className == "outline") {
		i = 1;
		targets = document.all(source.id + zerofill(i, digits));
		if (targets == null) {
			return false;
		} else {
			if (targets.style.display == "none") {
				tabexpand(source.id, digits);
			} else {
				tabcollapse(source.id, digits);
			}
		}
	}
	window.event.cancelBubble = true;
}
 
function tabexpand(level, digits) {
var i, target;
	i = 1;
	target = document.all(level + zerofill(i, digits));
	while (target != null) {
		//window.alert(target.id);
		target.style.display = "";
		i++;
		target = document.all(level + zerofill(i, digits));
	}
}

function tabcollapse(level, digits) {
var i, target;
	i = 1;
	target = document.all(level + zerofill(i, digits));
	if (target == null) {
		return false;
	} else {
		i = 1;
		target = document.all(level + zerofill(i, digits));
		while (target != null) {
			tabcollapse(target.id, digits);
			target.style.display = "none";
			i++;
			target = document.all(level + zerofill(i, digits));
		}
	}
}

function outlineclick() {
var source, targets, i;
	source = window.event.srcElement;
	window.alert("(1)source.className = " + source.className);
	if (source.className == "outline") {
		targets = document.all(source.id).children;
		if (targets.length != 0 && targets[0].style.display == "none") {
			expand(source.id);
		} else {
			collapse(source.id);
		}
	}
	window.event.cancelBubble = true;
}
 
function expand(level) {
var i, target;
	i = 1;
	target = document.all(level + i.toString());
	while (target != null) {
		target.style.display = "";
		i++;
		target = document.all(level + i.toString());
	}
}

function collapse(level) {
var i, target;
	if (document.all(level).children.length == 0) {
		return false;
	} else {
		i = 1;
		target = document.all(level + i.toString());
		while (target != null) {
			collapse(target.id);
			target.style.display = "none";
			i++;
			target = document.all(level + i.toString());
		}
	}
}
</script>
</html>

