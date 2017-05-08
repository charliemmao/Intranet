<html>
<?php
function find_pg_tablename($strsql) {
	$strsql = strtolower($strsql);
	$tmp = trim($strsql);
	$tab = explode(" ", $tmp);
	if ($tab[0] == "insert" || $tab[0] == "delete" || $tab[0] == "create") {
		return $tab[2];
	} elseif ($tab[0] == "update") {
		return $tab[1];
	} elseif ($tab[0] == "select") {
		return $tab[0];
	} else {
		return "no";
	}
}
?>
</html>