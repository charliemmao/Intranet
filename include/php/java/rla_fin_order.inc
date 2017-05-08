<html>
<script language="javascript">
var text, i, j, tmp, msg;
var name, valtmp, estcost;
goods = new Array();
qty = new Array();
price = new Array();
charge = new Array();
ghrcharge = new Array();

function chkonesteporder(noitem) {
//Item ordered: collect data
	for (i=0; i<noitem; i++) {
		j=i+1;
		targets = document.all("goods"+i);
		if (targets != null) {
			goods[i]= targets.value;
		}
		targets = document.all("quantity"+i);
		if (targets != null) {
			qty[i]= targets.value;
		}
		targets = document.all("unitprice"+i);
		if (targets != null) {
			price[i]= targets.value;
		}
		targets = document.all("rlacharge"+i);
		if (targets != null) {
			charge[i]= targets.value;
		}
		targets = document.all("ghrcharge"+i);
		if (targets != null) {
			ghrcharge[i]= targets.value;
		}
	}
	
//Item ordered: find no item ordered
	j=0;
	for (i=0; i<noitem; i++) {
		if (goods[i]) {
			j++;
		}
	}	
	if (j==0) {
		window.alert("No item has been ordered, submit has been aborted.");
		return false;
	}
	
//Item ordered: data validation
	valtmp=0;
	msg = "";
	for (i=0; i<noitem; i++) {
		j = i + 1;
		if (goods[i]) {			
			tmp=chkempty(qty[i])
			if (tmp == "") {
				msg = msg + "Item " + j + " quantity:\tempty\n";
				qty[i] = 0;
			} else {
				targets = document.all("quantity"+i);
				targets.value = tmp;
				qty[i] = tmp;
			}			
			
			tmp=chkempty(price[i])			
			if (tmp == "") {
				msg = msg + "Item " + j + " price:\tempty.\n";
				price[i]= 0.00;
			} else {
				tmp=chkmoney(price[i]);
				//window.alert(tmp);
				targets = document.all("unitprice"+i);
				targets.value = tmp;
				price[i]=tmp;
				if (tmp == "0.00") {
					msg = msg + "Item " + j + " price:\twrong format (0.00).\n";
				}
			}
			
			tmp=chkempty(charge[i])
			if (tmp == "") {
				msg = msg + "Item " + j + " project:\tnot selected.\n";
			}
			
			tmp=chkempty(ghrcharge[i])
			if (tmp == "") {
				msg = msg + "Item " + j + " cost center:\tnot selected.\n";
			}
			 
			valtmp = valtmp + price[i]*qty[i];
		}
	}
		
	myregexp = /(\d)+/g;
	tmp = String(valtmp);
	if (tmp.replace(myregexp, "") == "") {
		tmp=tmp+ ".00";
	}	
	estcost = formatdata(tmp);

//Invoice related checkup: payment
	//credit=1; cheque=2; cash=3; account=4; EFT=5
	targets = document.all("paymethod");
	if (targets != null) {
		if (targets.value == 1) {
			targetcard = document.all("card_id");
			if (targetcard != null) {
				if (targetcard.value == "") {
					msg = msg + "\nYou paid by credit card but no credit card selected.\n";
				}
			}
		}
		
		if (targets.value == 2) {
			targetsck = document.all("checqueno");
			if (targetsck != null) {
				if (targetsck.value == "") {
					msg = msg + "\nYou paid by cheque but no checque entered.\n";
				}
			}
		}
	}

//Invoice related checkup: costs Estimated cost for this order is A$" + estcost + 
	invmsg = msg;
	if (msg) {
		invmsg = invmsg  + "\n";
	}

	checkinvoicedata("n");
	msg = invmsg + msg;
	if (msg) {
		window.alert(msg + "\nSubmit has been aborted.");
		return false;
	}
	if (window.event.srcElement.name == "valdata") {
		window.alert("All data are checked and OK. Ready to submit.");
		return true
	} else {
		return confirm("Do you really want to submit this order?\n\nPlease confirm.");
	}
}

