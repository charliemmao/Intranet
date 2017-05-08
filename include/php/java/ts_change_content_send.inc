<html>
<script language=javascript>
var text, sumtime, epy0, val, len;
var e, i, j, k, t, c, n, r, se, xx, realcode, ii;
var msg;

function tsconfirm() {
	addtime();
	if (msg != "") {
		window.alert(msg + "\n\nSubmit has been aborted.");
		return false;
	}
	if (obtochange.innerText	==	"Time on this sheet is 0 (-2280).") {
		window.alert("Time on this form is zero, submit has been aborted.");
		return false;
	}
  	return confirm("Do you really want to submit this timesheet?\n\nPlease confirm.");
}

function addtime() {
	msg = "";
	e = document.TSComposeForm.elements;
	text = 0;
	if (e.length) {
		text = 0;
		for (i=5; i<e.length-1; i++) {
			n	=	e[i].name
			t	=	e[i].type;
			len = n.length;
			v	=	e[i].value;
			if (t == "text" && v != "") {
				//window.alert("No: " + i + ", " + n + ", " + t + ", " + len + ", " + v);
				if (n.substring(0, 7) != "special") {
					d15	=	n.substring(0, 3);
					xx	= n.substring(n.length-4, n.length);
					//alert(xx);
					if (isNaN(v)) {
						msg = msg + v + " is not a number.\n";
					} else if (parseInt(v)<0) {
						msg = msg + v + " is a negative number.\n";
					} else {
						//find name
						if (d15 == "d15") {
							realcode	= n.substring(3, n.length);
						}
 						if (xx == "text") {
 							realcode	= n.substring(0, n.length-5);
 						}
 						if (xx == "date") {
 							realcode	= n.substring(0, n.length-6);
 						}
 						if (xx == "_tcc") {
 							realcode	= n.substring(0, n.length-4);
 						}
 						if (d15 == "d15") {
							k	= 	parseInt(eval(v/15));
							j	=	eval(v - k*15);
							if (j != "0") {
								msg = msg + n + ":\t" + v + " is not divisible by 15.\n";
							}
						}
						text	=	parseInt(text) + parseInt(v);
					}
				}
			}
		}
	}
	timedisplay();
}

function timedisplay() {
var stavar;
	if (text != "2280") {
		i	=	eval(text-2280);
		if (parseInt(i) < 0) {
			stavar ="total: " + text + " minutes (" + i + " minutes short.)";
			text	=	"<font color=#ff0000>" + text + " (" + i + ")</font>";
		} else if (parseInt(i) > 0) {
			stavar ="total: " + text + " minutes (" + i + " minutes over.)";
			text	=	"<font color=#008080>" + text + " (" + i + ")</font>";
		}
	} else {
		stavar ="total: " + text + " minutes.";
		text	=	text;
	}
	window.status = stavar;
	changeHTMLText();
}

function timesheetload() {
	text = document.TSComposeForm.elements[3].value;
	//window.alert(text);
	if (text == "Load Timesheet") {
		return true;
	} else {
		//window.alert("Please change \"Type of Action\" to \"Load Timesheet\" enable you load timesheet.");
		return false;
	}
}

