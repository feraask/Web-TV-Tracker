<?php include "base.php"?>

<!--About page-->

<!-- Additional CSS for the about page -->
<style type="text/css">
	#page4
	{
		color:                  black;
    		background-color:       DodgerBlue;
	}
	
	/* About Page Specifics */
	.paragraph_center {
		text-align: 		center;
	}
	
	.group_video {
	    margin: 			0 auto; 
	    width: 			640px;
	    display:			block;
	}
	
	/*button {
		background:none;
		border:none;
		font-size:20px;
	}*/
</style>


<!-- Main body -->
<div class="page_content"><div class="main_info" id="main_content">
	<br><br>
	
	<div id="main_body"><p>TV Tracker allows users to keep track of TV shows. This includes keeping track of shows you've seen, shows you want to see, as well as keeping track of episodes and seasons for shows that you're currently watching or that you have on hold. TV Tracker was built using a combination of HTML, CSS, PHP, and JavaScript. IMDbPy was used to obtain data from IMDB. Orbit was used in the home page. Additionally, we have a functioning iOS app built in Objective-C.</p>
	<br><br>
	<div class="paragraph_center"><p>Check out the video below for a TV Tracker demo.</p></div>
	
	<!--Group Video-->
	<video class="group_video" width="640" height="360" controls>
		<source src="movie.mp4" type="video/mp4">
		Your browser does not have HTML5 video support. :(
	</video>
	
	<div class="paragraph_center"><p>For more information about our team, the SQL Soldiers, or about our project, click <a href="https://wiki.engr.illinois.edu/display/cs411sp13/SQL+Soldiers">here</a>.</p></div></div></div>
	
</div>

<!-- Footer for our webpage -->
<div class="page_footer">
	<a href="https://wiki.engr.illinois.edu/display/cs411sp13/SQL+Soldiers">SQL Soldiers</a>
</div>