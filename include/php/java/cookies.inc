<html>

<head>
<title>Cookies</title>

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

</script>

</html>

