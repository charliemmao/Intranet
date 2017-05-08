<html>
<?php
echo "<hr><h2>Current available budget files</h2>";
$sql = "SELECT bgtfileidx, projcode_id, description, client, 
            begin_date, end_date, preparedby, uploaddate 
        FROM timesheet.bgtfileidx
        WHERE active='y'
        ORDER BY bgtfileidx DESC;";

    $result = mysql_query($sql);
    include("err_msg.inc");
    $no = mysql_num_rows($result);

    while (list($bgtfileidx, $projcode_id, $description, $client, 
            $begin_date, $end_date, $preparedby, $uploaddate) = mysql_fetch_array($result)) {
        echo "<table>
            <tr><td><b>PROJCODE CODE</b></td><td>".$brcode[$projcode_id]."</td></tr>
            <tr><td><b>DESCRIPTION</b></td><td>$description</td></tr>
            <tr><td><b>CLIENT</b></td><td>$client</td></tr>
            <tr><td><b>BEGIN_DATE</b></td><td>$begin_date</td></tr>
            <tr><td><b>END_DATE</b></td><td>$end_date</td></tr>
            <tr><td><b>PREPAREDBY</b></td><td>$preparedby</td></tr>
            <tr><td><b>UPLOADDATE</b></td><td>$uploaddate</td></tr>";
    
			if ($removebgtfile) {
				$userstr	=	"?".base64_encode($userinfo."&bgtfileidx=$bgtfileidx");
				echo "<tr><td colspan=2><h3><a href=\"ts_proj_budget_deactivedata.php$userstr\">
					Remove above budget file from DB.</a></h2></td></tr>";
			}
			echo "</table><p>";
    }
?>
</html>
