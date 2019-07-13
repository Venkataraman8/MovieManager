<?php
	
	require "database_connection.php";
	
	echo '<script language="javascript">';
	echo 'alert("message successfully sent")';
	echo '</script>';	
if(!empty($_POST["user_name"]) && !empty($_POST["movie_id"]) && !empty($_POST["type"]))
{	

	$user_name=$_POST['user_name'];
	$movie_id=$_POST['movie_id'];
	$type=$_POST['type'];
	
	
	$select=$mysqli->prepare("SELECT * FROM movies WHERE user_name=?  and movie_id=? and click_type=?");
	$select->bind_param("sss",$user_name,$movie_id,$type);
	$select->execute();
	if($select==false)
	{
		die("select fail");
	}
	$result=$select->get_result();
	$select->close();
	
	if($result==NULL)
	{
		$insert=$mysqli->prepare("INSERT INTO movies VALUES(?,?,?)");
		$insert->bind_param("sss",$user_name,$movie_id,$type);
		$insert->execute();
		if($insert==false)
	{
		die("insert fail");
	}
		$insert->close();
		
	}
}

?>