<?php include "../base.php" ?>
<?php
	$show  = $_POST['show'];
	$user  = $_POST['user'];
	$value = $_POST['value'];
	
	$query = mysql_query("UPDATE z_". $user ." SET ep_count=" . $value . " WHERE movie_id=". $show);
	
?>
