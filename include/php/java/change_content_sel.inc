<html>
<script language=javascript>
var text;
var e, i, j, k, t, c, n, r, se, xx, realcode;
function code_sel_des(str) {
	e = document.codeSelectionForm.elements;
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
	changeText();
}

function checkbox_count() {
	nocodeselected();
	window.status = text + " code(s) selected.";
	changeText();
}

function nocodeselected() {
	e = document.codeSelectionForm.elements;
	if (e.length) {
		text = 0;
		for (i=0; i<e.length; i++) {
			t	=	e[i].type;
			c	=	e[i].checked;
			//window.alert(e[i].name + ", " + t + " " + c);			
			if (t == "checkbox") {
				if (c == true) {
					text++;
				}
			}
		}
	}
}

function changeText() {
	text = "<b>Number of project codes selected is  <font color=#ff0000><b>" + text + "</font>.</b>";
	obtochange.innerHTML	=	text;
}

function onSubmit() {
	nocodeselected();
	if (text == 1) {
		return true;
	} else if (text > 1) {
		return true;
	} else {
		window.alert("No project code has been selected.\nPlease select project codes then submit again.");
		return false;
	}
}
</script>
</html>
