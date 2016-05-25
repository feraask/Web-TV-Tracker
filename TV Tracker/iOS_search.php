<?php include "iOS_connect.php"; ?>
<?php include "iOS_imdb.php"; ?>
<?php
	$imdb = new Imdb();	
	
	#Set up the query that does the search
	$show=mysql_real_escape_string($_GET['show']);
	$query=mysql_query("SELECT title, id, series_years FROM title WHERE title LIKE '%$show%' AND kind_id=2 LIMIT 24");

	$num_row=mysql_num_rows($query);
	echo "$-$-$";
	echo $num_row;
	//For every row of the results output the information to the table
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
		$movieArray = $imdb->getMovieInfo($row['title']);

		/*Only first 23 rows shone
		if($x  > 23)
		break;
		*/
		//Display the results in a table (Title, Series Years, Add to list?)

		echo "$-$-$";
		echo $row['id'];
		echo "$-$-$";
		echo $row['title'];
		echo "$-$-$";
		echo $row['series_years'];
		echo "$-$-$";
		echo $movieArray['poster'];
		
	}
					
	include "iOS_disconnect.php";
?>