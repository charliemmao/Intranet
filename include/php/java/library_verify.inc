<html>
<script language=javascript>
var e, v0, epy0, v, i, j, k;
var msg, msg0, field;
var fname0, fname1, mname0, mname1, lname0, lname1;
var no_auth, real_no;
var temp;

function confirm_delete_lib_rcd() {
	return confirm("Do you really wnat to delete this library record.");	
}
function uploadverify() {
	if (document.uploadcopyfrm.elec_copy_add.value == "") {
		window.alert("No file has been selected.");
		return false
	} else {
		//window.alert("File title is " + document.uploadcopyfrm.userfile.value + ".");
 	}
}
function bookmodverify() {
	lib_item_general();
	if (msg == "name conflict") {
		return false;
	}
	bookfield();

	if (document.newlibitemfrm.year_cancellation.value == "") {
		msg = msg + "\nYear cancelled field:\tempty.";
	}
	if (document.newlibitemfrm.missing_record.value == "") {
		msg = msg + "\nMissing record field:\tempty.";
	}	
	return validcheck();
}

function bookverify() {
	lib_item_general();
	if (msg == "name conflict") {
		return false;
	}
	bookfield();
	return validcheck();	
} 

function bookfield() {
	//book fields check
	if (document.newlibitemfrm.barcode.value == "") {
		msg = msg + "\nBarcode:\tempty.";
	}
	if (document.newlibitemfrm.dewey.value == "") {
		msg = msg + "\nDEWEY" + ":\tempty.";
 	}
	if (document.newlibitemfrm.isbn.value == "") {
		msg = msg + "\nISBN" + ":\tempty.";
 	}
	if (document.newlibitemfrm.editor.value == "") {
		msg = msg + "\nEditor" + ":\tempty.";
 	}
	if (document.newlibitemfrm.edition.value == "") {
		msg = msg + "\nEdition" + ":\tempty.";
 	}
	if (document.newlibitemfrm.volume.value == "") {
		msg = msg + "\nVolume" + ":\tempty.";
 	}
	if (document.newlibitemfrm.no_pages.value == "") {
		msg = msg + "\nPages" + ":\tempty.";
 	}
	if (document.newlibitemfrm.year_published.value == "") {
		msg = msg + "\nPublishing Year " + ":\tempty.";
 	}
 	v = document.newlibitemfrm.pub_name.value;
 	v0 = document.newlibitemfrm.pub_id.value;
	if (v == "" && v0 == 0) {
		msg = msg + "\nPubliser" + ":\tempty.";
 	} else if (v != "" && v0 > 0) {
		msg = msg + "\nPubliser" + ":\tduplicated entry.";
		window.alert(msg);
		return false;
 	}
	if (document.newlibitemfrm.copyright.value == "") {
		msg = msg + "\nCopyright" + ":\tempty.";
 	}
	if (document.newlibitemfrm.year_in.value == "") {
		msg = msg + "\nYear in field:\tempty.";
	}
}

function validcheck() {
	if (msg == msg0) {
		return true;
	} else {
		//window.alert(msg);
		if (real_no == 0) {
			window.alert("No valid author can be found, submit has been cancelled.");
			return false;
		} else if (document.newlibitemfrm.libtitle.value == "") {
			window.alert("Title is empty, submit has been cancelled.");
			return false;
		} else if (real_no != no_auth) {
			if (real_no == 1) {
				msg = "You intended to have " + no_auth + " authers, but only 1 author's entry is valid.\n\n";
			} else {
				msg = "You intended to have " + no_auth + " authers, but only first \"" + real_no + "\" author's entries are valid.\n\n";
			}
			msg = msg + "\n\nDo you want to submit anyway ?\n"
			return confirm(msg);	
		} else {
			msg = msg + "\n\nDo you want to submit anyway ?\n"
			return confirm(msg);	
		}
	}
}

function patentverify() {
var pat;
	lib_item_general();
	if (msg == "name conflict") {
		return false;
	}
	
	pat = "";
	if (document.newlibitemfrm.country.value == "") {
		pat= pat+ "\n\nCountry" + ":\t\tempty.";
 	}
	if (document.newlibitemfrm.patent_no.value == "") {
		pat= pat+ "\nPatent No" + ":\tempty.";
 	}
	if (document.newlibitemfrm.assignee.value == "") {
		pat= pat+ "\nAssignee" + ":\t\tempty.";
 	}
	if (document.newlibitemfrm.file_date.value == "") {
		pat= pat+ "\nDate Filed" + ":\tempty.";
 	}
	if (document.newlibitemfrm.pub_date.value == "") {
		pat= pat+ "\nDate Published" + ":\tempty.";
 	}
	if (document.newlibitemfrm.patent_date.value == "") {
		pat= pat+ "\nPatent Date" + ":\tempty.";
 	}
	if (document.newlibitemfrm.intnl_clsfcn.value == "") {
		pat= pat+ "\nInt. Cl." + ":\t\tempty.";
 	}
	if (document.newlibitemfrm.citations.value == "") {
		pat= pat+ "\nCitation" + ":\t\tempty.";
 	}
	if (document.newlibitemfrm.no_pages.value == "") {
		pat= pat+ "\nNo Pages" + ":\tempty.";
 	}
 	
 	msg = msg + pat;
	return validcheck();
	
	if (pat != "") {
		return false;
	}
}
function journalverify() {
	lib_item_general();
	if (msg == "name conflict") {
		return false;
	}
	return validcheck();
}
function articleverify() {
	lib_item_general();
	if (msg == "name conflict") {
		return false;
	}
	return validcheck();
}
function reportverify() {
	lib_item_general();
	if (msg == "name conflict") {
		return false;
	}
	return validcheck();
}
function otherverify() {
	lib_item_general();
	if (msg == "name conflict") {
		return false;
	}
	return validcheck();
}

