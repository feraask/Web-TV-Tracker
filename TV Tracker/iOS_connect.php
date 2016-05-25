<?php
	
	$dbhost = "engr-cpanel-mysql.engr.illinois.edu";
	$dbname = "sqlsoldiers_TVTracker";
	$dbuser = "sqlsoldiers_OWN";
	$dbpass = "sqlsoldiers";
	
	mysql_connect($dbhost, $dbuser, $dbpass) or die("MySQL Error: " . mysql_error());
	mysql_select_db($dbname) or die("MySQL Error: " . mysql_error());
?>
