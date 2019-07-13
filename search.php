<?php
ini_set('max_execution_time', 0);
session_start();

if(!isset($_SESSION["user_name"]))
{
header("Location:index.php");
exit();
}

require "database_connection.php";
$movie=urlencode($_REQUEST['movie']);

if (isset($_GET['pageno'])) {
            $pageno = $_GET['pageno'];
        } else {
            $pageno = 1;
        }
		
echo"<center><h2>HomePage</h2></center>";
echo"<form action='search.php' method='post'>";
echo"<fieldset>";
echo"<label for='movie'>Enter Movie Name</label>";
echo"<input type='text' name='movie' value='".$_REQUEST['movie']."' size=50/>";

echo"<input type='submit' value='Search' />";
echo"</fieldset>";
echo"</form>";


$details=array(array(array()));


	for($j=0;$j<20;$j++)
	{
$url="https://api.themoviedb.org/3/search/movie?query=".$movie."&api_key=bd6253e44a43d70f2afc69233f68ebb9&page=".$pageno;
$json=file_get_contents($url);
$details=json_decode($json,true); 

echo"<div class='hvrbox'>";

echo "<img src=http://image.tmdb.org/t/p/w500" .$details["results"][$j]["poster_path"].
		" class='hvrbox-layer_bottom' width=240 height=360 >";
	echo"<div class='hvrbox-layer_top'>";
			echo"<div class='hvrbox-text'>";
			echo "<h3>".$details["results"][$j]['title']."</h3>".
			substr($details["results"][$j]['overview'],0,200)."<h3>RELEASE: ".$details["results"][$j]['release_date']."</h3>";
				
				$select=$mysqli->prepare("SELECT * FROM movies WHERE user_name=?  and movie_id=?");
				$user_name=$_SESSION['user_name'];
				$movie_id=$details["results"][$j]['id'];
				$select->bind_param("ss",$user_name,$movie_id);
				$select->execute();
				$result=$select->get_result();
				$lcolor=$fcolor=$wcolor='white';
				while($row=$result->fetch_assoc())
				{
					if($row['click_type']=='like')
						$lcolor='yellow';
					else if($row['click_type']=='watched')
						$wcolor='yellow';
					else if($row['click_type']=='favourite')
						$fcolor='yellow';
				}
				$select->close();
				
			echo "<button type='button' onclick=\"(function(){
					
					var modal=document.getElementById('myModal');
					var content = document.getElementById('modal-content');
					if(modal!=null && content!=null)
					{
					var video;
					modal.style.display='block';
					
					$.get('https://www.googleapis.com/youtube/v3/search?part=snippet&maxResults=1&q=".str_replace(" ","+",$details["results"][$j]['title'])."+trailer&key=AIzaSyDbtnCZCbIPDxApzMYkQ5m_7JV_6f5n08Q',
						function(response)
						{
							
							if(response && response.items) 
							{
								
								var items = response.items;
								if(items.length>0) 
								{
									
									var item = items[0];
									var videoid = 'https://www.youtube.com/embed/'+item.id.videoId+'?enablejsapi=1&version=3&playerapiid=ytplayer';
									
									video = '<iframe width=426 height=240 src='+videoid+' frameborder=0 allowfullscreen class=trailer allowscriptaccess=always></iframe>';
									
									
									$.getJSON('http://www.omdbapi.com/?apikey=dfdae050&t='+encodeURI('{$details["results"][$j]['title']}')).then(function(response){
									console.log(response);	
									
									content.innerHTML=
									'<h3>'+response['Title']+'</h3>'+
									'YEAR: '+response['Year']+'<br/>'+
									'RELEASED: '+response['Released']+'<br/>'+
									'GENRE: '+response['Genre']+'<br/><br/>'+
									'DIRECTOR: '+response['Director']+'<br/>'+
									'WRITER: '+response['Writer']+'<br/>'+
									'ACTORS: '+response['Actors']+'<br/><br/>'+
									'PLOT: '+'".str_replace("'","",str_replace("\"","",$details["results"][$j]['overview']))."'+'<br/><br/>'+
									'RATE: '+response['Rated']+'<br/>'+
									'IMdb: '+response['imdbRating']+'<br/>'+
									'<input type=button style=\'position:relative;left:410px; background-color:".$lcolor.";\' value=Like class=like id=like_{$details["results"][$j]['id']} onclick=action(\'like_{$details["results"][$j]['id']}\') />'+
									'<input type=button style=\'position:relative; left:430px; background-color:".$fcolor.";\' value=Favourite class=favourite id=favourite_{$details["results"][$j]['id']} onclick=action(\'favourite_{$details["results"][$j]['id']}\') />'+
									'<input type=button style=\'position:relative; left:450px; background-color:".$wcolor.";\' value=Watched class=watched id=watched_{$details["results"][$j]['id']} onclick=action(\'watched_{$details["results"][$j]['id']}\') />'+
									'<center>'+video+'</center>';
	
					});
								}
							}
						});
					
					
					
					
					}
					
				   })()\">VIEW MORE</button>";
			echo"</div>";
	echo"</div>"	;	

echo"</div>";
	}
//var_dump($details);

?>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Homepage Page : <?php echo $pageno?></title>
<link href="homepage3.css" type="text/css" rel="stylesheet">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script>
function action(id)
	{
         
        var split_id = id.split("_");

        var type = split_id[0];
        var movie_id = split_id[1]; 
		var user_name='<?php echo $_SESSION["user_name"];?>';
		//alert(type +' and '+ movie_id+' and '+user_name);
        // AJAX Request
        $.ajax({
            url: 'fill_movies.php',
            type: 'POST',
			data: {movie_id: movie_id , type: type , user_name: user_name},
            success: function(data){
                if(type=='like')
				{
					
					
					$("#like_"+movie_id).css("background-color","yellow");
				}
				if(type=='favourite')
				{
					
					
					$("#favourite_"+movie_id).css("background-color","yellow");
				}
				if(type=='watched')
				{
					
					
					$("#watched_"+movie_id).css("background-color","yellow");
				}
				
            }
			
            
        });

    }
window.onclick = function(event) 
{
	var modal = document.getElementById("myModal");
var content = document.getElementById("modal-content");
		  if (event.target == modal)
			  {
			modal.style.display = "none";
			content.innerHTML="";
			$('.trailer').each(function(){
			  this.contentWindow.postMessage('{"event":"command","func":"stopVideo","args":""}', '*')
			});
			}
  }
</script>
</head>
<body id="main">
	<ul class="pagination">
			<li><a href="?pageno=1">First</a></li>
			<li class="<?php if($pageno <= 1){ echo 'disabled'; } ?>">
				<a href="<?php if($pageno <= 1){ echo '#'; } else { echo "?pageno=".($pageno - 1); } ?>">Prev</a>
			</li>
			<li class="<?php if($pageno >= 100){ echo 'disabled'; } ?>">
				<a href="<?php if($pageno >= 100){ echo '#'; } else { echo "?pageno=".($pageno + 1); } ?>">Next</a>
			</li>
	</ul>

		<div id='myModal' class='modal'>
			<div class="modal-content" id="modal-content">
			</div>
		</div>
</body>			
	
</html>
