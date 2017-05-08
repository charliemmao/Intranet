<html>

 <head>
 <title></title>
 </head>

 <body bgcolor="#C0C0C0">

 <b>

 <font face="Courier New">

 string date(string format, int [timestamp] )</font></b>
 <p><font face="Courier New">Returns a string formatted according to the given format string using the given timestamp or the current local time if no timestamp is given. 
 The following characters are recognized in the format string:</font></p>
      
 <p><b><font face="Courier New">Year</font></b></p>
      
 <ul>
  <li><font face="Courier New">&nbsp;Y - year, 4 digits; i.e. "1999"</font></li>
  <li><font face="Courier New">&nbsp;y - year, 2 digits; i.e. "99"</font></li>
 </ul>
 <p align="left"><b><font face="Courier New">Month</font></b></p>
      
 <ul>
  <li><font face="Courier New">&nbsp;m - month; i.e. "01" to "12"</font></li>
  <li><font face="Courier New">&nbsp;n - month without leading zeros; i.e. "1" to "12"</font></li>
  <li><font face="Courier New">&nbsp;M - month, textual, 3 letters; i.e. "Jan"</font></li>
  <li><font face="Courier New">&nbsp;F - month, textual, long; i.e. "January"</font></li>
 </ul>
 <p align="left"><b><font face="Courier New">Day of the Month</font></b></p>
      
 <ul>
  <li><font face="Courier New">
	d - day of the month, 2 digits with leading zeros; i.e. "01" to "31"</font></li>
  <li><font face="Courier New">j - day of the month without leading zeros; i.e. "1" to "31"</font></li>
 </ul>
      
 <p align="left"><b><font face="Courier New">Day of the Week</font></b></p>
      
 <ul>
  <li><font face="Courier New">
	D - day of the week, textual, 3 letters; i.e. "Fri"</font></li>
  <li><font face="Courier New">
	w - day of the week, numeric, i.e. "0" (Sunday) to "6" (Saturday)</font></li>
  <li><font face="Courier New">l (lowercase 'L') - day of the week, textual, long; i.e. "Friday"</font></li>
 </ul>
      
 <p align="left"><b><font face="Courier New">Hour</font></b></p>
      
 <ul>
  <li><font face="Courier New">
	h - hour, 12-hour format; i.e. "01" to "12"</font></li>
  <li><font face="Courier New">
	g - hour, 12-hour format without leading zeros; i.e. "1" to "12"</font></li>
  <li><font face="Courier New">
	H - hour, 24-hour format; i.e. "00" to "23"</font></li>
  <li><font face="Courier New">
	G - hour, 24-hour format without leading zeros; i.e. "0" to "23"</font></li>
 </ul>
      
 <p align="left"><b><font face="Courier New">Minute</font></b></p>
      
 <ul>
  <li><font face="Courier New">
	i - minutes; i.e. "00" to "59"</font></li>
 </ul>
      
 <p align="left"><b><font face="Courier New">Second</font></b></p>
      
 <ul>
  <li><font face="Courier New">
	s - seconds; i.e. "00" to "59"</font></li>
 </ul>
      
 <p align="left"><b><font face="Courier New">AM/PM</font></b></p>
      
 <ul>
  <li><font face="Courier New">
	a - "am" or "pm"</font></li>
  <li><font face="Courier New">
	A - "AM" or "PM"</font></li>
 </ul>
      
 <p align="left"><b><font face="Courier New">Day of the Year</font></b></p>
      
 <ul>
  <li><font face="Courier New">
	z - day of the year; i.e. "0" to "365"</font></li>
 </ul>
      
 <p align="left"><b><font face="Courier New">Leap Year</font></b></p>
      
 <ul>
  <li><font face="Courier New">
	L - boolean for whether it is a leap year; i.e. "0" or "1"</font></li>
 </ul>
      
 <p align="left"><b><font face="Courier New">Days in one month</font></b></p>
      
 <ul>
  <li><font face="Courier New">
	t - number of days in the given month; i.e. "28" to "31"</font></li>
 </ul>
      
 <p align="left"><b><font face="Courier New">Special</font></b></p>
      
 <ul>
  <li><font face="Courier New">
	S - English ordinal suffix, textual, 2 characters; i.e. "th", &quot;nd&quot;</font></li>
  <li><font face="Courier New">
	U - seconds since the epoch</font></li>
  <li><font face="Courier New">
	Z - timezone offset in seconds (i.e. "-43200" to "43200")<br>
<br>
Unrecognized characters in the format string will be printed as-is. The "Z" format will always return "0" when using gmdate(). 
<br></font></li>
 </ul>
      
 <p><font face="Courier New">Example 1. date example
<br>print (date("l dS of F Y h:i:s A"));
<br>print ("July 1, 2000 is on a " . date("l", mktime(0,0,0,7,1,2000)));</font></p>
 <p><font face="Courier New">It is possible to use date and mktime together to find dates in the future or the past.&nbsp;</font></p>
 <p><font face="Courier New"> 
Example 2. date and mktime&nbsp;</font></p>
 <p><font face="Courier New"> example 
$tomorrow  = mktime(0,0,0,date("m")  ,date("d")+1,date("Y"));
$lastmonth = mktime(0,0,0,date("m")-1,date("d"),  date("Y"));
$nextyear  = mktime(0,0,0,date("m"),  date("d",   date("Y")+1);</font></p>
      
</html>