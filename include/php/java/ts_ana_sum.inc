<html>
<script language="javascript">
var text,  i, j, e;
pp = new Array();
period = new Array();
function showcodeselfieldset() {
	if (window.event.srcElement.value == "y") {
		codeselfieldset .style.display = "";
	} else {
		codeselfieldset .style.display = "none";
	}
}

function code_sel_des(str) {
	//window.alert(str);
	//window.alert(document.periodform.ldrcodelist0.checked);
	
	e = document.periodform.elements;
	if (e.length) {
		text = 0;
		for (i=0; i<e.length; i++) {
			t	=	e[i].type;
			if (t == "checkbox") {
				if (str == 'clear') {
					e[i].checked = false;
				} else {
					e[i].checked = true;
					text++;
				}
			}
		}
	}
	window.status = text + " code(s) selected.";
}

function displayhide() {
	//window.alert("Is code group? " + window.event.srcElement.value);
	//window.alert("Is code group? " + document.periodform.grpcode.value);
	if (window.event.srcElement.value == "Y") {
		//window.alert(window.event.srcElement.value);
		grpcode.style.display = "";
		indcode.style.display = "none";
	} else {
		//window.alert(window.event.srcElement.value);
		grpcode.style.display = "none";
		indcode.style.display = "";
	}
}
	
function timerangecheck() {
	return true;
	window.alert(window.event.srcElement.name);
	e = document.periodform.elements;
	window.alert(window.event.srcElement.name);
	window.alert(e.length);
	
	//window.alert(document.periodform.calyearstart.name);
	window.alert(window.event.srcElement.name);

	period[0][0] = document.periodform.calyearstart.value;
	period[1][0] = document.periodform.calmonthstart.value;
	period[2][0] = document.periodform.caldaystart.value;
	period[0][1] = document.periodform.calyearend.value;
	period[1][1] = document.periodform.calmonthend.value;
	period[2][1] = document.periodform.caldayend.value;
	period[0] = document.periodform.calyearstart.value;
	period[1] = document.periodform.calmonthstart.value;
	period[2] = document.periodform.caldaystart.value;
	period[3] = document.periodform.calyearend.value;
	period[4] = document.periodform.calmonthend.value;
	period[5] = document.periodform.caldayend.value;
	for (i=0; i<2; i++) {
		pp[i] = "";
		for (j=0; j<3; j++) {
			//pp[i] = pp[i] + period[j][i];
		}
		window.alert(pp[i]);
	}
}
</script>
</html>
