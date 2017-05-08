<HTML><HEAD>
<TITLE>Image Swap Clock 3</TITLE>
<SCRIPT LANGUAGE="JavaScript">
<!--Hide from JS-Impaired Browsers
var ns="0123456789";
var flg=0;
today=new Date();
M=new Array("January","February","March","April","May","June","July","August","September","October","November","December");
D=new Array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");

function stopit(){
 if(flg==1){
  clearTimeout(tmout);
  }
 flg=0;
 }

function startit(){
 stopit();
 Clock();
 }

function prtIt(){
 document.images[2].src="nr/c.gif";
 document.images[5].src="nr/c.gif";
 startit();
 }

function Clock(){
 dt=new Date();
 hrs=dt.getHours();
 min=dt.getMinutes();
 sec=dt.getSeconds();
 document.images[9].src=((hrs>=12)?"nr/pm.gif":"nr/am.gif");
 hrs=((hrs>12)?hrs-12:hrs);
 document.images[0].src=((hrs<10)?"nr/w.gif":"nr/1.gif");
 hrs=((hrs>9)?hrs-10:hrs);
 document.images[1].src="nr/"+hrs+".gif";
 ls=((min<10)?"0":"")+min;
 for (var i=0;i<2;i++){
  document.images[i+3].src="nr/"+ns.indexOf(ls.charAt(i))+".gif";
  }
 ls=((sec<10)?"0":"")+sec;
 for (var i=0;i<2;i++){
  document.images[i+6].src="nr/"+ns.indexOf(ls.charAt(i))+".gif";
  }
 tmout=setTimeout("Clock()",1000);
 flg=1;
 }

//-->
</SCRIPT>
</HEAD>
<!--We start the clock running in the BODY call-->
<BODY onLoad="prtIt()"
 BGCOLOR="FFFFFF">
<CENTER>
<B>JavaScript to Print an Image Swap Clock to Screen</B>
<P><FORM NAME="frm">
<SCRIPT LANGUAGE="JavaScript">
<!--Hide from JS-Impaired Browsers
document.write('<TABLE BORDER=0><TR><TD'
+' VALIGN=TOP ALIGN=CENTER><BR>');
for (var i=0;i<10;i++){
 if (i==9){
  w=30;
  }
 else{
  w=(i==2||i==5||i==8?8:14);
  }
 document.write('<IMG SRC="nr/w.gif" WI'
 +'DTH='+w+' HEIGHT=20 BORDER=0>')
 }
document.write("</TD></TR><TR><TD ALI"
+"GN=CENTER><FONT SIZE=4><B>"
+D[today.getDay()]+", "+M[today.getMonth()]
+" "+today.getDate()+", "+today.getYear()
+"</B></FONT></TD></TR>");
// End Hiding -->
</SCRIPT>
<TR><TD ALIGN=CENTER><BR>
<INPUT TYPE="radio" NAME="rb" VALUE="a"
 onClick="stopit()"> Stop the Clock     
<INPUT TYPE="radio" NAME="rb" VALUE="b"
 onClick="startit()" CHECKED> Start the
 Clock</TD></TR>
</TABLE></FORM>
<P>
</BODY>
</HTML>
