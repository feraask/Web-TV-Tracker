<?php include "base.php" ?>
<html>
	<!-- Setup highlight for the navigation bar to highlight the current page -->
	<head>
		<style type="text/css">
			#page3
			{
				color:                  black;
       	 			background-color:       DodgerBlue;
			}
		</style>
	
	</head>

	<body>
		<!-- Contains the main content of the page -->
		<div class="page_content">
			<!-- Main Content and Important Headlines Go Here -->
			<div class="main_info" id="main_content">
				<div class="sub_content">
					<?php
					
					//Check if a user is logged in
					if($_SESSION['LoggedIn'] == 1)
					{
						//Make sure user has shows to recommend based off of
						$num_rows_query = mysql_query("SELECT movie_id FROM z_" . $_SESSION['UserID']);
						$num_shows = mysql_num_rows($num_rows_query);
						if($num_shows > 0 && $_SESSION['Rec_Set'] == 1)
						{
							echo "<h1>My Recommendations</h1>";						
							//Check if they have already received recommendations this session, and haven't modified their list
								echo "<table border='1'><tr><th>TV Show</th><th>Year</th>";
								echo "<th>Add to My Shows?</th></tr>";
								echo "<form name=\"add_shows\" action=\"add_show.php\" method=\"get\">";
								
								for($i = 0; $i < 10; $i++)
								{
									$stored_rec_query = mysql_query("SELECT title.id as id, title.title as title, title.series_years as series_years
									FROM title
									WHERE title.id = " . $_SESSION['Rec_' . $i]);
									$row = mysql_fetch_array($stored_rec_query);
									
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
									
									//Display the results in a table (Title, Series Years, Add to list?)
								 	echo "<tr>";
								  	echo '<td><a class="show_link" href="show_info.php?id=' . $row['id'] . '">' . $row['title'] . '</a></td>';
								  	echo "<td>" . $row['series_years'] . "</td>";
								  	//Store the id of the show as the checkbox value, so if it is checked and added we can find it
								  	echo "<td><input style=\"margin:auto;width:100%;\" type=\"checkbox\" name=\"" . $row['id'] . "\" value=\"" . $row['id'] . "\">";
								  	
								  	echo "</tr>";
								  	
	
									
								}
								echo "</table>";
								echo "<input type=\"submit\" value=\"Add Shows\">";
								echo "</form>";						
						}
						//Otherwise generate recommendations, and store them for this session
						elseif($num_shows > 0)
						{
								echo "<h1>My Recommendations</h1>";
								$recommendations_query = mysql_query("SELECT DISTINCT(ratings_dist.movie_id) AS id, ratings_dist.title as title, ratings_dist.series_years AS series_years, SQRT(ratings_distance+POW((1 - num_genres_in_top/num_genres_in_show)*10, 2)) AS euclid_dist
									FROM
									(
										SELECT title.id AS movie_id, title.title AS title, title.series_years AS series_years, POW((10-ratings.info), 2) AS ratings_distance
										FROM title, movie_info_idx AS ratings, movie_info_idx AS votes
										WHERE ratings.info >= 7 AND title.id = ratings.movie_id AND ratings.info_type_id = 101
										AND votes.info > 5000 AND votes.movie_id = title.id
										AND title.id NOT IN
										        (
										        	SELECT movie_id as id FROM z_" . $_SESSION['UserID'] . "        
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
												SELECT movie_id FROM z_" . $_SESSION['UserID'] . "
											)
											AND genre.info IN
											(
											 	SELECT genre FROM
											 	(
													SELECT user_genre_freq.genre_freq/genre_freq.frequency as freq_relation, genre_freq.genre as genre FROM genre_freq,
											        	(
											        		SELECT movie_info.info AS genre, COUNT(movie_info.info)/13 AS genre_freq
											             	FROM movie_info, title, z_" . $_SESSION['UserID'] . " 
											             	WHERE movie_info.info_type_id = 3 AND movie_info.movie_id = title.id AND title.kind_id = 2 AND z_" . $_SESSION['UserID'] . ".movie_id = title.id
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
									    	SELECT movie_id FROM z_" . $_SESSION['UserID'] . "
									    )
									    GROUP BY genre.movie_id
									) AS num_genres_in_show
									
									ON num_genres_in_show.num_genre_id = ratings_dist.movie_id
									
									ORDER BY euclid_dist
									LIMIT 0,10");
									
								echo "<table border='1'><tr><th>TV Show</th><th>Year</th>";
								echo "<th>Add to My Shows?</th></tr>";
								echo "<form name=\"add_shows\" action=\"add_show.php\" method=\"get\">";
								$x = 0;
								while($row = mysql_fetch_array($recommendations_query))
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
									
									//Display the results in a table (Title, Series Years, Add to list?)
								 	echo "<tr>";
								  	echo '<td><a class="show_link" href="show_info.php?id=' . $row['id'] . '">' . $row['title'] . '</a></td>';
								  	echo "<td>" . $row['series_years'] . "</td>";
								  	//Store the id of the show as the checkbox value, so if it is checked and added we can find it
								  	echo "<td><input style=\"margin:auto;width:100%;\" type=\"checkbox\" name=\"" . $row['id'] . "\" value=\"" . $row['id'] . "\">";
								  	
								  	echo "</tr>";
								  	
								  	//Store session variable so we only calculate recommendations once per session unless the list is modified
								  	switch ($x)
								  	{
								  		case 0:
											$_SESSION['Rec_0'] = $row['id'];
											break;
								  		case 1:
								  			$_SESSION['Rec_1'] = $row['id'];
											break;
										case 2:
											$_SESSION['Rec_2'] = $row['id'];
											break;
										case 3:
											$_SESSION['Rec_3'] = $row['id'];
											break;
										case 4:
											$_SESSION['Rec_4'] = $row['id'];
											break;
										case 5:
											$_SESSION['Rec_5'] = $row['id'];
											break;
										case 6:
											$_SESSION['Rec_6'] = $row['id'];
											break;
										case 7:
											$_SESSION['Rec_7'] = $row['id'];
											break;
										case 8:
											$_SESSION['Rec_8'] = $row['id'];
											break;
										case 9:
											$_SESSION['Rec_9'] = $row['id'];
											break;
										default:
											break;
								  	}
								  	$x++;
								}
								//Set stored session recommendation flag
								$_SESSION['Rec_Set'] = 1;
								echo "</table>";
								echo "<input type=\"submit\" value=\"Add Shows\">";
								echo "</form>";
								
								//Set modified flag back to zero since recommendations have been created
								$setModified_query = mysql_query("UPDATE users SET list_modified = 0 WHERE UserID = " . $_SESSION['UserID']);
						}
						else
						{
							echo "<h1>Add shows to your list to get recommendations!</h1>";
						}
					}
					//Otherwise only show the top 10 shows
					else
					{
						echo "<h1>Log in or Sign up to get personalized recommendations and find new favorite shows!</h1>";
					
					}
								
					
					?>
				</div>

			</div>


		</div>


		<!-- Footer for our webpage -->
		<div class="page_footer">
			<a href="https://wiki.engr.illinois.edu/display/cs411sp13/SQL+Soldiers">SQL Soldiers</a>
		</div>

	</body>

</html>