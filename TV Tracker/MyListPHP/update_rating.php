<?php include "../base.php" ?>
<?php
	$show  = $_POST['show'];
	$user  = $_POST['user'];
	$rating = $_POST['rating'];
	
	$query = mysql_query("UPDATE z_". $user ." SET user_rating=" . $rating . " WHERE movie_id=". $show);
	
	$setModified_query = mysql_query("UPDATE users SET list_modified = 1 WHERE UserID = " . $user);

?>

// number_format($value, 1, ',')