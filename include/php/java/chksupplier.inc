<html>
<script language=javascript>

function chksupplier() {
/* table: supplier. columns: supid, company, telno, faxno, mobno, email, www, address, contactperson*/

	var msg="";
	var tmp, tmpempty, regexp;
	tmp = document.supplierslistman.company.value;
	tmpempty = chkempty(tmp);
	
	//alert(tmp);
	if (tmpempty == "") {
		msg = "Supplier:\tempty\n";
		alert(msg);
	}
	
	tmp = document.supplierslistman.faxno.value;
	tmpempty = chkempty(tmp);
	if (tmpempty == "") {
		msg = msg  + "Fax:\tempty\n";
	}
	
	tmp = document.supplierslistman.telno.value;
	tmpempty = chkempty(tmp);
	if (tmpempty == "") {
		msg = msg  + "Tel:\tempty\n";
	}

	if (msg != "") {
		alert("The following field(s) can't be empty:\n\n" + msg + "\n\nEnter correct data and re-submit.");
		return false;
	}
	return true;
}
</script>
</html>
