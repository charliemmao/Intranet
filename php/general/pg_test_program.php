<html>

<?php

exit;
include("find_domain.inc");
echo $mydomain;

include("specialentrydef.inc");
echo bcpow( "123456789", "12");
// get host name from URL
preg_match("/^(http:\/\/)?([^\/]+)/i",
"http://www.php.net/index.html", $matches);
$host = $matches[2];
// get last two segments of host name
preg_match("/[^\.\/]+\.[^\.\/]+$/",$host,$matches);
echo "domain name is: ".$matches[0]."\n";
// the "i" after the pattern delimiter indicates a case-insensitive search
if (preg_match ("/php/i", "PHP is the web scripting language of choice.")) {
    print "A match was found.";
} else {
    print "A match was not found.";
}

  if (!isset($PHP_AUTH_USER)) {
    header("WWW-Authenticate: Basic realm=\"My Realm\"");
    header("HTTP/1.0 401 Unauthorized");
    echo "Text to send if user hits Cancel button\n";
    exit;
  } else {
    echo "<p>Hello $PHP_AUTH_USER.</p>";
    echo chdir("/home/users/");
    echo "<br>";
    echo getcwd();
    echo "<br>";
    echo chdir($PHP_AUTH_USER);
    echo "<br>";
	$output = `ls -al`;
	echo "<pre>$output</pre>";
	

    //echo "<p>You entered $PHP_AUTH_PW as your password.</p>";
  }

    $d = dir("/etc");
echo "Handle: ".$d->handle."<br>\n";
echo "Path: ".$d->path."<br>\n";
while($entry=$d->read()) {
   echo $entry."<br>\n";
}
$d->close();
  function authenticate() {
    header( "WWW-Authenticate: Basic realm=\"Test Authentication System\"");
    header( "HTTP/1.0 401 Unauthorized");
    echo "You must enter a valid login ID and password to access this resource\n";
    exit;
  }
 
  if (!isset($PHP_AUTH_USER) || ($SeenBefore == 1 && !strcmp($OldAuth, $PHP_AUTH_USER))) {
   authenticate();
  } 
  else {
   echo "<p>Welcome: $PHP_AUTH_USER<br>";
   echo "Old: $OldAuth";
   echo "<form action='$PHP_SELF' METHOD='POST'>\n";
   echo "<input type='hidden' name='SeenBefore' value='1'>\n";
   echo "<input type='hidden' name='OldAuth' value='$PHP_AUTH_USER'>\n";
   echo "<input type='submit' value='Re Authenticate'>\n";
   echo "</form>\n";
  }

echo "<a id=top><h1 align=center>Test PostgreSQL Database</a>";
echo "<a href=\"".$PHP_SELF."$admininfo\"><font size=2>[Refresh]</font></a>";
echo "</h1>";
echo "<hr><p>";
include("rla_functions.inc");

$output = `ls -al`;
echo "<pre>$output</pre>";

echo $HTTP_USER_AGENT."<br>";
echo $$REMOTE_PORT." REMOTE_PORT<br>";
/* Get the first character of a string  */
$str = 'This is a test.';
$first = $str{0};

/* Get the last character of a string. */
$str = 'This is still a test.';
$last = $str{strlen($str)-1};
echo $first."; ". $last ."<br>";

/* More complex example, with variables. */
class foo
{
    var $foo;
    var $bar;

    function foo()
    {
        $this->foo = 'Foo';
        $this->bar = array('Bar1', 'Bar2', 'Bar3');
    }
}

$foo = new foo();
$name = 'MyName';

echo <<<EOT
My name is "$name". I am printing some $foo->foo.
Now, I am printing some {$foo->bar[1]}.
This should print a capital 'A': \x41
EOT;

exit;
	//echo strlen("40257144430    10200007210019312492063908  0000000BRUSH-PAINT SPREE-ROKSET      6390 -  63MM                  +0000006000+00000000006     B   +0000002270+000001362000000+000000000001000      40257144430    001 00000000000000000000000000000000000000000000000                                                                                                                                                                                                        ");
//echo "here<br>";
exit;
include("pg_find_tabnamefrom_sql.inc");

$sql = "INSERT INTO alltypes VALUES ( 'PA',  'Hilda Blairwood', 3, 10.7, 4308.20, '9/8/1974', '9:00', '07/03/1996 10:30:00');";
echo "$sql<br>";
$table = find_pg_tablename($sql);
$sql = "select * from $table";
echo "$sql<br>";

//postgresql installation at /usr/lib/pgsql
// /var/lib/pgsql/data
//	$cstr = "user=$user dbname=$pgdb port=5432 password=";
/*
$user="root";
$pgdb="test";
include("pg_conn.inc");

$sql = "select * from friend";
$result = pg_exec("$sql");
include("pg_errmsg.inc");
include("pg_display_res_in_table.inc");
//echo "tty: ".pg_tty ($conn);
//echo "port: ".pg_port ($conn);

	include("pg_errmsg.inc");
//*/

?>
<hr><a href=#top>Back to top</a><br>
