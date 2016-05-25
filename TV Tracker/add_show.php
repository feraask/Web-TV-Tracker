<?php include "base.php" ?>
<?php
//This script adds shows to a user's list after they search for it in the search bar.
//The show(s) to add should be coming from the $_GET variable passed in from the search results as ID's

//Loop through all the shows in the $_GET variable and add them to the user list
	echo "<h1>Adding your shows to the your list please wait...</h1>";
	
	foreach ($_GET as $title_id)
	{
		//Simply lists the title_ID's passed in for testing
		//echo $title_id;
		//echo "<br>";
		//If the show is not already in the user list, add it. Otherwise skip it
		$myShowsquery = mysql_query("SELECT movie_id FROM z_" . $_SESSION['UserID'] . " WHERE movie_id = " . $title_id . " LIMIT 1");
		if(mysql_num_rows($myShowsquery) == 0)
		{
			//Insert the data
			$insertquery = mysql_query("INSERT INTO z_" . $_SESSION['UserID'] . " (movie_id, season_count, ep_count) VALUES (" . $title_id . ", 1, 0)");
			//Set their list to be modified
			$setModified_query = mysql_query("UPDATE users SET list_modified = 1 WHERE UserID = " . $_SESSION['UserID']);

		}
	}
	
	echo "<h3>Done adding new shows. Redirecting back to My Shows...</h3>";
	echo "<meta http-equiv=\"refresh\" content=\"2;my_shows.php\">";
?>