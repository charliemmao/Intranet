<html>
<body background="rlaemb.JPG" leftmargin="20">

<h1>Policy Document Collection</h1>

<ul>
  <li>
	<h2>Information Management</h2>
    <ul>
      <li>
         [<a onMouseOver="self.status='Microsoft Word Document'; return true" onMouseOut="self.status=''; return true"
         	href="http:/rladoc/Information Management.doc" target=_top>Word Format</a>]
         [<a onMouseOver="self.status='PDF Document'; return true" onMouseOut="self.status=''; return true"
         	href="http:/rladoc/Information Management.pdf" target=_top>PDF Format</a>]</li>
    </ul>
  </li>
</ul>

<!--
<ul>
  <li>
	<h2>Sick Leave Policy</h2>
    <ul>
      <li>
         [<a onMouseOver="self.status='Microsoft Word Document'; return true" onMouseOut="self.status=''; return true"
         	href="http:/rladoc/Sick leave Policy.doc" target=_blank>Word Format</a>]</li>
    </ul>
  </li>
</ul>


<ul>
  <li>
	<h2>Time-In-Lieu</h2>
    <ul>
      <li>
         [<a onMouseOver="self.status='Microsoft Word Document'; return true" onMouseOut="self.status=''; return true"
         	href="http:/rladoc/tilmemo.doc" target=_blank>Word Format</a>]</li>
    </ul>
  </li>
</ul>


<ul>
  <li>
	<h2>Visitor Policy</h2>
    <ul>
      <li>
         [<a onMouseOver="self.status='Microsoft Word Document'; return true" onMouseOut="self.status=''; return true"
         	href="http:/rladoc/Visitor policy.doc" target=_blank>Word Format</a>]</li>
    </ul>
  </li>
</ul>

-->
<?php 
include("phpdir.inc"); 
?>
<ul>
  <li>
    <h2>Employee Handbook</h2>
    <ul>
    <li>
         [<a onMouseOver="self.status='HTML Document'; return true" onMouseOut="self.status=''; return true;" href="http:/<?php echo $phpdir ?>/rlaemployeehandbook.php" target=_top>HTML Document</a>]</li>
    </ul>
  </li>
</ul>

<?php
	include("find_admin_ip.inc");
	if (getenv("remote_addr") != $adminip) {
		exit;
	}
?>
<ul>
    <ul>
      <li>
         [<a onMouseOver="self.status='HTML Document'; return true" onMouseOut="self.status=''; return true"
         	href="http:/<?php echo $phpdir ?>/rlaemployeehandbook_parse.php" target=_blank>Parse Employee Handbook</a>]</li>
      <li>
         <a onMouseOver="self.status='Microsoft Word Document'; return true" onMouseOut="self.status=''; return true"
         	href="http:/rladoc/Employee Handbook V2.doc" target=_blank>[Word Format</a>]</li>
    </ul>
</ul>
</body>