function tccsum(specialobject, obid1, obid2) {
	sumtime = 0;
	for (ii=parseInt(obid1); ii<parseInt(obid2); ii++) {
		n = document.TSComposeForm.elements[ii].name;
		if (n.substring(n.length-4, n.length) == "time") {
			isnum(document.TSComposeForm.elements[ii].value);
		}
	}
	ii = parseInt(obid1) - 1;
	val = document.TSComposeForm.elements[ii].value;
	//window.alert(isNaN(val ));
	n = document.TSComposeForm.elements[ii].name;
	d15	=	n.substring(0, 3);
	if (d15 == "d15") {
		n	= n.substring(3, n.length);
	}
	xx	= n.substring(n.length-4, n.length);
	if (xx == "_tcc") {
		n	= n.substring(0, n.length-4);
	}

	if (val != "") {
		if (val != "") {
			emptycheck(val);
		}
		if (epy0 == "y") {
			if (parseInt(sumtime) != 0) {
				window.alert("Please check " + n + "'s value.");
			}
		}
	} else {
		if (parseInt(sumtime) != 0) {
			window.alert("Please check " + n + "'s value.");
		}
	}
	if (parseInt(sumtime) == 0) {
		sumtime = " ";
	}
	if (specialobject == "tcc1") {
		tcc1.innerText = sumtime;
	} else if (specialobject == "tcc2") {
		tcc2.innerText = sumtime;
	} else if (specialobject == "tcc3") {
		tcc3.innerText = sumtime;
	} else if (specialobject == "tcc4") {
		tcc4.innerText = sumtime;
	} else if (specialobject == "tcc5") {
		tcc5.innerText = sumtime;
	} else if (specialobject == "tcc6") {
		tcc6.innerText = sumtime;
	} else if (specialobject == "tcc7") {
		tcc7.innerText = sumtime;
	} else if (specialobject == "tcc8") {
		tcc8.innerText = sumtime;
	} else if (specialobject == "tcc9") {
		tcc9.innerText = sumtime;
	} else if (specialobject == "tcc10") {
		tcc10.innerText = sumtime;
	} else if (specialobject == "tcc11") {
		tcc11.innerText = sumtime;
	} else if (specialobject == "tcc12") {
		tcc12.innerText = sumtime;
	} else if (specialobject == "tcc13") {
		tcc13.innerText = sumtime;
	} else if (specialobject == "tcc14") {
		tcc14.innerText = sumtime;
	} else if (specialobject == "tcc15") {
		tcc15.innerText = sumtime;
	} else if (specialobject == "tcc16") {
		tcc16.innerText = sumtime;
	} else if (specialobject == "tcc17") {
		tcc17.innerText = sumtime;
	} else if (specialobject == "tcc18") {
		tcc18.innerText = sumtime;
	} else if (specialobject == "tcc19") {
		tcc19.innerText = sumtime;
	} else if (specialobject == "tcc20") {
		tcc20.innerText = sumtime;
	}
}

function leavesum($name, obid1) {
	sumtime = 0;
	if ($name == "annual") {
		//window.alert(document.TSComposeForm.special_annual1.value);
		isnum(document.TSComposeForm.special_annual1.value);
		isnum(document.TSComposeForm.special_annual2.value);
		isnum(document.TSComposeForm.special_annual3.value);
		isnum(document.TSComposeForm.special_annual4.value);
		isnum(document.TSComposeForm.special_annual5.value);
		if (parseInt(sumtime) == 0) {
			sumtime = " ";
		}
		annual.innerText = sumtime;
		
	} else if ($name == "sick") {
		isnum(document.TSComposeForm.special_sick1.value);
		isnum(document.TSComposeForm.special_sick2.value);
		isnum(document.TSComposeForm.special_sick3.value);
		isnum(document.TSComposeForm.special_sick4.value);
		isnum(document.TSComposeForm.special_sick5.value);
		if (parseInt(sumtime) == 0) {
			sumtime = " ";
		}
		sick.innerText = sumtime;
	} else if ($name == "lsl") {
		isnum(document.TSComposeForm.special_lsl1.value);
		isnum(document.TSComposeForm.special_lsl2.value);
		isnum(document.TSComposeForm.special_lsl3.value);
		isnum(document.TSComposeForm.special_lsl4.value);
		isnum(document.TSComposeForm.special_lsl5.value);
		if (parseInt(sumtime) == 0) {
			sumtime = " ";
		}
		lsl.innerText = sumtime;
	}
	//get name
	n = document.TSComposeForm.elements[obid1].name;
	d15	=	n.substring(0, 3);
	if (d15 == "d15") {
		n	= n.substring(3, n.length);
	}
	xx	= n.substring(n.length-6, n.length);
	if (xx == "_adate" || xx == "_sdate" || xx == "_ldate") {
		n	= n.substring(0, n.length-6);
	}
	//check value
	val= document.TSComposeForm.elements[obid1].value;
	if (val != "") {
		if (val != "") {
			emptycheck(val);
		}
		if (epy0 == "y") {
			if (sumtime != " ") {
				window.alert("Please check " + n + "'s value.");
			}
		}
	} else {
		if (sumtime != " ") {
			window.alert("Please check " + n + "'s value.");
		}
	}
}

function isnum(val) {
	if (isNaN(val) == false && val != "") {
		if (val != "") {
			emptycheck(val);
		}
		if (epy0 == "n") {
			sumtime = parseInt(sumtime) + parseInt(val);
			//window.alert("'" + val + "' is " + isNaN(val) + ", " + parseInt(sumtime));
		}
	}
}

function emptycheck(v0) {
var len, str, iempty;
	len = v0.length;
	epy0 = "y";
	if (len != 0) {
		for (iempty=0; iempty<len; iempty++) {
			str 	=	v0.substring(iempty,iempty+1);
			//window.alert("Postion " + iempty + ": " + str);
			if (str != " ") {
				epy0 = "n";	
				break;
			}
		}
	}
}

