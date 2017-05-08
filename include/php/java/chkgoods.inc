<html>
<script language=javascript>

function chkgoods() {
	var msg;
	var i,j,target;
	msg = itembyitem();
	if (msg != "") {
		alert("Please check following field(s) for item 0:\n\n" + msg + "\n\nCheck and resubmit.");
		return false;
	}
	for (i=1; i<100; i++) {
		target = document.all("name" + i.toString());
		if (target != null) {
			//alert("name" + i.toString() + "=" + target.value);
			if (target.value) {
				msg = checkbyitem(i);
				if (msg != "") {
					alert("Please check following field(s) for item " + i + ":\n\n" + msg + "\n\nCheck and resubmit.");
					return false;
				}
			}
		} else {
			break;
		}
	}
	return confirm("Are you sure you want to add the goods to system?\n\nRemember no modification is allowed afterwards.");
}

function checkbyitem(i) {
	var msg="";
	var tmp, tmpempty, regexp, target;
		
	target = document.all("name" + i.toString());
	if (target != null) {
		tmp = target.value;
	}
	tmpempty = chkempty(tmp);
	//alert(tmp);
	if (tmpempty == "") {
		msg = "Product Name:\tempty\n";
		//alert(msg);
	}
	
	target = document.all("description" + i.toString());
	if (target != null) {
		tmp = target.value;
	}
	tmpempty = chkempty(tmp);
	//alert(tmp);
	if (tmpempty == "") {
		msg = msg + "Description:\tempty\n";
		//alert(msg);
	}
	
	target = document.all("price" + i.toString());
	if (target != null) {
		tmp = target.value;
	}
	tmpempty = chkmoney(tmp);
	//alert(tmp);
	if (tmpempty == "-1") {
		msg = msg + "Price:\t(" + tmp + ") wrong format (0.00).\n";
	} else {
		target.value = tmpempty;
	}
	//name, description, supid, product_code
	
	target = document.all("supid" + i.toString());
	if (target != null) {
		tmp = target.value;
	}
	tmp = document.goodslistman.supid.value;
	if (tmp  == "-1") {
		msg = msg + "Supplier:\t\tempty\n";
	}
	return msg;
}

function itembyitem() {
	var msg="";
	var tmp, tmpempty, regexp;
		
	tmp = document.goodslistman.name.value;
	tmpempty = chkempty(tmp);
	//alert(tmp);
	if (tmpempty == "") {
		msg = "Product Name:\tempty\n";
		//alert(msg);
	}
	
	tmp = document.goodslistman.description.value;
	tmpempty = chkempty(tmp);
	//alert(tmp);
	if (tmpempty == "") {
		msg = msg + "Description:\tempty\n";
		//alert(msg);
	}
	
	tmp = document.goodslistman.price.value;
	tmpempty = chkmoney(tmp);
	//alert(tmp);
	if (tmpempty == "-1") {
		msg = msg + "Price:\t(" + tmp + ") wrong format (0.00).\n";
	} else {
		document.goodslistman.price.value = tmpempty;
	}
	//name, description, supid, product_code
	
	tmp = document.goodslistman.supid.value;
	//alert(tmp);
	if (tmp  == "-1") {
		msg = msg + "Supplier:\t\tempty\n";
		//alert(msg);
	}
	return msg;
}
</script>
</html>
