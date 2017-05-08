<html>

<head>
<title>Open New Window</title>

<script language="JavaScript">
window.onerror = null
var msgWindow  = null

//
// cancel
//
var cancelList            = null
var can_already_submitted = false

function MakeArray(n) {
  this.length = n;
  for (var i = 1; i <= n; i++)
    this[i] = null
  return this
}

function cancel_multiple(index, boxValue, isChecked) {
  if (isChecked == true) {
    cancelList[index] = boxValue
  } else {
    cancelList[index] = null
  }
}

function confirm_cancellation()
{
  var str = "";

  for (var i = 1; i <= cancelList.length; i++)
  {
    if (cancelList[i] != null) 
    {
      str = str + "\n\t" + cancelList[i]
    }
  }

  if (str == "")
  {
    alert("You have not selected any orders to cancel.\n\n" +
          "Please select the order to cancel by clicking in the \"Cancel\" checkbox.");
    return false
  }

  return confirm("Please confirm cancellation of the following orders:\n" + str)
}

function init_cancel()
{
  var i = 0, numOrders = 0, firstCancelItem = null;
  var itemName;

  // Count the number of orders
  while (document.cancelForm.elements[i].name != "endOrderList" ) {
    itemName = document.cancelForm.elements[i].name.substring(0, 10);
    if (itemName == "cancelItem") {
      if (firstCancelItem == null) { 
        firstCancelItem = i 
      }
      numOrders++
    }
    i++
  }

  // Initialise cancellation list
  cancelList = new MakeArray(numOrders);
  for (i = 1; i <= numOrders; i++) {
    if (document.cancelForm.elements[firstCancelItem + i - 1].checked == true) {
      cancelList[i] = document.cancelForm.elements[firstCancelItem + i - 1].value
    } else {
      cancelList[i] = null
    }
  }
}

function check_can_submitted()
{
  if (can_already_submitted == false)
  {
    can_already_submitted = true
    canNotifyWindow()
    return true
  } 
  else 
  {
    return false
  }
}

function page_selection() {
  var selectedItem = (document.cancelForm.cbPageSelection.selectedIndex + 1);
  parent.mainFrame.location.href =  document.cancelForm["pageUrl" + selectedItem].value;
}


//
// order search
//
var msgWindow             = null
var sch_already_submitted = false

function schNotifyWindow() {
  msgWindow = window.open("/php/oereq.php", "Window", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,width=500,height=100")
}

function option_selected(){
  if ((document.enquiryForm.chBuy.checked == false) &&
      (document.enquiryForm.chSell.checked == false)){
    alert("You have not specified an Order Type.\n\n Please select either \"BUY\" and\/or " + 
          " \"SELL\"\n");
    return false;
  } else {
    return true
  }
}    

function check_sch_submitted()
{  
  if (sch_already_submitted == false) {
    sch_already_submitted = true
    schNotifyWindow()
    return true
  }
  else {
    return false
  }
}
 

function check_buy() {
  if (document.enquiryForm.chBuy.checked == false) {
    if (document.enquiryForm.chSell.checked == false) {
      document.enquiryForm.chSell.click()
    }
  }
}

function check_sell() {
  if (document.enquiryForm.chSell.checked == false) {
    if (document.enquiryForm.chBuy.checked == false) {
      document.enquiryForm.chBuy.click()
    }
  }
}

</script>

</html>