function checkinvoicedata(show) {
	msg = "";
	targetord = document.all("invoice_date");
	valtmp = targetord.value;
	valtmp = chkempty(valtmp);
	if (!valtmp) {
		msg  = msg  + "Invoice Date:\tempty.\n";
	} else {
		valtmp = chkdate(valtmp);
		if (valtmp == "-1") {
			msg  = msg  + "Invoice Date Format:\tyyyy-mm-dd.\n";
		} else {
			targetord.value = valtmp;
		}
	}
	
	targetord = document.all("invoice_no");
	valtmp = targetord.value;
	valtmp = chkempty(valtmp);
	if (!valtmp) {
		msg  = msg  + "Invoice no:\tempty.\n";
	}

	targetord = document.all("invoice_cost");
	valtmp = targetord.value;
	valtmp = chkempty(valtmp);
	if (!valtmp) {
	}
	valtmp = chkmoney(valtmp);
	targetord.value = valtmp;
	if (valtmp == "0.00") {
		msg  = msg  + "Invoice Cost:\tzero, estimated cost entered.\n";
		targetord.value = estcost;
	}
	cost = valtmp;
	invcost = cost;
	
	targetord = document.all("invoice_gst");
	valtmp = targetord.value;
	valtmp = chkempty(valtmp);
	if (!valtmp) {
		targetord.value = "0.00";
	}
	valtmp = chkmoney(valtmp);
	targetord.value = valtmp;
	gst = valtmp;
	
	if (eval(gst)>=eval(cost)  && cost != "0.00"){
		msg  = msg  + "GST costs too much for goods.\n";
	}
	
	//invoice_no, invoice_date, invoice_cost, invoice_gst, delivery_cost, delivery_gst
	targetord = document.all("delivery_cost");
	valtmp = targetord.value;
	valtmp = chkempty(valtmp);
	if (!valtmp) {
		targetord.value = "0.00";
	}
	valtmp = chkmoney(valtmp);
	targetord.value = valtmp;
	cost = valtmp;
	
	targetord = document.all("delivery_gst");
	valtmp = targetord.value;
	valtmp = chkempty(valtmp);
	if (!valtmp) {
		targetord.value = "0.00";
	}
	valtmp = chkmoney(valtmp);
	targetord.value = valtmp;	
	gst = valtmp;
	
	if (eval(gst)>=eval(cost) && cost != "0.00"){
		msg  = msg  + "GST costs too much for delivery.\n";
	}

	if (msg) {
 		if (show == "y") {
 			window.alert(msg);
 		}
		return false
	}
 	if (show == "n") {
		return true
 	}
	msg="";
	targetord = document.all("estimate_cost");
	valtmp = targetord.value;
	if (eval(0.9*valtmp) > eval(invcost)){
		msg = "less";
	}
	if (eval(1.1*valtmp) < eval(invcost)){
		msg = "more";
	}
	tmp = 100.0*(1.0- invcost/valtmp)
	tmp = Math.abs(tmp);
	tmp = formatdata(String(tmp));
}

function invoiceentryconfirm() {
	checkinvoicedata("y");
	if (msg.length>4) {
		return false
	}
	if (eval(tmp)>10) {
		msg = "Warning: Invoice ($" + invcost + ") costs " + tmp + "% " + msg +
			" than estimation ($" + valtmp + ").\n\n";
	}
 	return confirm(msg + "Are you sure you have completed invoice entry ?\n\nPlease confirm.");
}

function backorderverify(noitem) {
var uord, urcvd;
	//window.alert(noitem);
	msg = "";
	for (i=0; i<noitem; i++) {
		targetord = document.all("bkordered"+i);
		targetrcd = document.all("unitorcvd"+i);
		if (targetord != null && targetrcd != null) {
			uord = targetord.value;
			urcvd= targetrcd .value;
			if (uord != urcvd) {
				targetpro = document.all("product"+i);
				if (targetpro != null) {
					msg = msg + targetpro.value;
				} else {
					msg = msg + "Item " + i + 1;
				}
				msg = msg + " has a back order.\n";
			}
		}
	}
	if (msg) {
		msg = msg + "\nBack order will be placed in the DB.\nYou are required to verify back order later.\n\n";
	}
 	return confirm(msg + "Are you sure you have verified this order?\n\nPlease confirm.");
}

function chequeconfirm() {
//collect data
	msg = "";
	//cash=3; cheque=2; credit=1
	//credit card selection paymethod card_id
	targets = document.all("paymethod");
	if (targets.value == 1) {
		target = document.all("card_id");
		if (target != null) {
			if (target.value == "") {
				msg = "Please select credit card.";
				window.alert(msg);
				return false;
			}
		}
	}
	
	if (targets.value == 2) {	
		target = document.all("chequeno");
		if (target != null) {
			if (target.value == "") {
				msg = "Please type cheque no in the box.";
				window.alert(msg);
				return false;
			}
		}
	}
	
	return confirm("Do you really want to process this order?\n\nPlease confirm.");
}

function orderapproval(noitem) {
//collect data
	msg = "";
	targets = document.all("ghrcharge0");
	if (targets != null) {
		if (targets.value == "") {
			msg = msg + "No cost center code selected.";
		}
	}
	
	targets = document.all("paymethod");
	if (targets != null) {
		if (targets.value == 1) {
			targetcard = document.all("card_id");
			if (targetcard != null) {
				if (targetcard.value == "") {
					msg = msg + "\nNo credit card selected.";
				}
			}
		}
		//cash=3; cheque=2; credit=1
	}
	
	if (msg != "") {
		window.alert(msg + "\n\nPlease modify and submit again.");
		return false;
	}
  	return confirm("Do you really want to approval this order?\n\nPlease confirm.");
}

function cancelorderconfirm(orderid) {
	return confirm("Do you really want to cancel order " + orderid + "?")
}

