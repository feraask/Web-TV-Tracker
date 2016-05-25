<?php

	include "iOS_connect.php";
	$query = mysql_query("SELECT user.movie_id AS id, title.title AS title, 
						title.series_years AS series_years, user.user_rating AS rating, user.ep_count AS ep_count,
						user.season_count AS season_count 
						FROM z_" . $_GET['UserID'] . " AS user, title 
						WHERE user.movie_id = title.id AND title.kind_id = 2
						LIMIT 24");
	echo "$-$-$";
	echo mysql_num_rows($query);
	`				
	if(mysql_num_rows($query) > 0)
	{					
		while($row = mysql_fetch_array($query))
		{
			//Check if the show is still airing, if so then the end year must be ???? so replace it with 
			// the word Present to display something like 2002 - Present rather than 2002 - ????
			if(stripos($row['series_years'], "?"))
			{
				$row['series_years'] = substr($row['series_years'], 0, 4) . " - Present";
			}
			else
			{
				$row['series_years'] = substr($row['series_years'], 0, 4) . " - " . substr($row['series_years'],5);	
			}
			//Display the results in a table (Title, Series Years, My Rating, Curr Season, Eps. Watched)
			echo "$-$-$";
			echo $row['id'];
			echo "$-$-$";
			echo $row['title'];
			echo "$-$-$";
			echo $row['series_years'];
		}
	}
	
	include "iOS_disconnect.php";
?>