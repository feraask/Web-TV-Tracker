<?php
	session_start();
	
	$dbhost = "engr-cpanel-mysql.engr.illinois.edu";
	$dbname = "sqlsoldiers_TVTracker";
	$dbuser = "sqlsoldiers_OWN";
	$dbpass = "sqlsoldiers";
	
	mysql_connect($dbhost, $dbuser, $dbpass) or die("MySQL Error: " . mysql_error());
	mysql_select_db($dbname) or die("MySQL Error: " . mysql_error());
?>
<html>
	<head>
		<title>TV Tracker</title>
		<link rel="stylesheet" type="text/css" href="css/TVTracker_Style.css">
  		

		<!-- Meta data -->
		<meta name="description" content="Home page for the TV tracker website"/>
		<meta name="keywords" content="TV Tracker, CS 411, SQL, database, TV"/>
		<meta name="author" content="SQL Soldiers"/>

		<!-- Scripts -->
		<script type='text/javascript' src='front_page_javascript.js'></script>

		<!--[if lt IE 9]>
		<script src="html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		
	</head>
	<body>
		<!-- Header of our page -->
		<div class="page_header">
			<div class="header_content">
				<!-- Main navigation bar and Logo -->
				<!-- DO NOT DELETE THAT IMAGE BELOW-->
				<a href="http://sqlsoldiers.web.engr.illinois.edu/"><img class="logo" src="images/logo1.png"/></a>
				<ul class="nav_bar">
					<a href="http://sqlsoldiers.web.engr.illinois.edu/"><li class="page_button" id="page1" style="border-left:1px solid #373737;margin-left:6px">Home</li></a>
				<?php
					//If a user is logged in, change the header to go to their shows page
					if(!empty($_SESSION['LoggedIn']) && !empty($_SESSION['Username']))
					{
						echo "<a href=\"my_shows.php\"><li class=\"page_button\" id=\"page2\">My Shows</li></a>";
					}
					//Otherwise link to the login page
					else
					{
						echo "<a href=\"login_register.php\"><li class=\"page_button\" id=\"page2\">Login</li></a>";
					}
				?>
					<a href="recommend.php"><li class="page_button" id="page3">Recommend</li></a>
					<a href="about.php"><li class="page_button" id="page4">About</li></a>
				</ul>
				<ul class="nav_bar" id="right_nav">
				
					<!-- Search bar for TV Shows -->
					<form class="" id="main_content" name="input" action="search_results.php" method="get">
						<input class="search_box" type="text" name="show" placeholder="   Search shows">
						<input class="search_button" type="submit" value="Search" style="visibility:hidden">
					</form>

				
				<?php
					//If a user is logged in, change the header
					if(!empty($_SESSION['LoggedIn']) && !empty($_SESSION['Username']))
					{
						echo "<a href=\"logout.php\"><li class=\"page_button\" id=\"page5\" style=\"border-left:1px solid #373737;\">Hi " . $_SESSION['Username'] . ", logout?</li></a>";
					}
					else
					{
						echo "<a href=\"register.php\"><li class=\"page_button\" id=\"page5\" style=\"border-left:1px solid #373737;\">Sign Up</li></a>";
					}		
				?>
				</ul>
			</div>
		</div>
			</body>
</html>