function ChangeYesNo() {
	//window.alert(send.innerText);
	if (send.innerText == "Do you want to submit? NO.") {
		send.innerHTML = "<b>Do you want to submit? <font color=#0000ff>YES.</font></b>";
	} else {
		send.innerHTML = "<b>Do you want to submit? <font color=#ff0000>NO.</font></b>";
	}
}

function keypress() {
	//window.alert("test");
	if (send.innerText == "Do you want to submit? NO.") {
		window.alert("Please change \"No\" to \"YES\" to enable you send this timesheet.");
		return false;
	} else {
		/*
		if (obtochange.innerText	==	"Time on this sheet is 0.") {
			window.alert("Time on your timesheet is zero.");
			return false;
		} else {
			return true;
		}
		*/
	}
}

function ForOtherAllowed(authrisedperson) {
	se	=	window.event.srcElement.value;
	window.alert("You are not authrised person to prepare time sheet for " + se);
	if (se != authrisedperson) {
		window.alert("You are not authrised person to prepare time sheet for " + se);
	}
}

function timesum() {
var targettext;
	se	=	eval(window.event.srcElement.value);
	if (se == "0") {
		return;
	}
	se	=	window.event.srcElement.name;
	//window.alert(se);
	e = document.TSComposeForm.elements;
	text = 0;
	if (e.length) {
		text = 0;
		for (i=0; i<e.length; i++) {
			t	=	e[i].type;
			c	=	e[i].value;
			if (t == "text" && c != "") {
			n	=	e[i].name;
			if (n.substring(0, 7) != "special") {
				d15	=	n.substring(0, 3);
				xx	= n.substring(n.length-4, n.length);
				//window.alert("object " + i + ". value = " + c);
				if (isNaN(c)) {
					window.alert(c + " is not a number.\nPlease enter integer number only.");
					return;
				} else if (parseInt(c)<0) {
					if (se == n) {
						window.alert(c + " is a negative number.\nPlease enter a positive integer number only.");
						return;
					}
				} else {
					if (d15 == "d15") {
						k	= 	parseInt(eval(c/15));
						j	=	eval(c - k*15);
						if (j != "0") {
							k	=	eval(k*15);
							j	=	eval(k+15);
							r	=	j.toString() + " has been typed for you.";
							if (se == n) {
								window.alert(c + " is not divisible by 15.\n" + r);
								e[i].value = j;
								//e[i].focus(); 
								//e[i].select();
							}
						}
					}
					if (se == n) {
						if (d15 == "d15" && (xx == "text" || xx == "date")) {
							realcode	= n.substring(3, n.length-4);
						} else if (d15=="d15" && (xx != "text" || xx != "date")) {
							realcode	= n.substring(3, n.length);
						} else if (d15 !="d15" && (xx == "text" || xx == "date")) {
							realcode	= n.substring(0, n.length-4);
						} else {
							realcode	= n;
						}
						if (xx == "text") {
							xx	=	"You need to fill the text box for the project code \n\n";
							// + realcode + "\"\n\n" 
							xx	=	xx + "Please enter text which describes the activities undertaken.\n";
							targettext = e[i+1].value;
							if (targettext == "Please enter text which describes the activities undertaken.") {
								window.alert(xx);
								e[i+1].focus(); 
								e[i+1].select();
							}
						}
						if (xx == "_tcc") {
							xx	=	"You need to fill the text box for the project code \n\n";
							// + realcode + "\"\n\n" 
							xx	=	xx + "Please enter minute in the text box, select company and country from the lists.\n";
							window.alert(xx);
							/*
							sumtime = 0;
							for (ii=parseInt(i+1); ii<parseInt(i+20); ii++) {
								len = e[ii].name;
								if (len.substring(len.length-4, len.length) == "time") {
									isnum(e[ii].value);
								}
							}
							if (parseInt(sumtime) == 0 ) {
								window.alert(xx);
							}
							*/
							// Lee's PC doesn't like these codes
						}
						if (xx == "trvl") {
							xx	=	"You need to fill the text box for the project code \n\n";
							// + realcode + "\"\n\n" 
							xx	=	xx + "Please select company from the list, enter minute and activities in the text boxes.\n";
							window.alert(xx);
						}
						if (xx == "date") {
							xx	=	"This project code requires you to fill time to the boxes provided.";							
							sumtime = 0;
							for (ii=parseInt(i+1); ii<parseInt(i+5); ii++) {
								isnum(e[ii].value);
							}
							if (parseInt(sumtime) == 0 ) {
								window.alert(xx);
							}
						}
					}
					text	=	parseInt(text) + parseInt(c);
				}
			}
			}
		}
	}
	timedisplay();
}

