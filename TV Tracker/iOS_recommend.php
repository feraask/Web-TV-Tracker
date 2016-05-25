<?php 
	include "iOS_connect.php";
	/*$recommendations_query = mysql_query("SELECT DISTINCT(ratings_dist.movie_id) AS id, ratings_dist.title as title, ratings_dist.series_years AS series_years, SQRT(ratings_distance+POW((1 - num_genres_in_top/num_genres_in_show)*10, 2)) AS euclid_dist
									FROM
									(
										SELECT title.id AS movie_id, title.title AS title, title.series_years AS series_years, POW((10-ratings.info), 2) AS ratings_distance
										FROM title, movie_info_idx AS ratings, movie_info_idx AS votes
										WHERE ratings.info >= 7 AND title.id = ratings.movie_id AND ratings.info_type_id = 101
										AND votes.info > 5000 AND votes.movie_id = title.id
										AND title.id NOT IN
										        (
										        	SELECT movie_id as id FROM z_" . $_REQUEST['UserID'] . "        
										        )
									) AS ratings_dist
									
									INNER JOIN
									
									(
										SELECT movie_id, num_genres_in_top
										FROM
										(
											SELECT genre.movie_id AS movie_id, COUNT(genre.info) AS num_genres_in_top
											FROM title, movie_info AS genre
											WHERE title.id = genre.movie_id AND genre.info_type_id = 3 AND title.kind_id = 2 AND title.id NOT IN
											(
												SELECT movie_id FROM z_" . $_REQUEST['UserID'] . "
											)
											AND genre.info IN
											(
											 	SELECT genre FROM
											 	(
													SELECT user_genre_freq.genre_freq/genre_freq.frequency as freq_relation, genre_freq.genre as genre FROM genre_freq,
											        	(
											        		SELECT movie_info.info AS genre, COUNT(movie_info.info)/13 AS genre_freq
											             	FROM movie_info, title, z_" . $_REQUEST['UserID'] . " 
											             	WHERE movie_info.info_type_id = 3 AND movie_info.movie_id = title.id AND title.kind_id = 2 AND z_" . $_REQUEST['UserID'] . ".movie_id = title.id
											             	GROUP BY movie_info.info
											             ) AS user_genre_freq
											        WHERE user_genre_freq.genre = genre_freq.genre
											        ORDER BY freq_relation DESC
											        LIMIT 5
											    ) AS top_genres
											)
											GROUP BY genre.movie_id
										) AS top_genres_2
									) AS num_genres_in_top
									ON num_genres_in_top.movie_id = ratings_dist.movie_id
									
									INNER JOIN
									
									(
										SELECT genre.movie_id AS num_genre_id, IF(COUNT(genre.info) > 5, 5, COUNT(genre.info)) AS num_genres_in_show
									    FROM title, movie_info AS genre
									    WHERE title.id = genre.movie_id AND genre.info_type_id = 3 AND title.kind_id = 2 AND title.id NOT IN
									    (
									    	SELECT movie_id FROM z_" . $_REQUEST['UserID'] . "
									    )
									    GROUP BY genre.movie_id
									) AS num_genres_in_show
									
									ON num_genres_in_show.num_genre_id = ratings_dist.movie_id
									
									LIMIT 0,10");*/
									
		echo "$-$-$";
		//$num=mysql_num_rows($recommendations_query);
		//echo $num;
		
		/*while($row = mysql_fetch_array($recommendations_query))
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
									
			echo "$-$-$";
			//echo $row['id'];
			echo "$-$-$";
			//echo $row['title'];
			echo "$-$-$";
			//echo $row['series_years'];
		}*/
		include "iOS_disconnect.php";		
?>