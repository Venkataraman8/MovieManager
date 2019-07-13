<?php
session_start();

if(!isset($_SESSION["user_name"]))
{
header("Location:index.php");
exit();
}

require "database_connection.php";


?>
<html>
<head>
<Title>Dashboard</Title>
</head>
<body>
<h2>Welcome to Dashboard</h2>
<a href='homepage.php'>VIEW POPULAR MOVIES</a><br/><br/>
<a href='profile.php'>PROFILE PAGE</a><br/><br/>
</body>
</html>