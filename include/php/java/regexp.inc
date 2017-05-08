<html>
<script language=javascript>
function chkdate(tmp) {
var myregexp = /( )+/g;
	tmp=tmp.replace(myregexp, "");
	myregexp = /(\.)+/;
	tmp=tmp.replace(myregexp, "\.");
	myregexp = /[a-zA-Z]/g;
	tmp=tmp.replace(myregexp, "");
	myregexp = /(!|~|`|\@|\#|\&|\*|\$|%|\^|\+|\=|_|\(|\))/g;
	tmp=tmp.replace(myregexp, "");
	myregexp = /^(\d){4}-(\d){2}-(\d){2}\b/g;
	if (tmp == "0000-00-00") {
		return -1;
	}
	if (tmp.replace(myregexp, "") == "") {
		return tmp;
	} else {
		return -1;
	}
}

function chkmoney(tmp) {
	var myregexp = /( )+/g;
	tmp=tmp.replace(myregexp, "");
	myregexp = /(\.)+/;
	tmp=tmp.replace(myregexp, "\.");
	myregexp = /[a-zA-Z]/g;
	tmp=tmp.replace(myregexp, "");
	myregexp = /(!|~|`|\@|\#|\&|\*|\$|%|\^|\+|\=|_|-|\(|\))/g;
	tmp=tmp.replace(myregexp, "");
	//alert(tmp);
	myregexp = /(\d)+/g;
	if (tmp.replace(myregexp, "") == "") {
		tmp=tmp + ".00";
	}
	myregexp = /(\d)+\./g;
	if (tmp.replace(myregexp, "") == "") {
		tmp=tmp + "00";
		return tmp;
	}
	myregexp = /\./;
	if (parseInt(tmp.search(myregexp)) == 0) {
		tmp= "0" + tmp;
		return tmp;
	}

	myregexp = /(\d)+\.(\d)+/g;
	if (tmp.replace(myregexp, "") != "") {
		return -1;
	} else {
		return tmp;
	}
}

function chkempty(tmp) {
	var myregexp = /( )+/g;
	return tmp.replace(myregexp, "");
}

</script>
</html>
