<html>
<head>
<style>
	.outline {cursor: hand}
</style>
</head>

<script language=javascript>
//rr=""+(navigator.appVersion.indexOf("Win")>-1?"\r\n":"\n");
interval = new Array();

groupcode = new Array();
rlastaffe = new Array();
rlastafff = new Array();
periodtime = new Array();
var codectr, enamectr, ptimectr;
rowlett = new Array();

function lcletter() {
var LC ="abcdefghijklmnopqrstuvwxyz";
var ls, ctr, str;
	ls = LC;
 	ctr = 0;
 	str = "";
	while (ls != ""){
 		rowlett[ctr]=ls.substring(0,1);
 		str = str + ctr + ": " + rowlett[ctr] + "\n";
  		ls=ls.substring(1,ls.length);
  		ctr++;
  	}
	//window.alert(str);
}

function varparse() {
var ls, ctr, str, pos, tmp;
	lcletter();
	//rlastaffe
	ls = document.tsoutline.staffename.value;
	//window.alert(ls);
	enamestr = ls;
 	ctr = 0;
	while (ls.indexOf("@")>-1){
  		pos=ls.indexOf("@");
  		tmp = ls.substring(0,pos);
  		if (tmp) {
 			rlastaffe[ctr]=tmp;
   			ctr++;
   		}
 		ls=ls.substring(pos+1,ls.length);
  	}
  	enamectr = ctr;
  	 
	//rlastafff
	ls = document.tsoutline.stafffname.value;
 	//window.alert(ls);
	ctr = 0;
 	str = "";
	while (ls.indexOf("@")>-1){
  		pos=ls.indexOf("@");
  		tmp = ls.substring(0,pos);
  		if (tmp) {
 			rlastafff[ctr]=tmp;
   			ctr++;
   		}
  		ls=ls.substring(pos+1,ls.length);
  	}
  	
  	for (i=1; i<enamectr; i++) {
  		str = str + i + ": " + rlastaffe[i] + ": " + rlastafff[i] + "\n";
  	}
  	//window.alert(str);
  	
	//groupcode
	ls = document.tsoutline.catpluscode.value;
	codestr = ls;
	//window.alert(ls);
 	ctr = 0;
 	str = "";
	while (ls.indexOf("@")>-1){
  		pos=ls.indexOf("@");
  		tmp = ls.substring(0,pos);
  		if (tmp) {
 			groupcode[ctr]=tmp;
  			str = str + ctr + ": " + tmp + "\n";
   			ctr++;
   		}
  		ls=ls.substring(pos+1,ls.length);
  	}
	codectr = ctr;
  	//window.alert(codectr + "\n\n" + str);
  	
	//periodtime
	ls = document.tsoutline.timeperiod.value;
	ptimsctr = ls;
	//window.alert(ls);
 	ctr = 0;
 	str = "";
	while (ls.indexOf("@")>-1){
  		pos=ls.indexOf("@");
  		tmp = ls.substring(0,pos);
  		if (tmp) {
 			periodtime[ctr]=tmp;
  			str = str + ctr + ": " + tmp + "\n";
   			ctr++;
   		}
  		ls=ls.substring(pos+1,ls.length);
  	}
	ptimectr = ctr;
  	//window.alert(ptimectr + "\n\n" + str);
}

function perioddef(table) {
	if (table == "") {
		interval[1] = "yearly";
		interval[2] = "half yearly";
		interval[3] = "quarterly";
		interval[4] = "monthly";
		interval[5] = "weekly";
	} else if (table == "") {
		interval[1] = "project group";
		interval[2] = "detailed project";
	} else if (table == "") {
	
	} else if (table == "") {
	
	}
}

function mtabcollective(source) {
var target, i, j, disp;
var msg, cursrc, ptype, stype;
	msg = "\nsource = " + source + 
		"\ncodectr = " + codectr + 
		"\nenamectr = " + enamectr + 
		"\nptimectr = " + ptimectr + 
		"\ncodestr = " + codestr + 
		"\nenamestr = " + enamestr + 
		"\nptimsctr = " + ptimsctr;
	//window.alert(msg);
	if (source.substring(0, 1) == "c") {
		cols = 1;
		stype = document.showpd.period.value;
		//window.alert("show type\t" + stype);
		cole = parseInt(eval(ptimectr) - 1);
		for (i=cols; i<=cole; i++) {
			ptype = periodtime[i];
			if (ptype == stype) {
				disp = "";
			} else {
				disp = "none";
			}
			for (j=0; j<=cole ; j++) {
				cursrc = source + rowlett[j]+ i.toString();
				target = document.all(cursrc);
				//window.alert("\n cursrc\t" + cursrc + "\n target\t" + target);
				if (target != null) {
					target.style.display = disp;
				}
			}
		}
	}

	if (source.substring(0, 1) == "r") {
		cursrc = source + 2;
		target = document.all(cursrc);
		msg = "\n cursrc = " + cursrc + "\n target = " + target +
			"\n target.style.display = " + target.style.display;
		//window.alert(msg);
		if (target != null) {
			if (target.style.display == "") {
				disp = "none";
			} else {
				disp = "";
			}
		} else {
			return;
		}
		//window.alert('disp =' + disp);

		//for (i=1; i<5; i++) {
		for (i=1; i<parseInt(codectr,10); i++) {
			if (groupcode[i] == "C") {
				cursrc = source + i.toString();
				target = document.all(cursrc);
				//window.alert("\n cursrc = " + cursrc + "\n target = " + target);
				if (target != null) {
					target.style.display = disp;
				}
			}
		}
	}
}

