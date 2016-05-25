<?php
	include "iOS_connect.php";
	if(!empty($_REQUEST['username']) && !empty($_REQUEST['password']))
	{
		$username = mysql_real_escape_string($_REQUEST['username']);
		$password = md5(mysql_real_escape_string($_REQUEST['password']));
		$email = mysql_real_escape_string($_REQUEST['email']);
						
		$checkusername = mysql_query("SELECT * FROM users WHERE Username = '" . $username . "'");
		if(mysql_num_rows($checkusername) == 1)
		{
			echo "Taken";
			#echo "<p>Sorry, that username is already taken. Please go back and try again.</p>";	
		}
		else
		{
			$registerquery = mysql_query("INSERT INTO users (Username, Password, EmailAddress) VALUES('".$username."', '".$password."', '".$email."')");
			if($registerquery)
			{
				echo "Success";
				#echo "<p>Your account was successfully created. Please <a style=\"text-decoration:underline;\" href=\"login_register.php\">click here to login</a>.</p>";
			}
			else
			{
				echo "Error";
				#echo "<p>Sorry, your registration failed. Please go back and try again.</p>";
			}
		}
	}
	include "iOS_disconnect.php";
?>