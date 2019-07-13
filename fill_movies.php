<?php
	
	require "database_connection.php";
	

if(!empty($_POST["user_name"]) && !empty($_POST["movie_id"]) && !empty($_POST["type"]))
{	

	$user_name=$_POST["user_name"];
	$movie_id=$_POST["movie_id"];
	$type=$_POST["type"];
	echo $user_name;
	echo $movie_id;
	echo $type;
	
	$select=$mysqli->prepare("SELECT * FROM movies WHERE user_name=?  and movie_id=? and click_type=?");
	$select->bind_param("sss",$user_name,$movie_id,$type);
	$select->execute();
	$result=$select->get_result();
	$select->close();
	echo"success";
	
	if($result->fetch_assoc()==NULL)
	{
		echo "1";
		$insert=$mysqli->prepare("INSERT INTO movies VALUES(?,?,?)");
		$insert->bind_param("sss",$user_name,$movie_id,$type);
		$insert->execute();
		

		$insert->close();
		
	}
}

?>