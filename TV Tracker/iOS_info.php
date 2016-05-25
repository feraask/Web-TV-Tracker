<?php include "iOS_connect.php"; ?>
<?php include "iOS_imdb.php"; ?>
<?php
//This page should display more information about a specific show found in the search bar or in a user's list
//i.e. plot, runtimes, seasons, number of epsiodes, year etc...

	//Get the id passed in from the search
	$id = $_GET['id'];
	
	//Run query to get relevant data based off the title_id passed in. Returns 3 rows since some shows have more than one genre.
	//Separate queries are run for relavant data so if one is missing it does not destroy the data
	$query = mysql_query("SELECT title.title AS title, title.series_years AS years
				FROM title
				WHERE title.id = " . $id . " AND
				title.kind_id = 2
				LIMIT 3"
				);
				
	$ratings_query = mysql_query("SELECT ratings.info AS rating, votes.info AS num_votes
				FROM title, movie_info_idx AS votes, movie_info_idx AS ratings
				WHERE title.id = " . $id . " AND
				title.id = votes.movie_id AND
				votes.movie_id = ratings.movie_id AND
				title.kind_id = 2 AND
				ratings.info_type_id = 101 AND
				votes.info_type_id = 100
				LIMIT 3"
				);
				
	$plot_query = mysql_query("SELECT plot.info AS plot
				FROM title, movie_info AS plot
				WHERE title.id = " . $id . " AND
				plot.movie_id = title.id AND
				title.kind_id = 2 AND
				plot.info_type_id = 98
				LIMIT 3"
				);	
				
	$genre_query = mysql_query("SELECT genre.info AS genre
				FROM title, movie_info AS genre
				WHERE title.id = " . $id . " AND
				genre.movie_id = title.id AND
				title.kind_id = 2 AND
				genre.info_type_id = 3
				LIMIT 3"
				);
				
	$runtime_query = mysql_query("SELECT runtime.info AS runtime
			FROM title, movie_info AS runtime
			WHERE title.id = " . $id . " AND
			runtime.movie_id = title.id AND
			title.kind_id = 2 AND
			runtime.info_type_id = 1
			LIMIT 3"
			);
			
	$ep_seasons_query = mysql_query("SELECT MAX(season_nr) AS seasons, COUNT(episode_nr) AS episodes
			FROM title
			WHERE episode_of_id = " . $id);
	
	//Store the reslts into variables
	$tv_info = mysql_fetch_array($query);
	$ratings = mysql_fetch_array($ratings_query);
	$plot = mysql_fetch_array($plot_query);
	$genre = mysql_fetch_array($genre_query);
	$runtime = mysql_fetch_array($runtime_query);
	$ep_seasons = mysql_fetch_array($ep_seasons_query);
	
	//Check if the show is still airing, if so then the end year must be ???? so replace it with the word Present
	// to display something like 2002 - Present rather than 2002 - ????
	if(stripos($tv_info['years'], "?"))
	{
		$tv_info['years'] = substr($tv_info['years'], 0, 4) . " - Present";
	}
	
	$imdb = new Imdb();
	$movieArray = $imdb->getMovieInfo($tv_info['title']);

	echo "$-$-$";
	echo $tv_info['title'];
	//echo $movieArray['title'];
	
	echo "$-$-$";
	//display the first genre read in by the original query
	echo $genre['genre'];
	
	//Loop through if there are more rows (some shows have multiple genres) and display them
	while($data = mysql_fetch_array($genre_query))
	{					
		echo " | " . $data['genre'];
	}
	echo "$-$-$$$--runtime--$$$-$-$";
	echo $runtime['runtime'];
	
	echo "$-$-$";
	echo $ratings['rating'];
	
	echo "$-$-$";
	echo $ratings['num_votes'];
	
	echo "$-$-$";
	echo $plot['plot'];
	
	echo "$-$-$";
	echo $ep_seasons['seasons'];
	
	echo "$-$-$";
	echo $ep_seasons['episodes'];
	
	echo "$-$-$";
	echo $movieArray['poster'];
?>