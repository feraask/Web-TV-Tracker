<?php include "base.php" ?>
<?php include "imdb.php" ?>
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
?>

<html>
	<head>
		<style>
		#info_section
		{
			color:			red;
			font-size:		1.8em;
		}
		#text
		{
			font-size:		1.3em;
		}
		</style>
	</head>
	<body>
		<!-- Contains the main content of the page -->
		<div class="page_content">
			<!-- Display information about the TV Show -->
			<div class="main_info" id="main_content">
			
				<!-- Main Poster Image -->
				<div class="small_content" style="margin-left:-50%;">
					<img style="width:250px;height:400px;" src="imdbImage.php?url=<?=$movieArray['poster']?>" alt="Title Poster">
				</div>
				
				<!-- Main info starts -->
				<div class="small_content" style="margin-left:-20%;text-align:left;">
					<h1 style="max-width:70%;font-size:3em;"><?php echo $tv_info['title']?></h1>
					<h3 style="margin-left:5px;font-size:2em;">(<?php echo $tv_info['years']?>)</h3>
					<br>
					<p id="info_section"><b>Genre(s):</b></p>
					<p id="text">
						<?php 
							//display the first genre read in by the original query
							echo $genre['genre'];
							//Loop through if there are more rows (some shows have multiple genres) and display them
							while($data = mysql_fetch_array($genre_query))
							{					
								echo " | " . $data['genre'];
							}
						?>
					</p>
					<p id="info_section" style="margin-left:10px;"><b>Episode Runtime: </b></p>
					<p style="margin-left:3px;" id="text"> <?php echo $runtime['runtime']?> mins</p> 
					<br>
					<p style="background-color:yellow;padding:10px 10px 10px 10px;border-radius:10px;font-size:1.5em;"><b><?php echo $ratings['rating']?></b></p>
						<p id="info_section" style="margin-left:38%;"><b>Ratings: </p><p id="text" style="margin-left:10px;"><?php echo $ratings['rating']?></b>/10 from: <?php echo $ratings['num_votes']?> users</p>
					<br>
					<p id="info_section"><b>Plot/Description: </b></p><p id="text" style="margin-left:10px;"><?php echo $plot['plot']?></p>
					<br>
					<p id="info_section"><b>Number of Seasons: </b></p><p id="text" style="margin-left:10px;"><?php echo $ep_seasons['seasons']?></p><p id="info_section" style="margin-left:15%;"><b>Total Number of Episodes: </b></p><p id="text" style="margin-left:10px;"><?php echo $ep_seasons['episodes']?></p>
				</div>
			<!-- To be modified to display Cast info
				<div class="small_content" style="margin-left:-49%;margin-top:400px;text-align:left;">
					<p style="color:red;font-size:1.3em;"><b>Cast:</b></p>
				</div>
			-->

			</div>

		<!-- Footer for our webpage -->
		<div class="page_footer">
			<a href="https://wiki.engr.illinois.edu/display/cs411sp13/SQL+Soldiers">SQL Soldiers</a>
		</div>

	</body>

</html>