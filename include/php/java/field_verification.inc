<html>
<script language=javascript>
var name, value;
var ls, pos, ctr, str;
data_list = new Array();
codear = new Array();
lettU = new Array();
lettL = new Array();
var UC ="ABCDEFGHIJKLMNOPQRSTUVWXYZ";
var LC ="abcdefghijklmnopqrstuvwxyz";
var llist, ulist;

	ls = UC;
 	ctr = 0;
 	str = "";
	while (ls != ""){
 		lettU[ctr]=ls.substring(0,1);
 		str = str + ctr + ": " + lettU[ctr] + "\n";
  		ls=ls.substring(1,ls.length);
  		ctr++;
  	}
  	ulist = str;
	//window.alert(str);

	ls = LC;
 	ctr = 0;
 	str = "";
	while (ls != ""){
 		lettL[ctr]=ls.substring(0,1);
 		str = str + ctr + ": " + lettL[ctr] + "\n";
  		ls=ls.substring(1,ls.length);
  		ctr++;
  	}
  	llist = str;

function projcodeveri(type) {
	if (type == "date") {
		rval = field_verification();
		value = document.myform.problem.value;
		//window.alert(value);
		if (rval == -1 && value == "n") {
			document.myform.problem.value = "y";
		}
	}
	//window.alert(document.myform.problem.value);
}

function chkprojfield() {
	msg = "";
	value = document.myform.brief_code.value;
	getdataset();
	find=0;
	for (i=0; i<ctr; i++) {
		k=data_list[i].length;
		if (value.substring(0, k) == data_list[i]) {
			find = 1;
			break;
		}
	}
	if (find == 0) {
		msg = "\"" + value + "\" is not belong to any predefined project groups. Please use upper case.\n";
		msg = msg + "\nIf this is belong to a new project group please enter this in table\n";
		msg = msg + "\"CODE PREFIX\".";
	}	

	value = document.myform.begin_date.value;
	rval = chkdate(value);
	if (rval == -1) {
		msg = msg + "\nPlease chech begin date form to yyyy-mm-dd.";
	}
	
	value = document.myform.end_date.value;
	rval = chkdate(value);
	if (rval == -1) {
		msg = msg + "\nPlease chech end date form to yyyy-mm-dd.";
	}
	if (msg) {
		window.alert(msg);
		return false;	
	}
	return true;	
}

function selectcodecheck() {
var target, srccode, srcgroup, codeval, grpval;
//return;
	/*
	name =  window.event.srcElement.name;
	value = window.event.srcElement.value;
	*/
	value = document.fldform.leader.value;
	//window.alert("You clicked me! Leader is " + value + ".");
	e = document.fldform.elements;
	for (j=0; j<e.length; j++) {
		if (e[j].type == "checkbox") {
			e[j].checked = false;
		}
	}
	//window.alert(e.length);
	srccode = value + "code";
	srcgroup = value + "group";
	codeval = "";
	//window.alert(srccode + "-" + srcgroup );
	for (i=0; i<e.length; i++) {
		if (e[i].name == srccode) {
			codeval = e[i].value;
			break;
		}
	}
	grpval = "";
	for (i=0; i<e.length; i++) {
		if (e[i].name == srcgroup ) {
			grpval = e[i].value;
			break;
		}
	}
	//window.alert(codeval + "-" + grpval);
 	
 	ctr = 0;
	if (codeval != "") {
		ls = codeval;
		//window.alert(ls);
 		str = "";
		while (ls.indexOf("@")>-1){
  			pos=ls.indexOf("@");
  			target = ls.substring(0,pos);
  			if (target) {
 				codear[ctr]=target;
 				str = str + ctr + ": " + codear[ctr] + "\n";
  				ctr++;
  			}
  			ls=ls.substring(pos+1,ls.length);
  		}
		//window.alert(str);
	}

	for (i=0; i<ctr; i++) {
		value = codear[i];
		for (j=0; j<e.length; j++) {
			if (e[j].type == "checkbox" && value == e[j].value) {
				e[j].checked = true;
				break;
			}
		}
	}
	//<input name="codeind2" type="checkbox" value="AIP-IP-Patent_Applications">

 	ctr = 0;
	if (grpval != "") {
		ls = grpval;
		//window.alert(ls);
 		str = "";
		while (ls.indexOf("@")>-1){
  			pos=ls.indexOf("@");
  			target = ls.substring(0,pos);
  			if (target) {
 				codear[ctr]=target;
 				str = str + ctr + ": " + codear[ctr] + "\n";
  				ctr++;
  			}
  			ls=ls.substring(pos+1,ls.length);
  		}
		//window.alert(str);
	}
	for (i=0; i<ctr; i++) {
		value = codear[i];
		for (j=0; j<e.length; j++) {
			if (e[j].type == "checkbox" && value == e[j].value) {
				e[j].checked = true;
				break;
			}
		}
	}
	//window.alert(grpval);
	//<input name="codegrp6" type="checkbox" value="TJ-CORE">
}

