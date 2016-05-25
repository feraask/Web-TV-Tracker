<?php
	include "iOS_connect.php";
	
	//If a user has submitted a login form, set the session variables and log them on
	if(!empty($_REQUEST['username']) && !empty($_REQUEST['password']))
	{
		$username = mysql_real_escape_string($_REQUEST['username']);
		$password = md5(mysql_real_escape_string($_REQUEST['password']));
		$checklogin = mysql_query("SELECT UserID from users WHERE Username = '" . $username . "' AND Password = '" . $password . "'");
		if(mysql_num_rows($checklogin) == 1)		
		{
			
			$row = mysql_fetch_array($checklogin);
			echo $row['UserID'];
			echo "$-$-$-$-$-$";
			echo "Success";
		}
		else
			echo "Fail";
	}
	include "iOS_disconnect.php"
?>