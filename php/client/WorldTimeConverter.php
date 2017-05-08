<HTML>
<HEAD>
<SCRIPT LANGUAGE=javascript>

var timeDiff;
var selectedCity;
var daylightSavingAdjust = 0;

function window_onload()
{
   updateTimeZone();
   window.setInterval("updateTime()",5000);
}


function updateTimeZone() 
{
   var lstCity = document.form1.lstCity;	//[object]		lstCity.selectedIndex
   timeDiff = lstCity.options[lstCity.selectedIndex].value;
   selectedCity = lstCity.options[lstCity.selectedIndex].text;
   //alert(timeDiff + ": " + selectedCity);
   updateTime();
}

function getTimeString(dateObject)
{
   var timeString;

   var hours = dateObject.getHours();
   if (hours < 10)
      hours = "0" + hours;

   var minutes = dateObject.getMinutes();
   if (minutes < 10)
      minutes = "0" + minutes;

   var seconds = dateObject.getSeconds()
   if (seconds < 10)
      seconds = "0" + seconds;

   timeString = hours + ":" + minutes + ":" + seconds;

   return timeString;
}

function updateTime()
{
   var nowTime = new Date();

   var resultsFrame = window.top.resultsFrame.document;
	//alert(resultsFrame);
	
   resultsFrame.open()
   resultsFrame.write("<table border=1><tr><th width=270>City</th><th width=80>Time</th></tr>");
   resultsFrame.write("<tr><td>Local</td><th>" + getTimeString(nowTime) + "<BR>");
   resultsFrame.write("</th></tr>");
   
   //alert(nowTime);
   /*
   alert(nowTime.getMinutes());
   alert(nowTime.getTimezoneOffset());
   alert(timeDiff);
   alert(daylightSavingAdjust);
   //*/
   nowTime.setMinutes(nowTime.getMinutes() + nowTime.getTimezoneOffset() + 
      parseInt(timeDiff) + daylightSavingAdjust);
   resultsFrame.write("<tr><td>" + selectedCity + "</td><th>" + getTimeString(nowTime));
   resultsFrame.write("</th></tr>");
   //alert(nowTime);


   resultsFrame.write("</table>");
   resultsFrame.close();
}

function chkDaylightSaving_onclick()
{
   if (document.form1.chkDaylightSaving.checked)
   {
      daylightSavingAdjust = 60;
   }
   else
   {
      daylightSavingAdjust = 0;
   }

   updateTime();
}

</SCRIPT>
</HEAD>

<BODY LANGUAGE=JavaScript onload="return window_onload()">
<h1>WorldTime Converter <font size="1"> (March 2001)</font></h1>
<!--
If you want add other cities into the list please send me 
	message with City and GMT time difference.
	<p><a href="mailto:
<?php
	include("find_admin_ip.inc");
	echo $adminname;
?>
@rla.com.au">[Add More Cities To the List]</a></p>
-->
<hr color=red>
<FORM NAME=form1>
<b>Please select a city from the list to convert.</b><br><br>
<SELECT SIZE=1 NAME=lstCity  onchange="updateTimeZone();">

