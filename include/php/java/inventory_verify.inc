<html>
<script language=javascript>
var v0, epy0, v, i;
var msg, field;

function invlocationverify() {
var msg0;
	msg0 = "";
	msg = msg0;

	//location
	field = "\"Location\"";
	v0 = document.invnewitem.location.value;
	v = -1;
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

function invverify() {
var msg0;
	msg0 = "Check following fields (either missing or duplicated):\n\n";
	msg = msg0;
	//catname		catid1
	field = "\"Category\"";
	v0 = document.invnewitem.catname.value;
	v = document.invnewitem.catid1.value;
	emptycheck()
	
	//brandname	brandid1 
	field = "\"Brand\"";
	v0 = document.invnewitem.brandname.value;
	v = document.invnewitem.brandid1.value;
	//emptycheck()

	//manfname		manid1 
	field = "\"Manufacturer\"";
	v0 = document.invnewitem.manfname.value;
	v = document.invnewitem.manid1.value;
	emptycheck()
	
	//biref_description 
	field = "\"Description\"";
	v0 = document.invnewitem.biref_description.value;
	v = -1;
	emptycheck()
	if (epy0 == "y") {
		msg = msg  + field + " field: empty.\n";
	}
	
	//location
	field = "\"Location\"";
	v0 = document.invnewitem.location.value;
	v = -1;
	emptycheck()
	if (epy0 == "y") {
		msg = msg  + field + " field: empty.\n";
	}
	
	//barcode		size_xyz_cm 			color				year_made			order_no		purchase_date
	//disposal 	purchasing_price		purchased_by		purchased_for

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
	if (eval(v) == -1 ) {
	} else {
		if (epy0 == "y" && eval(v) == 0) {
			msg = msg  + field + " field: either type or select one from list.\n";
		} else if (epy0 == "n" && eval(v) > 0) {
			msg = msg  + field + " field: you have typed and selected one.\n";
		}
	}
}

</script>
</html>