function changeHTMLText() {
	//window.status = 	"Time on this sheet is " + text + ".";
	obtochange.innerHTML	=	"<b>Time on this sheet is " + text + ".</b>";
}

function travelnochk(specialobject, obid1, obid2,thisob) {
var i,totime;
	
// if total time is zero return
	i=parseInt(obid1)-1;
	//alert(document.TSComposeForm.elements[i].name);
	totime = document.TSComposeForm.elements[i].value;
	emptycheck(totime );
	if (epy0 == "y") {
		//window.alert("Empty");
		return;
	} else {
		//window.alert(totime);
	}

//number check
	ii = parseInt(thisob);
	n = document.TSComposeForm.elements[ii].name;
	n = n.substring(8, n.length);
	n = n.substring(0, 12);
	val = document.TSComposeForm.elements[ii].value;
	emptycheck(val);

	//alert(n);
	//window.alert(isNaN(val ));

	if (epy0 == "y") {
		return;
	}

	sumtime = 0;
	for (itr=parseInt(obid1); itr<=parseInt(obid2); itr++) {
		n = document.TSComposeForm.elements[itr].name;
		if (n.substring(n.length-4, n.length) == "time") {
			val = document.TSComposeForm.elements[itr].value;
			emptycheck(val);
			if (epy0 == "y") {
				val = 0;
			}
			if (isNaN(val) == true) {
				document.TSComposeForm.elements[ii].value = "";
				val = 0;
			} else if ( eval(val) > 0) {
				//alert(itr + ": " + n + "\n" + val);
				i2 = eval(itr) - 1;
				n = document.TSComposeForm.elements[i2].name ;
				//alert(n+ "\nvalue: " + document.TSComposeForm.elements[i2].value);
				if (document.TSComposeForm.elements[i2].value == "") {
					alert("\n" + "Please select company from the list and enter activity.");
				}
			}
			sumtime = eval(sumtime) + eval(val);
		}
	}
	//alert("total time " + sumtime);
	totime = eval(totime) - eval(sumtime);
	if (totime > 0) {
		sumtime = sumtime + " (<font color=#0000ff>" + totime + "</font>)";
	} else if (totime < 0) {
		sumtime = sumtime + " (<font color=#ff0000>" + totime + "</font>)";
	}
	traveldiv.innerHTML = sumtime;	//innerHTML
}

function travelactivitycheck(totime, obid1, obid2, chkactivity) {
var i,totime;
	
// if total time is zero return
	i=parseInt(obid1)-1;
	//alert(document.TSComposeForm.elements[i].name);
	totime = document.TSComposeForm.elements[i].value;
	emptycheck(totime );
	if (epy0 == "y") {
		//window.alert("Empty");
		return;
	} else {
		//window.alert(totime);
	}
	sumtime = 0;
	for (itr=parseInt(obid1); itr<=parseInt(obid2); itr++) {
		n = document.TSComposeForm.elements[itr].name;
		if (n.substring(n.length-4, n.length) == "time") {
			val = document.TSComposeForm.elements[itr].value;
			emptycheck(val);
			if (epy0 == "y") {
				val = 0;
			}
			if (isNaN(val) == true) {
				document.TSComposeForm.elements[ii].value = "";
				val = 0;
			} else if ( eval(val) > 0) {
				//alert(itr + ": " + n + "\n" + val);
				i2 = eval(itr) - 1;
				n = document.TSComposeForm.elements[i2].name ;
				//alert(n+ "\nvalue: " + document.TSComposeForm.elements[i2].value);
				if (document.TSComposeForm.elements[i2].value == "") {
					alert("\n" + "Please select company from the list and enter activity.");
				}
			}
			sumtime = eval(sumtime) + eval(val);
		}
	}
	//alert("total time " + sumtime + "\nsub time\t" + sumtime);
	timedif = eval(totime) - eval(sumtime);
	if (timedif > 0) {
		sumtime = sumtime + " (<font color=#ff0000>" + timedif + "</font>)";
	} else if (totime < 0) {
		sumtime = sumtime + " (<font color=#0000ff>" + timedif + "</font>)";
	}
	traveldiv.innerHTML = sumtime;	//innerHTML
}
</script>
</html>
