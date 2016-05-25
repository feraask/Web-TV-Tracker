<?php include "../base.php" ?>
<?php
	$show  = $_POST['show'];
	$user  = $_POST['user'];
	$value = $_POST['value'];
	
	echo "fuuuuu " . $show;

	$query = mysql_query("UPDATE z_". $user ." SET season_count=" . $value . " WHERE movie_id=". $show);
	$query = mysql_query("UPDATE z_". $user ." SET ep_count=0 WHERE movie_id=". $show);
	$setModified_query = mysql_query("UPDATE users SET list_modified = 1 WHERE UserID = " . $user);

?>