function lib_item_general() {
	msg0= "Please check these fields:";
	msg = msg0;
	real_no = 0;
	//v0 = document.newlibitemfrm.cat_id_lib.value
	no_auth = eval(document.newlibitemfrm.no_author_lib.value);
	e = document.newlibitemfrm.elements;
	//window.alert("test " + e.length + "- No Author: " + no_auth);
	if (e.length) {
		for (i=0; i<no_auth; i++) {
			j = 4 + i*6;
			//	check names
			fname0 = e[j].name.substring(0,9);
			fname1 = e[j+1].name.substring(0,9);
			mname0 = e[j+2].name.substring(0,10);
			mname1 = e[j+3].name.substring(0,10);
			lname0 = e[j+4].name.substring(0,8);
			lname1 = e[j+5].name.substring(0,8);
			//window.alert("test " + e[j].name);
			//return;
			if (fname0 != fname1 && fname0 != "firstname") {
				msg = "First name conflict.";
				window.alert(msg);
				msg = "name conflict";
				return;
			}
			if (mname0 != mname1 && mname0 != "middlename") {
				msg = "Middle name conflict.";
				window.alert(msg);
				msg = "name conflict";
				return;
			}
			if (lname0 != lname1 && lname0 != "lastname") {
				msg = "Last name conflict.";
				window.alert(msg);
				msg = "name conflict";
				return;
			}
			
			// 	check values
			fname0 = e[j].value;
			fname1 = e[j+1].value;
			mname0 = e[j+2].value;
			mname1 = e[j+3].value;
			lname0 = e[j+4].value;
			lname1 = e[j+5].value;
			/*
			field 	=	"fname0: " + fname0 + "\n" + "fname1: " + fname1 + "\n";
			field 	=	field + "mname0: " + mname0 + "\n" + "mname1: " + mname1 + "\n";
			field 	=	field + "lname0: " + lname0 + "\n" + "lname1: " + lname1 + "\n";
			window.alert(field);
			//*/
			temp = "";
			if (real_no == i) {
				if (fname0 == "" && eval(fname1) == 0) {
					temp = temp + "\tFirst name empty;";
				} else if (fname0 != "" && eval(fname1) > 0) {
					temp = temp + "\tFirst name duplicated;";
				}
				if (mname0 != "" && eval(mname1) > 0) {
					temp = temp + "\tMiddle name duplicated;";
				}
				if (lname0 == "" && eval(lname1) == 0) {
					temp = temp + "\tLast name empty;";
				} else if (lname0 != "" && eval(lname1) > 0) {
					temp = temp + "\tLast name duplicated;";
				}
			}
			if (temp != "") {
				//window.alert(temp);
				k = i + 1;
				if (k > 9) {
					msg = msg + "\nAuthor " + k + ":" + temp;
				} else {
					msg = msg + "\nAuthor " + k + ":\t" + temp;
				}
			} else {
				if (real_no == i) {
					real_no = real_no + 1;
				}
			}
		}
	}
	if (document.newlibitemfrm.libtitle.value == "") {
		msg = msg + "\nTitle:\tempty.";
	}
	if (document.newlibitemfrm.keyword_id.value == "") {
		msg = msg + "\nKeyword:\t\tempty.";
	}
	if (document.newlibitemfrm.abstract.value == "") {
		msg = msg + "\nAbstract:\t\tempty.";
	}
	//  msg = new MakeArray(numOrders);
}
	
function title1verify() {
var msg0, key1, key2;
	msg0 = "Check following fields:\n\n";
	msg = msg0;

	//title1
	v0 = document.frmtitle1.title1.value;
	emptycheck()
	if (epy0== "y") {
		msg = "Please enter title.\n";
	}

	if (msg == msg0) {
		return true;
	} else {
		window.alert(msg);
		return false;
	}
}

function keyword1verify() {
var msg0, key1, key2;
	msg0 = "Check following fields:\n\n";
	msg = msg0;

	//keywords1
	v0 = document.frmkeyword1.keywords1.value;
	emptycheck()
	key1=epy0

	//keywords2
	key2=epy0
	v0 = document.frmkeyword1.keywords2.value;
	emptycheck()
	if (key1 == "y" && key2 == "y") {
		msg = "Please enter at least one keyword.\n";
	}

	if (msg == msg0) {
		return true;
	} else {
		window.alert(msg);
		return false;
	}
}

function auth1verify() {
var msg0;
	msg0 = "Check following fields:\n\n";
	msg = msg0;

	//authfirst
	field = "\"First Name\"";
	v0 = document.frmauthor1.authfirst.value;
	emptycheck()
	if (epy0 == "y") {
		msg = msg  + field + " field: empty.\n";
	}

	//authlast
	field = "\"Last Name\"";
	v0 = document.frmauthor1.authlast.value;
	emptycheck()
	if (epy0 == "y") {
		msg = msg  + field + " field: empty.\n";
	}

	if (msg == msg0) {
		return true;
	} else {
		window.alert(msg);
		return false;
	}
}

function emptycheck() {
var len, str;
	len = v0.length;
	epy0 = "y";
	if (len != 0) {
		for (i=0; i<len; i++) {
			str 	=	v0.substring(i,i+1);
			//window.alert("Postion " + i + ": " + str);
			if (str != " ") {
				epy0 = "n";	
				break;
			}
		}
	}
}

function MakeArray(n) {
  this.length = n;
  for (var i = 1; i <= n; i++)
    this[i] = null
  return this
}

</script>
</html>