function subconfirm(mantablename) {
var name, fld, value, val1;
	val1 = "";
	if (mantablename == "projleader") {
		return true;
	}
	if (mantablename == "pub_holidays") {
		name =  "NAME";
		value =  document.fldform.name.value;
	}
	if (mantablename == "country") {
		name =  "Country";
		value =  document.fldform.country.value;
	}
	if (mantablename == "company") {
		name =  "Company";
		value =  document.fldform.company_name.value;
		value = trim(value);
		val1 =  document.fldform.country.value;
		val1 = trim(val1);

		if (val1 == "" && value == "") {
			window.alert("Company and country fields can't be empty, submit is aborted.");
			return false;
		} else if (value == "") {
			window.alert(name + " field can't be empty, submit is aborted.");
			return false;
		} else if (val1 == "") {
			window.alert("Country field can't be empty, submit is aborted.");
			return false;
		}
	}
	if (mantablename == "chargecode") {
		name =  "Charging Code";
		value =  document.fldform.charging_code.value;
	}
	if (mantablename == "code_prefix") {
		name =  "Code Prefix";
		value =  document.fldform.code_prefix.value;
	}
	if (mantablename == "employee") {
		name =  "Email Name";
		value =  document.fldform.email_name1.value;
	}
	if (mantablename == "projcodes") {
		name =  "Brief Code";
		value =  document.fldform.brief_code.value;
	}
	value = trim(value);
	if (value == "") {
		window.alert(name + " field can't be empty, submit is aborted.");
		return false;
	}
	return confirm("Do you really want to submit?");
}

function auto_fill() {
	document.fldform.brief_code.value = window.event.srcElement.value + "-";
}

function lctouc(str,lu) {
var i, s1;
var newstr = "";
	if (str == "") {
		return str;
	}
	while (str != "") {
		s1 = str.substring(0,1);
		for (i=0; i<26; i++) {
			if (lu == "aA") {
				if (s1 == lettL[i]) {
					s1 = lettU[i];
					break;
				}
			} else if (lu == "Aa") {
				if (s1 == lettU[i]) {
					s1 = lettL[i];
					break;
				}
			}
		}
		newstr = newstr + s1;
		str = str.substring(1,str.length);
	}
	return newstr;
}

function trim(str) {
var newstr = "", vlen, i, s1;
	if (str == "") {
		return str;
	}
	vlen = str.length;
// remove leading space
	for (i=0; i<vlen; i++) {
		s1 = str.substring(i,i+1);
		if (s1 != " ") {
			break;
		}
	}
	str = str.substring(i,str.length);
	if (str == "") {
		return str;
	}
	//window.alert("L" + str + "T");

// remove trailing space
	vlen = str.length;
	for (i=vlen; i>=0; i--) {
		s1 = str.substring(i-1,i);
		if (s1 != " ") {
			break;
		}
	}
	str = str.substring(0,i);
	//window.alert("L" + str + "T");
	return str;
}

function auto_check(mantablename) {
var name, fld;
// source name
	name =  window.event.srcElement.name;
	fld = "name@country@company_name@charging_code@code_prefix@email_name1@brief_code";
	if (fld.indexOf(name) < 0) {
		return true;
	}
	//window.alert(name + "\n" + fld.indexOf(name));

//	source value
	value =  window.event.srcElement.value;
	value = trim(value);
	if (value == "") {
		return true;
	}
	value = lctouc(value,"aA");

// parse data
	ls = document.fldform.rcdarray.value;
 	ctr = 0;
 	str = "";
	while (ls.indexOf("~")>-1){
  		pos=ls.indexOf("~");
 		data_list[ctr]=ls.substring(0,pos);
 		str = str + ctr + ": " + data_list[ctr] + "\n";
  		ls=ls.substring(pos+1,ls.length);
  		ctr++;
  	}
	for (i=0; i<ctr; i++) {
		if (value == data_list[i]) {
			window.alert(value + " is existed in table \"" + lctouc(mantablename,"aA") + "\".");
			window.event.srcElement.value = "";
			break;
		}
		if (mantablename == "employee") {
			value = lctouc(value,"Aa");
		}
		window.event.srcElement.value = value;
	}
	return true;
}

function field_verification(type) {
	if (type == "") {
		return true;
	}
	//date int
	name	=	window.event.srcElement.name;
	value	=	window.event.srcElement.value;
	rval = chkdate(value);
	if (rval == -1) {
		window.alert("Please chech date form to yyyy-mm-dd.")
		return -1;
	}
	return true;
}

function projectcode() {
var j, k, find;
	getdataset();
	value	=	window.event.srcElement.value;
	if (value == "") {
		return true;
	}
	find=0;
	for (i=0; i<ctr; i++) {
		k=data_list[i].length;
		if (value.substring(0, k) == data_list[i]) {
			find = 1;
			break;
		}
	}
	//window.alert(value + ": " + find);
	if (find == 0) {
		j = "\"" + value + "\" is not belong to any predefined project groups. Please use upper case.\n";
		j = j + "\nIf this is belong to a new project group please enter this in table\n";
		j = j + "\"CODE PREFIX\".";
		window.alert(j);
		//window.event.srcElement.value = lctouc(value, "aA");
		return -1;
	}
}

function getdataset() {
var t;
	ls = document.myform.projgrp.value;
	//ls = ls.toUpperCase;
 	ctr = 0;
 	str = "";
	while (ls.indexOf("~")>-1){
  		pos=ls.indexOf("~");
 		data_list[ctr]=ls.substring(0,pos);
 		str = str + ctr + ": " + data_list[ctr] + "\n";
  		ls=ls.substring(pos+1,ls.length);
  		ctr++;
  	}
	//window.alert(str);
}

function code_sel_des(str) {
	e = document.fldform.elements;
	if (str == "clear") {
		for (j=0; j<e.length; j++) {
			if (e[j].type == "checkbox") {
				e[j].checked = false;
			}
		}
	} else {
		for (j=0; j<e.length; j++) {
			if (e[j].type == "checkbox") {
				e[j].checked = true;
			}
		}
	}
}

</script>
</html>