<OPTION value="240">Abu Dhabi(GMT+04:00)</OPTION>
<OPTION value="570">Adelaide(GMT+09:30)</OPTION>
<OPTION value="-540">Alaska(GMT-09:00)</OPTION>
<OPTION value="60">Amsterdam(GMT+01:00)</OPTION>
<OPTION value="-420">Arizona(GMT-07:00)</OPTION>
<OPTION value="120">Athens(GMT+02:00)</OPTION>
<OPTION value="-240">Atlantic Time (Canada)(GMT-04:00)</OPTION>
<OPTION value="720">Auckland(GMT+12:00)</OPTION>
<OPTION value="-60">Azores(GMT-01:00)</OPTION>
<OPTION value="180">Baghdad(GMT+03:00)</OPTION>
<OPTION value="420">Bangkok(GMT+07:00)</OPTION>
<OPTION value="480">Beijing(GMT+08:00)</OPTION>
<OPTION value="60">Berlin(GMT+01:00)</OPTION>
<OPTION value="60">Bern(GMT+01:00)</OPTION>
<OPTION value="-300">Bogota(GMT-05:00)</OPTION>
<OPTION value="330">Bombay(GMT+05:30)</OPTION>
<OPTION value="-180">Brasilia(GMT-03:00)</OPTION>
<OPTION value="60">Bratislava(GMT+01:00)</OPTION>
<OPTION value="600">Brisbane(GMT+10:00)</OPTION>
<OPTION value="60">Brussels(GMT+01:00)</OPTION>
<OPTION value="-180">Buenos Aires(GMT-03:00)</OPTION>
<OPTION value="120">Cairo(GMT+02:00)</OPTION>
<OPTION value="330">Calcutta(GMT+05:30)</OPTION>
<OPTION value="600">Canberra(GMT+10:00)</OPTION>
<OPTION value="600">Canberra(GMT+10:00)</OPTION>
<OPTION value="-60">Cape Verde Is.(GMT-01:00)</OPTION>
<OPTION value="-240">Caracas(GMT-04:00)</OPTION>
<OPTION value="0">Casablanca(GMT)</OPTION>
<OPTION value="480">Chongqing(GMT+08:00)</OPTION>
<OPTION value="330">Colombo(GMT+05:30)</OPTION>
<OPTION value="570">Darwin(GMT+09:30)</OPTION>
<OPTION value="360">Dhaka(GMT+06:00)</OPTION>
<OPTION value="360">Dhaka(GMT+06:00)</OPTION>
<OPTION value="0">Dublin(GMT)</OPTION>
<OPTION value="120">Eastern Europe(GMT+02:00)</OPTION>
<OPTION value="0">Edinburgh(GMT)</OPTION>
<OPTION value="300">Ekaterinburg(GMT+05:00)</OPTION>
<OPTION value="-720">Eniwetok(GMT-12:00)</OPTION>
<OPTION value="720">Fiji(GMT+12:00)</OPTION>
<OPTION value="-180">Georgetown(GMT-03:00)</OPTION>
<OPTION value="0">Greenwich Mean Time</OPTION>
<OPTION value="600">Guam(GMT+10:00)</OPTION>
<OPTION value="480">Guangzhou(GMT+08:00)</OPTION>
<OPTION value="420">Hanoi(GMT+07:00)</OPTION>
<OPTION value="120">Harare(GMT+02:00)</OPTION>
<OPTION value="-600">Hawaii(GMT-10:00)</OPTION>
<OPTION value="120">Helsinki(GMT+02:00)</OPTION>
<OPTION value="600">Hobart(GMT+10:00)</OPTION>
<OPTION value="480">Hong Kong(GMT+08:00)</OPTION>
<OPTION value="-300">Indiana (East)(GMT-05:00)</OPTION>
<OPTION value="300">Islamabad(GMT+05:00)</OPTION>
<OPTION value="120">Israel(GMT+02:00)</OPTION>
<OPTION value="120">Istanbul(GMT+02:00)</OPTION>
<OPTION value="420">Jakarta(GMT+07:00)</OPTION>
<OPTION value="270">Kabul(GMT+04:30)</OPTION>
<OPTION value="720">Kamchatka(GMT+12:00)</OPTION>
<OPTION value="300">Karachi(GMT+05:00)</OPTION>
<OPTION value="240">Kazan(GMT+04:00)</OPTION>
<OPTION value="180">Kuwait(GMT+03:00)</OPTION>
<OPTION value="-720">Kwajalein(GMT-12:00)</OPTION>
<OPTION value="-240">La Paz(GMT-04:00)</OPTION>
<OPTION value="-300">Lima(GMT-05:00)</OPTION>
<OPTION value="0">Lisbon(GMT)</OPTION>
<OPTION value="0">London(GMT)</OPTION>
<OPTION value="330">Madras(GMT+05:30)</OPTION>
<OPTION value="60">Madrid(GMT+01:00)</OPTION>
<OPTION value="660">Magadan(GMT+11:00)</OPTION>
<OPTION value="720">Marshall Is.(GMT+12:00)</OPTION>
<OPTION value="-360">Mexico City(GMT-06:00)</OPTION>
<OPTION value="-120">Mid-Atlantic(GMT-02:00)</OPTION>
<OPTION value="-660">Midway Island(GMT-11:00)</OPTION>
<OPTION value="0">Monrovia(GMT)</OPTION>
<OPTION value="180">Moscow(GMT+03:00)</OPTION>
<OPTION value="240">Muscat(GMT+04:00)</OPTION>
<OPTION value="180">Nairobi(GMT+03:00)</OPTION>
<OPTION value="480">Nanjing(GMT+08:00)</OPTION>
<OPTION value="330">New Delhi(GMT+05:30)</OPTION>
<OPTION value="660">NewCaledonia(GMT+11:00)</OPTION>
<OPTION value="-210">Newfoundland(GMT-03:30)</OPTION>
<OPTION value="540">Osaka(GMT+09:00)</OPTION>
<OPTION value="60">Paris(GMT+01:00)</OPTION>
<OPTION value="480">Perth(GMT+08:00)</OPTION>
<OPTION value="600">PortMoresby(GMT+10:00)</OPTION>
<OPTION value="60">Prague(GMT+01:00)</OPTION>
<OPTION value="120">Pretoria(GMT+02:00)</OPTION>
<OPTION value="180">Riyadh(GMT+03:00)</OPTION>
<OPTION value="60">Rome(GMT+01:00)</OPTION>
<OPTION value="-660">Samoa(GMT-11:00)</OPTION>
<OPTION value="540">Sapporo(GMT+09:00)</OPTION>
<OPTION value="-360">Saskatchewan(GMT-06:00)</OPTION>
<OPTION value="540">Seoul(GMT+09:00)</OPTION>
<OPTION value="480">Shanghai(GMT+08:00)</OPTION>
<OPTION value="480">Singapore(GMT+08:00)</OPTION>
<OPTION value="660">Solomon Is.(GMT+11:00)</OPTION>
<OPTION value="180">St.Petersburg(GMT+03:00)</OPTION>
<OPTION value="60">Stockholm(GMT+01:00)</OPTION>
<OPTION value="600">Sydney(GMT+10:00)</OPTION>
<OPTION value="480">Taipei(GMT+08:00)</OPTION>
<OPTION value="300">Tashkent(GMT+05:00)</OPTION>
<OPTION value="240">Tbilisi(GMT+04:00)</OPTION>
<OPTION value="210">Tehran(GMT+03:30)</OPTION>
<OPTION value="480">Tianjin(GMT+08:00)</OPTION>
<OPTION value="-480">Tijuana(GMT-08:00)</OPTION>
<OPTION value="540" SELECTED>Tokyo(GMT+09:00)</OPTION>
<OPTION value="480">Urumqi(GMT+08:00)</OPTION>
<OPTION value="-360">US & Canada(Central Time)(GMT-06:00)</OPTION>
<OPTION value="-300">US & Canada(Eastern Time)(GMT-05:00)</OPTION>
<OPTION value="-420">US & Canada(MountainTime)(GMT-07:00)</OPTION>
<OPTION value="-480">US & Canada(Pacific Time)(GMT-08:00)</OPTION>
<OPTION value="60">Vienna(GMT+01:00)</OPTION>
<OPTION value="600">Vladivostok(GMT+10:00)</OPTION>
<OPTION value="240">Volgograd(GMT+04:00)</OPTION>
<OPTION value="60">Warsaw(GMT+01:00)</OPTION>
<OPTION value="720">Wellington(GMT+12:00)</OPTION>
<OPTION value="480">Wuhan(GMT+08:00)</OPTION>
<OPTION value="540">Yakutsk(GMT+09:00)</OPTION>

</SELECT>
<P>
<table><tr><td>
<INPUT TYPE="checkbox" NAME=chkDaylightSaving LANGUAGE=JavaScript
   onclick="return chkDaylightSaving_onclick()"></td><td><b>
Please check the left box to adjust for summertime<br>
daylight saving, if the selected city is in summer time.</b></td></tr></table>

</P>
</FORM>
<hr color=red>

</BODY>
</HTML>
