
<html>
<head>
<title></title>
</head>

<frameset border="0" frameborder="0" cols="16%,*">

    <frame src="left.php?<?php echo $url_query; ?>" name="nav">
    <frame src="<?php echo (empty($db)) ? 'main.php' : 'db_details.php'; ?>?<?php echo $url_query; ?>" name="phpmain">
</frameset>
</html>
