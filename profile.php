<?php
session_start();

if(!isset($_SESSION["user_name"]))
{
header("Location:index.php");
exit();
}

require "database_connection.php";


$user_name=$_SESSION['user_name'];
$like='like';
$favourite='favourite';
$watched='watched';
echo"<h3>Liked Movies</h3>";
$select=$mysqli->prepare("SELECT * FROM movies where user_name=? and click_type=?");
$select->bind_param("ss",$user_name,$like);
$select->execute();
$result=$select->get_result();

while($row=$result->fetch_assoc())
{
	$url="https://api.themoviedb.org/3/movie/".$row['movie_id']."?&api_key=bd6253e44a43d70f2afc69233f68ebb9";
	$json=file_get_contents($url);
	$details=json_decode($json,true); 
	
	echo "<img src=http://image.tmdb.org/t/p/w500" .$details['poster_path']." height=240 width=160>";
	
}

$select->close();

echo"<h3>Favourite Movies</h3>";
$select=$mysqli->prepare("SELECT * FROM movies where user_name=? and click_type=?");
$select->bind_param("ss",$user_name,$favourite);
$select->execute();
$result=$select->get_result();

while($row=$result->fetch_assoc())
{
	$url="https://api.themoviedb.org/3/movie/".$row['movie_id']."?&api_key=bd6253e44a43d70f2afc69233f68ebb9";
	$json=file_get_contents($url);
	$details=json_decode($json,true); 
	
	echo "<img src=http://image.tmdb.org/t/p/w500" .$details['poster_path']." height=240 width=160>";
	
}

$select->close();

echo"<h3>Watched Movies</h3>";
$select=$mysqli->prepare("SELECT * FROM movies where user_name=? and click_type=?");
$select->bind_param("ss",$user_name,$watched);
$select->execute();
$result=$select->get_result();

while($row=$result->fetch_assoc())
{
	$url="https://api.themoviedb.org/3/movie/".$row['movie_id']."?&api_key=bd6253e44a43d70f2afc69233f68ebb9";
	$json=file_get_contents($url);
	$details=json_decode($json,true); 
	
	echo "<img src=http://image.tmdb.org/t/p/w500" .$details['poster_path']." height=240 width=160>";
	
}

$select->close();






?>