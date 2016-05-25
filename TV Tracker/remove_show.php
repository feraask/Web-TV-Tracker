<?php include "base.php" ?>

<?php
	if($_SESSION['LoggedIn'] == 1)
	{
		echo "<h1>Removing the following show from your list:</h1>";
		$UserID = $_SESSION['UserID'];
			
		echo "<h3 style=\"display-inline;\">" . $_GET['title'] . "</h3>";
		
		$deletequery = mysql_query("DELETE FROM z_" . $UserID . " WHERE movie_id = " . $_GET['id']);
		
		//Set List Modified flag
		$setModified_query = mysql_query("UPDATE users SET list_modified = 1 WHERE UserID = " . $UserID);

				
		echo "<h3>Done removing, returning back to My Shows in 3 seconds</h3>";
		
		echo "<meta http-equiv=\"refresh\" content=\"3;my_shows.php\">";
	}
	else
	{
		echo "<h1>Not Logged In. Redirectiong to login page</h1>";
		
		echo "<meta http-equiv=\"refresh\" content=\"0;login_register.php\">";
	}
?>