function orderVerify(noitem) {
var uord, urcvd;
	//window.alert(noitem);
	msg = "";
	for (i=0; i<noitem; i++) {
		targetord = document.all("unitordered"+i);
		targetrcd = document.all("unitorcvd"+i);
		if (targetord != null && targetrcd != null) {
			uord = targetord.value;
			urcvd= targetrcd .value;
			if (uord != urcvd) {
				targetpro = document.all("product"+i);
				if (targetpro != null) {
					msg = msg + targetpro.value;
				} else {
					msg = msg + "Item " + i + 1;
				}
				msg = msg + " has a back order.\n";
			}
		}
	}
	if (msg) {
		msg = msg + "\nBack order will be placed in the DB.\nYou are required to verify back order later.\n\n";
	}
 	return confirm(msg + "Are you sure you have verified this order?\n\nPlease confirm.");
}

function chkorder(noitem) {
//collect data
	//window.alert(noitem);
	
	for (i=0; i<noitem; i++) {
		j=i+1;
		targets = document.all("goods"+i);
		if (targets != null) {
			goods[i]= targets.value;
		}
		targets = document.all("quantity"+i);
		if (targets != null) {
			qty[i]= targets.value;
		}
		targets = document.all("unitprice"+i);
		if (targets != null) {
			price[i]= targets.value;
		}
		targets = document.all("rlacharge"+i);
		if (targets != null) {
			charge[i]= targets.value;
		}
			//window.alert("Item " + j + "\nproduc id:\t" + goods[i] + "\nquantity:\t" +qty[i] 
			//	+ "\nprice:\t" + price[i] + "\nproject id:\t" +charge[i]);
	}

//find no item ordered
	j=0;
	for (i=0; i<noitem; i++) {
		if (goods[i]) {
			j++;
		}
	}	
	if (j==0) {
		window.alert("No item has been ordered, submit has been aborted.");
		return false;
	}
	
	//window.alert(j + " item has been selected.");
	// && qty[i] && price[i]

//validate items
	valtmp=0;
	msg = "";
	for (i=0; i<noitem; i++) {
		j = i + 1;
		if (goods[i]) {
			//window.alert("Item " + j + "\nproduc id:\t" + goods[i] + "\nquantity:\t" +qty[i] 
				//+ "\nprice:\t" + price[i] + "\nproject id:\t" +charge[i]);
			
			tmp=chkempty(qty[i]);

			if (tmp == "") {
				msg = msg + "Item " + j + " quantity:\tempty\n";
				qty[i] = 0;
			} else {
				targets = document.all("quantity"+i);
				targets.value = tmp;
				qty[i] = tmp;
			}
			
			tmp=chkempty(price[i])			
			if (tmp == "") {
				msg = msg + "Item " + j + " price:\tempty.\n";
				price[i]= 0.00;
			} else {
				tmp=chkmoney(price[i]);
				//window.alert(tmp);
				targets = document.all("unitprice"+i);
				targets.value = tmp;
				price[i]=tmp;
				if (tmp == "0.00") {
					msg = msg + "Item " + j + " price:\twrong format (0.00).\n";
				}
			}
			
			if (i == 0) {
				tmp=chkempty(charge[i])
				if (tmp == "") {
					msg = msg + "Item " + j + " project:\tnot selected.\n";
				}
			}
			valtmp = valtmp + price[i]*qty[i];
		}
	}

	myregexp = /(\d)+/g;
	tmp = String(valtmp);

	if (tmp.replace(myregexp, "") == "") {
		tmp=tmp+ ".00";
	}
	
	//window.alert(tmp);
	tmp = formatdata(tmp);
	
	targets = document.all("estimate_cost");
	targets.value = tmp;
	//window.alert(tmp);
	targets = document.all("authperson");
	if (!targets.value) {
		msg = msg + "\nSelect a person to authorise your order.\n";
	}
	if (msg != "") {
		msg = msg + "\n\nPlease check your order and submit again!";
		window.alert(msg);
		return false;
	}
	valtmp = "";
	msg = "";
	targets = document.all("spec_instruction");
	if (targets != null) {
		valtmp = targets.value;
		valtmp = chkempty(valtmp);
		if (valtmp != "") {
			valtmp = "\nPlease also deliver your extra order to purchasing officer.";
			msg = "\ncost on extra goods is not included.";
		}
	}

	tmp ="Estimated cost for this order is A$" + tmp + msg + "\n" + valtmp;
  	rtn = confirm(tmp + "\n\nDo you really want to submit this order?\n\nPlease confirm.");
  	//alert(rtn);
  	return rtn;
}

function formatdata(data) {
var i, j, k;
	i = data.length;
	if (i == 0 ) {
		return data;
	}
	j= data.indexOf(".");
	if (j != -1) {
		k = j + 3;
		if (k > i) {
			k= i;
		}
		data= data.substring(0,j) + data.substring(j,k);
	}
	return data;
}
</script>
</html>
