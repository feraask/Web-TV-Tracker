<?php include "base.php"?>
<html>
	<!-- Setup highlight for the navigation bar to highlight the current page -->
	<head>
		<style type="text/css">
			#page1
			{
				color:                  black;
       	 			background-color:       DodgerBlue;
			}
		</style>
		
		<link rel="stylesheet" href="css/orbit.css">
		
		<script src="http://sqlsoldiers.web.engr.illinois.edu/js/jquery.min.js" type="text/javascript"></script>
		<script src="http://sqlsoldiers.web.engr.illinois.edu/js/jquery.orbit.min.js" type="text/javascript"></script>
	</head>
	<body>
		<!-- Contains the main content of the page -->
		<div class="page_content">
			<!-- Main Content and Important Headlines Go Here -->
			<div class="main_info" id="main_content">
				<div id="featured"> 
					<img style="width:945px;height:550px;" src="orbit/tvtrackerorbit.png" alt="fuuuu"/>

				     <img style="width:945px;height:550px%;" src="http://1.bp.blogspot.com/-GleAOzCgv58/UUvU7AFpjRI/AAAAAAAAALk/sh8BajwzG-s/s1600/Game-of-Thrones1.png" alt="Overflow: Hidden No More" />
				     <img style="width:945px;height:550px;"src="http://images1.wikia.nocookie.net/__cb20120902232838/tardis/images/f/f1/Doctor-who-logo-nine.jpg"  alt="HTML Captions" />
				     <img style="width:945px;height:550px;" src="http://www.kbsez.com/wp-content/uploads/2012/01/sherlock1.jpg" alt="and more features" />
				     <img style="width:945px;height:550px;" src="http://www.empowernetwork.com/dbowen/files/2013/03/breaking-bad.jpg" alt="Breaking Bad" />

				</div>
				
				<script type="text/javascript">
				     $(window).load(function() {
				         $('#featured').orbit();
				     });
				</script>
	
			</div>

		</div>


		<!-- Footer for our webpage -->
		<div class="page_footer">
			<a href="https://wiki.engr.illinois.edu/display/cs411sp13/SQL+Soldiers">SQL Soldiers</a>
		</div>
	</body>

</html>