<HTML><HEAD>
<TITLE>HTML to JavaScript Conversion</TITLE>
<SCRIPT LANGUAGE="JavaScript">
<!--Hide JavaScript from Java-Impaired Browsers
//alert("I am just bringing this window to the front");
var ls="";
var dq='"';
var sq="'";
var rr="\r";

function jsPars(){
 	ls=document.ug.txt.value;
 	if (ls==""){
  		alert("I'm sorry.  I don't find any text pasted into the text window");
  	} else {
  		htmlPar();
  	}
}

function htmlPar(){
 	document.ug.txt.value="Working.......";
 	pos=ls.indexOf(dq);
 	pos1=ls.indexOf(sq);
 	if ((pos>-1)&&(pos1>-1)){
  		reWrt();
  	}
 	if (pos>-1){
  		qt=sq;
  	}else{
  		qt=dq;
  	}
 	while (ls.indexOf(rr)>-1){
  		lc=ls.indexOf(rr);
  		ls=ls.substring(0,lc)+" "+ls.substring(lc+1,ls.length);
  	}
 	nls="<SCRIPT LANGUAGE="+qt+"JavaScript"+qt+">"+rr
 		+"<!-- Hide from JavaScript-Impaired Browsers"+rr
 		+" document.write("+qt+ls.substring(0,36)+qt;
 	ls=ls.substring(36,ls.length);
 	lsl=ls.length
 	pp=50;
  	if (pp>lsl) {
   		pp=lsl;
   	}
 	while (lsl>0){
  		nls+="\r +"+qt+ls.substring(0,pp)+qt;
  		ls=ls.substring(pp,lsl);
  		lsl=ls.length;
  		if (pp>lsl){
   			pp=lsl;
   		}
  	}
 	nls+=");"+rr+"// End Hiding -->"+rr+"</SC"+"RIPT>";
 	document.ug.txt.value=nls+rr+"<!-- Size: "+nls.length
 		+" bytes --> "+rr;
 	document.ug.txt.select();
 	document.ug.txt.focus();
}

function reWrt(){
 	pos=-1;
 	while (ls.indexOf(dq)>-1){
  		lc=ls.indexOf(dq);
  		ls=ls.substring(0,lc)+sq+ls.substring(lc+1,ls.length);
  	}
}
// End Hiding -->
</SCRIPT>

</HEAD>
<body background="/images/rlaemb.JPG">
<CENTER>
<FORM NAME="ug">
<TABLE BORDER=0 WIDTH=486>
<TR><TD align=center><font size=5>HTML to JavaScript Generator</font>
<!--
<P>To use it, simply paste your HTML into the window below and click the submit button.  
So long as you have not <B>mixed</B> single and double quotes in your HTML, 
it'll do the job for you. For example, if you code your HTML with double quotes 
<BODY BGCOLOR="white"> or <A HREF="file.htm">), it will return the JavaScript 
statement properly concatenated. Similarly, if you code your HTML with single quotes 
<BODY BGCOLOR='white'> or <A HREF='file.htm'>), it will function properly.
<P>If you write JavaScript within JavaScript, this utility will also be useful, 
but you will need to split any </SCRIPT> calls like this:
<P><DD><B>document.write("</SCR"+
<DD>"IPT>"</B>
<P>to avoid "confusing" the compiler. (Either that or add some exception handling to 
the source code of this utility)
-->
<P><FONT COLOR="red"><B>Important: </B></FONT> Don't forget to immediately copy and 
pasted the returned JavaScript into your own document, since it only exists in memory.
<P><DIV ALIGN="center"><INPUT TYPE="button" NAME="but" VALUE=" Submit When Your HTML Has 
Been Pasted Below " onClick="jsPars()"></DIV>
<P><TEXTAREA NAME="txt" ROWS=20 COLS=75></TEXTAREA>
</TD></TR>
</TABLE></FORM>
</BODY>
</HTML>
