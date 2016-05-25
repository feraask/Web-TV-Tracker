<?php include "base.php"?>
<html>
	<body>
		<!-- Contains the main content of the page -->
		<div class="page_content">
			<!-- Main Content and Important Headlines Go Here -->
			<div class="main_info" id="main_content">
				<div class="sub_content">
					<p><b>Results:</b></p>
					<?php
						//Set up the query that does the search
						$show = mysql_real_escape_string($_GET['show']);
						$query=mysql_query("SELECT title, id, series_years FROM title WHERE title LIKE '%$show%' AND kind_id=2 LIMIT 24");

						$x = 0;
						//For every row of the results output the information to the table
						while($row = mysql_fetch_array($query))
					 	{
					 		//If there is at least 1 result, set up a form only on the first run
					 		if($x == 0)
					 		{
					 			//Set up a table to output the results
								echo "<table border='1'><tr><th>TV Show</th><th>Year</th>";
								if($_SESSION['LoggedIn'] == 1)
									echo "<th>Add to My Shows?</th>";
								echo "</tr>";
					 			if($_SESSION['LoggedIn'] == 1)
					 				echo "<form name=\"add_shows\" action=\"add_show.php\" method=\"get\">";
					 			$x++;
					 		}
					 		
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
						  	if($_SESSION['LoggedIn'] == 1)
						  		echo "<td><input style=\"margin:auto;width:100%;\" type=\"checkbox\" name=\"" . $row['id'] . "\" value=\"" . $row['id'] . "\">";
						  	
						  	echo "</tr>";
					  	}
						echo "</table>";
					  	//Close the form and add a submit button only if there are results (and a form was created)
						if($x > 0)
						{
							if($_SESSION['LoggedIn'] == 1)
							{
								echo "<input type=\"submit\" value=\"Add Shows\">";
								echo "</form>";
							}
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