function mtabolclick() {
var source, i, j, k, len, rows, rowe, tab;
var cols, cole, ptype, nexttype, curtype;
	window.status="Timesheet Table Expand/Contract Is Working...";
	varparse();
	source = window.event.srcElement;
	//window.alert("source 1\t\t" + source + "\nsource.id\t\t" + 
		//source.id + "\nsource.innerText\t"  + source.innerText);
	if (source.id.length == 4) {
		mtabcollective(source.id);
		return true;
	}
	//window.alert("source 1\t\t" + source + "\nsource.id\t\t" + 
	//	source.id + "\nsource.innerText\t"  + source.innerText);
	/*
	groupcode = new Array(); codectr
	rlastaffe = new Array(); enamectr
	rlastafff = new Array(); enamectr
	periodtime = new Array(); ptimectr
	var codestr, enamestr, ptimsctr;
	*/
	if (source.id.substring(0, 1) == "c") {
		tab = source.id.substring(0, 4);
		//window.alert("source3:\t\t" + source + "\nsource.id:\t\t" + source.id + "\nsource.innerText:\t"  + source.innerText);
		len = source.id.length;
		cols = parseInt(eval(source.id.substring(4, len)));
		ptype = periodtime[cols];
		//window.alert("Column period type: " + ptype);
		cols++;
		cole = parseInt(eval(ptimectr) - 1);
		cursrc = tab + rowlett[0] + cols.toString();
		target = document.all(cursrc);
		/*
		msg = "\n cursrc\t\t" + cursrc + "\n target\t\t" + target +
			"\n target.style.display\t" + target.style.display;
		window.alert(msg);
		*/
		if (target != null) {
			if (target.style.display == "") {
				disp = "none";
			} else {
				disp = "";
			}
		} else {
			return;
		}
		
		k = 2;
		//window.alert('disp =' + disp);
		if (disp == "none") {
			nexttype = 0;
			curtype = ptype;
			for (i=cols; i<=cole; i++) {
				ptype = periodtime[i];
				if (ptype == curtype) {
					nexttype++;
					if (nexttype >1) {
						break;
					}
				} else {
					for (j=0; j<=k; j++) {
						cursrc = tab + rowlett[j]+ i.toString();
						target = document.all(cursrc);
						//window.alert("\n cursrc\t" + cursrc + "\n target\t" + target);
						if (target != null) {
							target.style.display = disp;
						}
					}
				}
			}
		}

		if (disp == "") {
			if (ptype == "y") {
				nexttype = "hy";
			} else if (ptype == "hy") {
				nexttype = "q";
			} else if (ptype == "q") {
				nexttype = "m";
			} else if (ptype == "m") {
				nexttype = "w";
			} else {
				return;
			}
			curtype = ptype;
			for (i=cols; i<=cole; i++) {
				ptype = periodtime[i];
				if (ptype == curtype) {
					break;
				} else if (ptype == nexttype) {
					for (j=0; j<=k; j++) {
						cursrc = tab + rowlett[j] + i.toString();
						target = document.all(cursrc);
						//window.alert("\n cursrc\t" + cursrc + "\n target\t" + target);
						if (target != null) {
							target.style.display = disp;
						}
					}
				}
			}
		}
	} else if (source.id.substring(0, 1) == "r") {
		tab = source.id.substring(0, 4);
		//window.alert("source3: " + source + "\nsource.id: " + source.id + "\nsource.innerText:"  + source.innerText);
		len = source.id.length;
		rows = eval(source.id.substring(4, len)) + 1;
		rowe = parseInt(codectr,10) -1;
		for (i=rows; i<parseInt(codectr,10); i++) {
			if (groupcode[i] == "G") {
				rowe = i;
				rowe--;
				break;
			}
		}
		//window.alert(rows + " to " + rowe);
		
		cursrc = tab + rows.toString();
		target = document.all(cursrc);
		msg = "\n cursrc = " + cursrc + "\n target = " + target;
			//"\n target.style.display = " + target.style.display;
		//window.alert(msg);
		if (target != null) {
			if (target.style.display == "") {
				disp = "none";
			} else {
				disp = "";
			}
		} else {
			return;
		}
		//window.alert('disp =' + disp);
		for (i=rows; i<=rowe; i++) {
			for (j=0; j<=parseInt(enamectr,10); j++) {
				cursrc = tab + i.toString();
				target = document.all(cursrc);
				//window.alert("\n cursrc = " + cursrc + "\n target = " + target);
				if (target != null) {
					target.style.display = disp;
				}
			}
		}
	}
	window.event.cancelBubble = true;
	window.status="Timesheet Table Expand/Contract Completed.";
}
 
function mtabexpand(level) {
var i, target;
	i = 1;
	target = document.all(level + zerofill(i));
	while (target != null) {
		//window.alert(target.id);
		target.style.display = "";
		i++;
		target = document.all(level + zerofill(i));
	}
}

function mtabcollapse(level) {
var i, target;
	i = 1;
	target = document.all(level + zerofill(i));
	if (target == null) {
		return false;
	} else {
		i = 1;
		target = document.all(level + zerofill(i));
		while (target != null) {
			mtabcollapse(target.id);
			target.style.display = "none";
			i++;
			target = document.all(level + zerofill(i));
		}
	}
}

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


function zerofill(no) {
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
</script>
</html>

