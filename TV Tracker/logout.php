<?php session_start(); session_unset(); session_destroy(); $_SESSION['LoggedIn'] = 0; ?>
<html>
	<head>
		<meta http-equiv="refresh" content="0;index.php">
	</head>
</html>

<body>
	<h1>Logging out and redirecting...</h1>
</body>