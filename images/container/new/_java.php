<html>

<head>
<title>USE JAVA SCRIPT</title>

<script language="JavaScript">
window.onerror = null
var msgWindow  = null


function set_cookie()
{
  var nvstring = "";

  for (var i = 0; i < document.forms[1].elements.length; i++)
  {
    if (document.forms[1].elements[i].name.substring(0,2) == "rb")
    {
      nvstring = nvstring + i + ":checked=" + 
                 escape(document.forms[1].elements[i].checked) + ";";
    }
    if (document.forms[1].elements[i].name.substring(0,2) == "ch")
    {
      nvstring = nvstring + i + ":checkboxed=" +
                                escape(document.forms[1].elements[i].checked) + ";";
    }
    if (document.forms[1].elements[i].name.substring(0,2) == "cb")
    {
      nvstring = nvstring + i + ":selection=" +
       escape(document.forms[1].elements[i].selectedIndex) + ";";
    }

    if ((document.forms[1].elements[i].name.substring(0,2) == "eb") &&
        (document.forms[1].elements[i].name != "ebSecurity"))  
    {      
      nvstring = nvstring + i + ":value=" + 
                 escape(document.forms[1].elements[i].value) + ";";
    }
  }

  if (nvstring.length > 0) 
  {
    document.cookie = "regdefaults=" + escape(nvstring);
  }
}

function get_cookie() {
  var nvstring = "";
  var name     = "regdefaults";
  var offset = -1;
  var end    = -1;
  var i      =  0;
  var prop   = "";
  var val    = "";

  if (document.cookie.length == 0) return;

  /* Get the cookie */
  offset = document.cookie.indexOf(name);
  if (offset != -1) {
    offset += name.length + 1;
    end = document.cookie.indexOf(";",offset);
    if (end == -1)
      end = document.cookie.length;
    nvstring = unescape(document.cookie.substring(offset,end));
  }

  if (nvstring.length == 0) return;

  /* re-populate the form */
  offset = 0;
  while (1 == 1) {

    end = nvstring.indexOf(":",offset);
    if (end == -1) return;
    i = parseInt(nvstring.substring(offset,end), 10);
    if (i >= document.forms[1].elements.length) return;
    offset = end + 1;
    end = nvstring.indexOf("=",offset);
    if (end == -1) return;
    prop = nvstring.substring(offset,end);
    offset = end + 1;
    end = nvstring.indexOf(";",offset);
    if (end == -1) return;
    val = unescape(nvstring.substring(offset,end));
    /* set property of form object */
    if (prop == "value")
      document.forms[1].elements[ i ].value = val;
    if (prop == "selection")
      document.forms[1].elements[ i ].selectedIndex = val;  
    if (prop == "checkboxed")
          {
           if (val == "true")            
         document.forms[1].elements[ i ].checked = true;
           else
         document.forms[1].elements[ i ].checked = false;           
          }               
    if (prop == "checked")
          {
           if (val == "true")
         document.forms[1].elements[ i ].checked = true;
           else          
         document.forms[1].elements[ i ].checked = false;                      
          }
    offset = end + 1;
    if (offset >= nvstring.length) return;
    i = i + 1;
  }  
}

