<?php include "../base.php" ?>
<?php
	$show  = $_POST['show'];
	$user  = $_POST['user'];
	$value = $_POST['value'];
	
	$query = mysql_query("UPDATE z_". $user ." SET season_count=" . $value . ", ep_count = 0 WHERE movie_id=". $show);
	//$query = mysql_query("UPDATE z_". $user ." SET ep_count=0 WHERE movie_id=". $show);
	
?>
