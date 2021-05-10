<?php 
session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
	<nav>
		<ul>
			<li><a class="active" href="index.php">Home</a></li>
			<?php 
			include('db_connect.php');
			if(isset($_SESSION['user_id']))
			{
				$read_query = "SELECT * FROM  users where user_id='".$_SESSION['user_id']."'";
				$result = mysqli_query($conn, $read_query);
				if (mysqli_num_rows($result)>0) {
					while($row = mysqli_fetch_assoc($result)){
						echo "<li><a href='user.php?user_id=".$row['user_id']."'>".$row['username']."</a><li>";
						?>
						<li style="float: right;"><a href="logout.php">Log out</a></li>
						<?php 
						$user_id=$row['user_id'];
					} 
				} 
			}else {
				echo "<li style='float: right;'><a href='login.php'>Log in</a></li>";
			}
			?>
		</ul>
	</nav>
	<main>
		<?php
		if(isset($_SESSION['user_id']))
		{
			?>
			<button onclick="makeNewPost()">New post</button>
			<div id="divNewPost" style="display: none;">
				<form method="post" action="" id="formNewPost">
					<label>Title</label>
					<input type="text" name="title" placeholder="Title">
					<label>Post</label>
					<textarea name="newPost" id="textNewPost" cols="150" rows="6"></textarea>
					<button type="submit" name="submit">Post</button>	
				</form>	
			</div>
			<?php
			
			if(isset($_POST['submit']))  
			{
				date_default_timezone_set('europe/sofia');
				$time=date("Y-m-d H:i:s");
				$newTitle=str_replace("'","`",$_POST['title']);
				$newPost=str_replace("'","`",$_POST['newPost']);
				$insert_query = "INSERT INTO posts (title, post_text, user_id, post_time) VALUES ('$newTitle','$newPost','$user_id','$time')";
				if(mysqli_query($conn, $insert_query)){
				} else{
					echo "ERROR: Could not able to execute $insert_query. " . mysqli_error($conn);
				}
			}
		}

		$read_query = "SELECT * FROM posts JOIN users ON posts.user_id = users.user_id ORDER BY posts.post_time DESC";
		$result = mysqli_query($conn, $read_query);
		if (mysqli_num_rows($result)>0) {
			while($row = mysqli_fetch_assoc($result)){
				echo "<div id='divPostsIndex'>
				<a href='user.php?user_id=".$row['user_id']."'>
				<img src='images/".$row['image']."' id='profilePic' height='40px' width='40px'>
				</a> ";
				echo"<a href='post.php?post_id=".$row['post_id']."'><b>".$row['title']."</b></a><br>";

				$post_time = $row['post_time'];
				$post_time = date("H:i (d.m.y)", strtotime($post_time));
				if($row['title']=='')
					echo"<a href='post.php?post_id=".$row['post_id']."'><b>".$post_time."</b></a><br>";
				else
					echo '<p id="commentTime">'.$post_time.'</p>';
				if(strlen($row['post_text'])>500)
					echo "<p>".substr($row['post_text'],0,500)."...</p>";
				else echo "<p>".$row['post_text']."</p>";
				echo "</div>";
			}
		}
		?>
	</main>
	<script>
		function makeNewPost() {
			var x = document.getElementById("divNewPost");
			if (x.style.display === "none") {
				x.style.display = "block";
			} else {
				x.style.display = "none";
			}
		}
	</script>
</body>
</html>