function notifyDetailWindow() {
    msgWindow = window.open("/php/oedetail.php", "Window", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,width=500,height=100")
}

function closeNotifyWindow() {
    if (msgWindow != null) msgWindow.close()
}

//
// cancel
//
var cancelList            = null
var can_already_submitted = false

function canNotifyWindow() {
    msgWindow = window.open("/php/ocsum.php", "Window", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,width=500,height=100")
}

function canNotifyDetailWindow() {
    msgWindow = window.open("/php/oedetail.php", "Window", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,width=500,height=100")
}

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

</head>

<body bgcolor="#FFFFFF" topmargin="5" leftmargin="5" link="#700C0C" 
vlink="#700C0C" onLoad="msgWindow=null; init_cancel()"
onUnload="closeNotifyWindow()">

<FORM ACTION="../../php/sn" METHOD="POST" target="mainFrame" name="cancelForm" 
onSubmit="return (confirm_cancellation() && check_can_submitted())">

<table border="0" cellspacing="4" cellpadding="0" width="100%">
  <table border="0" cellspacing="0" cellpadding="2" width="100%" class="Striped-Table">
  <tr valign="bottom" class="Table-Bar">
    <th align="left" class="Table-Header">
      <font><br>Number</font>
    </th>
    <th align="left" class="Table-Header">
      <font>Date/Time</font>
    </th>
    <th align="left" class="Table-Header">
      <font>Type</font>
    </th>
    <th align="left" class="Table-Header">
      <font>Code</font>
    </th>
    <th align="center" class="Table-Header">
      <font>Amend</font>
    </th>
    <th align="center" class="Table-Header">
      <font>Cancel</font>
    </th>
  </tr>
  <tr class="Stripe-1">
    <td align="left">S106945</td>
    <td align="left">18/05/2000&nbsp;</td>
    <td align="left">A</td>
    <td align="left">Test</td>
    <td align="center"><a href="/php/ocamend.php?7BE80D9AAA04884813C59FAB53685B233CB0827C1FC214ADFF822FC492D439B8DF530CBC543527E437A29D89B9FB595F9D3EA1F18DD30B4D" target="mainFrame" onClick="notifyDetailWindow()" class="Table-Anchor">Amend</a></td>
    <td align="center"><input type="checkbox" name="cancelItemS106945" value="S106945" onClick="cancel_multiple(1, 'S106945', this.checked)"></td>
  </tr>
</table>
<table border="0" cellspacing="0" cellpadding="0" width="100%">
  <tr><td> &nbsp; </td></tr>
  <tr class="Submit-Bar">
    <td class="Submit-Column">
    </td>
    <td align="right" class="Submit-Column">
      <input type="submit" name="Cancel Selected Orders" value="Cancel " border="0">    </td>
  </tr>
</table>

</table>

<input type="hidden" name="endOrderList" VALUE="">
<input type="hidden" name="ebOrdersList" VALUE="S106945">
</form>

<FORM ACTION="" METHOD="POST" name="enquiryForm" target="mainFrame" onSubmit="option_selected() && check_sch_submitted()">
<table border="0" cellspacing="4" cellpadding="4" width="100%">
<tr>
  <td colspan="2"></td>
</tr>
<tr bgcolor="#FFCE9C" class="Form-Bar">
  <td class="Form-Column">
  <table border="0" cellspacing="0" cellpadding="2" width="100%">
  <tr>
      <td width="15%" nowrap>
        <font face="Arial" size="2"> Type:</font></td>
      <td nowrap>
        <input type="checkbox"  name="chBuy" value="ON"
        onClick="check_buy()" CHECKED><font face="Arial" size="2">A &nbsp; &nbsp;
        </font><input type="checkbox" name="chSell" value="ON"
        onClick="check_sell()" CHECKED>
        <font face="Arial" size="2">B</font>
      </td>
    </tr>
        <tr>
      <td width="15%"nowrap>
        <font face="Arial" size="2"> Code:</font></td>
      <td>&nbsp;<input type="text" size="15" 
        maxlength="20" name="ebSecurity" VALUE="">
        <font class="Small-Font" face="Arial" size="1">
        &nbsp;</font>
                <font class="Small-Font" face="Arial" size="1">(<A HREF="/iiop/misdb?REQT=HWCS&fromscreen=cor" target="mainFrame" class="Search-Anchor">Search</A> 
                )</font>

      </td>
    </tr>
        <tr>
      <td width="15%" nowrap>
        <font face="Arial" size="2">Include:</font></td>
      <td nowrap>
        <font face="Arial" size="2"><input        type="radio" name="rbOrderToInclude" value="0">Open       &nbsp; &nbsp;       </font><font face="Arial"><input        type="radio" name="rbOrderToInclude" checked value="1">All       </font>
      </td>
    </tr>
               
<tr><td width="15%" align="left" nowrap><font face="Arial" size="2">From Date:</font></td>

<td>&nbsp;
<select name="cbFromDay" size="1">
	<OPTION>01
	<OPTION>02
	<OPTION>03
	<OPTION>04
	<OPTION>05
	<OPTION>06
	<OPTION>07
	<OPTION>08
	<OPTION>09
	<OPTION>10
	<OPTION>11
	<OPTION>12
	<OPTION>13
	<OPTION>14
	<OPTION>15
	<OPTION>16
	<OPTION>17
	<OPTION>18
	<OPTION SELECTED>19
	<OPTION>20
	<OPTION>21
	<OPTION>22
	<OPTION>23
	<OPTION>24
	<OPTION>25
	<OPTION>26
	<OPTION>27
	<OPTION>28
	<OPTION>29
	<OPTION>30
	<OPTION>31
</SELECT>&nbsp;

<select name="cbFromMonth" size="1">
	<OPTION>01
	<OPTION>02
	<OPTION>03
	<OPTION SELECTED>04
	<OPTION>05
	<OPTION>06
	<OPTION>07
	<OPTION>08
	<OPTION>09
	<OPTION>10
	<OPTION>11
	<OPTION>12
</SELECT>&nbsp;

<select name="cbFromYear" size="1">
	<OPTION>1990
	<OPTION>1991
	<OPTION>1992
	<OPTION>1993
	<OPTION>1994
	<OPTION>1995
	<OPTION>1996
	<OPTION>1997
	<OPTION>1998
	<OPTION>1999
	<OPTION SELECTED>2000
</SELECT></td> </tr>

<tr><td width="15%" align="left" nowrap><font face="Arial" size="2">To Date:</font></td>
<td>&nbsp;
<select name="cbToDay" size="1">
	<OPTION>01
	<OPTION>02
	<OPTION>03
	<OPTION>04
	<OPTION>05
	<OPTION>06
	<OPTION>07
	<OPTION>08
	<OPTION>09
	<OPTION>10
	<OPTION>11
	<OPTION>12
	<OPTION>13
	<OPTION>14
	<OPTION>15
	<OPTION>16
	<OPTION>17
	<OPTION>18
	<OPTION>19
	<OPTION>20
	<OPTION>21
	<OPTION>22
	<OPTION>23
	<OPTION>24
	<OPTION>25
	<OPTION SELECTED>26
	<OPTION>27
	<OPTION>28
	<OPTION>29
	<OPTION>30
	<OPTION>31
</SELECT>&nbsp;

<select name="cbToMonth" size="1">
	<OPTION>01
	<OPTION>02
	<OPTION>03
	<OPTION>04
	<OPTION SELECTED>05
	<OPTION>06
	<OPTION>07
	<OPTION>08
	<OPTION>09
	<OPTION>10
	<OPTION>11
	<OPTION>12
</SELECT>&nbsp;

<select name="cbToYear" size="1">
	<OPTION>1990
	<OPTION>1991
	<OPTION>1992
	<OPTION>1993
	<OPTION>1994
	<OPTION>1995
	<OPTION>1996
	<OPTION>1997
	<OPTION>1998
	<OPTION>1999
	<OPTION SELECTED>2000
</SELECT>
</td></tr>

<tr>
      <td width="15%" align="left" nowrap>
        <font face="Arial" size="2">Account:</font></td>
      <td>&nbsp;<select name="cbAccount" size="1">
	<OPTION>A
	<OPTION>B
	<OPTION>C
</SELECT></td>
    </tr>        
  </table>
  </td>
</tr>
<tr class="Submit-Bar">
  <td class="Submit-Column">
    <table border="0" cellspacing="0" cellpadding="2" width="100%">
        <tr>
          <td width="15%">&nbsp;</td>
          <td>
            &nbsp;<input type="submit" name="Submit Order Enquiry" 
            value=" Submit " border="0" align="bottom"></td>
        </tr>
        </table>
  </td>
</tr>  
</table>
</form>

</body>
</html>

