<?php include "base.php"?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<style type="text/css">
			#page5
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
					if(!empty($_REQUEST['username']) && !empty($_REQUEST['password']))
					{
						$username = mysql_real_escape_string($_REQUEST['username']);
						$password = md5(mysql_real_escape_string($_REQUEST['password']));
						$email = mysql_real_escape_string($_REQUEST['email']);
						
						$checkusername = mysql_query("SELECT * FROM users WHERE Username = '" . $username . "'");
						if(mysql_num_rows($checkusername) == 1)
						{
							echo "<h1>ERROR</h1>";
							echo "<p>Sorry, that username is already taken. Please go back and try again.</p>";	
						}
						else
						{
							$registerquery = mysql_query("INSERT INTO users (Username, Password, EmailAddress) VALUES('".$username."', '".$password."', '".$email."')");
							if($registerquery)
							{
								echo "<h1>Success!</h1>";
								echo "<p>Your account was successfully created. Please <a style=\"text-decoration:underline;\" href=\"login_register.php\">click here to login</a>.</p>";
							}
							else
							{
								echo "<h1>ERROR</h1>";
								echo "<p>Sorry, your registration failed. Please go back and try again.</p>";
							}
						}
					}
					else
					{
						echo "<h1>Register</h1>
						<p>Please enter your details below to register.</p>
							<form method=\"post\" action=\"register.php\" name=\"registerform\" id=\"registerform\">
	<fieldset style=\"text-align:right;width:300px;margin:auto;\">
		<label for=\"username\">Username:</label><input type=\"text\" name=\"username\" id=\"username\" /><br />
		<label for=\"password\">Password:</label><input type=\"password\" name=\"password\" id=\"password\" /><br />
        <label for=\"email\">Email:</label><input type=\"text\" name=\"email\" id=\"email\" /><br />
		<input type=\"submit\" name=\"register\" id=\"register\" value=\"Register\" />
	</fieldset>
	</form>";
					}
				?>
				</div>
			</div>		
			
		</div>
	</body>