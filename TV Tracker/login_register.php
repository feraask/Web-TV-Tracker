<?php include "base.php"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

	<!-- Setup highlight for the navigation bar to highlight the current page -->
	<head>
		<style type="text/css">
			#page2
			{
				color:                  black;
       	 			background-color:       DodgerBlue;
			}
		</style>
		
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />	
	</head>

	<body>
		<!-- Contains the main content of the page -->
		<div class="page_content">
			<!-- Main Content and Important Headlines Go Here -->
			<div class="main_info" id="main_content">
				<div class="sub_content">
					<?php
						//Check if their is already a user session, if so display their shows
						if(!empty($_SESSION['LoggedIn']) && !empty($_SESSSION['Username']))
						{
							echo "<h1>My Shows:</h1>";
							echo "<p>Thanks for logging on! You are: <b>" . $_SESSION['Username'] . "</b> and Email: <b>" . $_SESSION['Email'] . "</b></p>";
						}
						//If a user has submitted a login form, set the session variables and log them on
						elseif(!empty($_REQUEST['username']) && !empty($_REQUEST['password']))
						{
							$username = mysql_real_escape_string($_REQUEST['username']);
							$password = md5(mysql_real_escape_string($_REQUEST['password']));
							$checklogin = mysql_query("SELECT * from users WHERE Username = '" . $username . "' AND Password = '" . $password . "'");
							if(mysql_num_rows($checklogin) == 1)
							{
								$row = mysql_fetch_array($checklogin);
								$email = $row['EmailAddress'];
								$userid = $row['UserID'];
								
								$_SESSION['Username'] = $username;
								$_SESSION['EmailAdress'] = $email;
								$_SESSION['LoggedIn'] = 1;
								$_SESSION['UserID'] = $userid;
								
								echo "<h1>Success</h1>";
								echo "<p>We are now redirectiong your shows page...</p>";
								echo "<meta http-equiv=\"refresh\" content=\"2;my_shows.php\">";
							}
							else
							{
								echo "<h1>ERROR</h1>";
								echo "<p>Sorry, incorrect Username or Password. Please <a style=\"text-decoration:underline;\" href=\"login_register.php\">click here to try again</a></p>";
							}
						}
						else
						{
							echo "<h3>Welcome to the member area, please login below or <a style=\"text-decoration:underline;\" href=\"register.php\">click here to register</a></h3>
							<form method=\"post\" action=\"login_register.php\" name=\"loginform\" id=\"loginform\">
							<fieldset style=\"width:300px;margin:auto;text-align:right;\">
								<label for=\"username\">Username:</label>
								<input type=\"text\" name=\"username\" id=\"username\"><br> 
								<label for=\"password\">Password:</label>
								<input type=\"password\" name=\"password\" id=\"password\"><br>
								<input type=\"submit\" name=\"login\" id=\"login\" value=\"Login\">
							</fieldset>
							</form>";
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