<?php include "base.php" ?>
<html>
	<!-- Setup highlight for the navigation bar to highlight the current page -->
	<head>
		<style type="text/css">
			#page2
			{
				color:                  black;
       	 			background-color:       DodgerBlue;
			}
		</style>
	
	</head>
	<script type="text/javascript" src="js/jQuery.js"></script>          
	<script type="text/javascript">
		/* All functions have two inputs:
			id: refers to the unique show id
			user_id: refers to the user's unique id
		 */
	
	
		/* Used to increment episode count */
		function increment(id, user_id)
		{
			var $value = parseFloat(document.getElementById("episode_"+id).innerHTML);
			$value ++;
			document.getElementById("episode_"+id).innerHTML=$value;
			$.post("MyListPHP/increment_show.php", {show: id, user: user_id, value: $value }); // Updates database with new value of episode count
		}

		/* Used to decrement episode count */
		function decrement(id, user_id)
		{
			var $value = parseFloat(document.getElementById("episode_"+id).innerHTML);
			$value --;
			if($value < 0)
				$value = 0;
			document.getElementById("episode_"+id).innerHTML=$value;
			$.post("MyListPHP/decrement_show.php", {show: id, user: user_id, value: $value }); // Updates database with new value of episode count
		}
	
		/* Used to increment seasons */
		function increment_season(id, user_id)
		{
			/* Update season count */
			var $value = parseFloat(document.getElementById("season_"+id).innerHTML);
			$value ++;
			document.getElementById("season_"+id).innerHTML=$value;
			
			/* Reset episode count */
			document.getElementById("episode_"+id).innerHTML="0";
			$.post("MyListPHP/increment_season.php", {show: id, user: user_id, value: $value }); // Updates database with new value of season count; resets episode count
		}
		
		/* Used to decrement seasons */
		function decrement_season(id, user_id)
		{
			/* Update season count */
			var $value = parseFloat(document.getElementById("season_"+id).innerHTML);
			$value --;
			if($value < 1)
				$value = 1;
			document.getElementById("season_"+id).innerHTML=$value;
			
			/* Reset episode count */
			document.getElementById("episode_"+id).innerHTML="0";
			//$value +='';
			$.post("MyListPHP/decrement_season.php", {show: id, user: user_id, value: $value }); // Updates database with new value of season count; resets episode count

		}
		
		/* update user rating */
		function update_rating(id, user_id)
		{
			var $value = parseFloat(prompt("Enter a rating (0.0 to 10.0)"));
			if(isNaN($value) || $value > 10 || $value < 0){
				alert("That's not a valid rating!"); // Notify user that their attempt was invalid
			}
			else	{
				/* If the result is valid, update the user rating (rounding to 1 decimal place) */
				$value = Math.round( $value* 10 ) / 10;
				document.getElementById("rating_"+id).innerHTML = $value;
				$.post("MyListPHP/update_rating.php", {show: id, user: user_id, rating: $value});
			}
		}
	
	</script>


	<body>
		<!-- Contains the main content of the page -->
		<div class="page_content">
			<!-- Main Content and Important Headlines Go Here -->
			<div class="main_info" id="main_content">
				<div class="sub_content">
					<h1>My TV Shows</h1>
				<?php
					//Ensure that a user is logged in, otherwise display an error
					if(!empty($_SESSION['LoggedIn']))
					{
						$query = mysql_query("SELECT user.movie_id AS id, title.title AS title, 
						title.series_years AS series_years, user.user_rating AS rating, user.ep_count AS ep_count,
						user.season_count AS season_count 
						FROM z_" . $_SESSION['UserID'] . " AS user, title 
						WHERE user.movie_id = title.id AND title.kind_id = 2
						LIMIT 24");
						if(mysql_num_rows($query) > 0)
						{
							//Set up a table to output the results
							echo "<table border='1'><tr><th>TV Show</th><th>Year</th><th>My Rating</th><th>Current Season</th><th>Episodes Watched</th><th>Remove Show?</th></tr>";
							
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
						 		echo "<tr>";
							  	echo '<td><a class="show_link" href="show_info.php?id=' . $row['id'] . '">' . $row['title'] . '</a></td>';
							  	echo "<td>" . $row['series_years'] . "</td>";
							  	
							  	//Ugly php to display + and - buttons connected to javascript for ep_count and seasons
							  	echo "<td><button id=\"rating_".$row['id']."\"  onclick=\"update_rating('".$row['id']."','".$_SESSION['UserID']."')\" style=\"background:none;border:none;\">" . $row['rating'] . "</button></td>";
							  	echo "<td><div id=\"season_". $row['id'] ."\">" . $row['season_count'] . "</div><button class=\"inc_button\" onclick=\"increment_season('".$row['id']."','".$_SESSION['UserID']."')\" style=\"background:none;border:none;\">+</button><button class=\"dec_button\"  onclick=\"decrement_season('".$row['id']."','".$_SESSION['UserID']."')\" style=\"background:none;border:none;\">-</button></td>";
								echo "<td><div id=\"episode_".$row['id']."\">" . $row['ep_count'] . "</div><button class=\"inc_button\" onclick=\"increment('".$row['id']."','".$_SESSION['UserID']."')\" style=\"background:none;border:none;\">+</button><button class=\"dec_button\"  onclick=\"decrement('".$row['id']."','".$_SESSION['UserID']."')\" style=\"background:none;border:none;\">-</button></td>";
								
								echo "<td><a href=\"remove_show.php?id=" . $row['id'] . "&&title=" . $row['title'] . "\" style=\"color:red;font-size:2em;margin-left:40%;\">X</a></td>";				
							  	echo "</tr>";
							}
							echo "</table>";
						}
						else
						{
							echo "<h3>You have no TV Shows added. Find your favorites by searching at the top of the page!</h3>";
						}
					}
					else
					{
						echo "<h3>ERROR</h3><br><p>You are not logged in</p>";
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