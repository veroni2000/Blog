<?php 
session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Post</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
	<nav>
		<ul>
			<li><a href="index.php">Home</a></li>
			<?php 
			include('db_connect.php');
			$post_id = $_GET['post_id'];
			$user_id;
			$post_owner=false;
			$admin=false;
			if(isset($_SESSION['user_id']))
			{
				$read_query = "SELECT * FROM  users WHERE user_id='".$_SESSION['user_id']."'";
				$result = mysqli_query($conn, $read_query);
				if (mysqli_num_rows($result)>0) {
					while($row = mysqli_fetch_assoc($result)){
						$user_id=$row['user_id'];
						if($row['role']=='admin')$admin=true;
						echo "<li><a href='user.php?user_id=".$row['user_id']."'>".$row['username']."</a><li>";
						?>
						<li style="float: right;"><a href="logout.php">Log out</a></li>
						<?php 
					} 
				} 
			}else {
				echo "<li style='float:right'><a href='login.php'>Log in</a></li>";
			}
			?>
		</ul>
	</nav>
	<main>
		<div id="divPost">
			<?php
			$read_query = "SELECT * FROM posts JOIN users ON posts.user_id=users.user_id WHERE post_id='".$post_id."'"; 
			$result = mysqli_query($conn, $read_query);
			if (mysqli_num_rows($result)>0) {
				while($row = mysqli_fetch_assoc($result)){
					if(isset($_SESSION['user_id'])&&$row['user_id']==$user_id){
						echo "<div id='divEditDelete'>
						<a href='update_post.php?post_id=" .$row['post_id'] . "'>Edit post</a> ";
						$post_owner=true;
					}
					if($post_owner||$admin){
						if($admin&&!$post_owner)
							echo "<div id='divEditDelete'>";
						echo " <a href='delete_post.php?post_id=".$row['post_id']."' style='color:red'>Delete post</a>
						</div>";
					}	
					echo '<h1>'.$row['title'].'</h1>';
					$post_time = $row['post_time'];
					$post_time = date("H:i (d.m.y)", strtotime($post_time));
					echo '<h2 id="postTime">'.$post_time.'</h2>';
					echo "<div id='PicUsername'>
					<a href='user.php?user_id=".$row['user_id']."' style='font-size:24px;'>
					<img src='images/".$row['image']."' id='profilePic' width='50px' height='50px'> "
					.$row['username']."</a></div><br>";
					echo '<p>'.$row['post_text'].'</p>';
				}
			}
			else return header('Location: index.php');
			echo "</div>"; 
			if(isset($_SESSION['user_id']))
			{
				?>
				<button onclick="addNewComment()">Add new comment</button>
				<div id="divNewComment" style="display: none;">
					<form method="post" action="" id="formNewComment">
						<textarea name="newComment" id="textNewComment" cols="150" rows="6"></textarea>
						<button type="submit" name="submit">Post</button>	
					</form>	
				</div>
				<?php
				if(isset($_POST['submit']))  
				{
					date_default_timezone_set('europe/sofia');
					$time=date("Y-m-d H:i:s");
					$newComment=str_replace("'","`",$_POST['newComment']);
					$insert_query = "INSERT INTO comments (post_id, user_id, comment, comment_time) VALUES ('$post_id','$user_id','$newComment','$time')";
					if(mysqli_query($conn, $insert_query)){
					} else{
						echo "ERROR: Could not able to execute $insert_query. " . mysqli_error($conn);
					}
				}
			}
			echo "<h3>Comments</h3>";
			$read_query = "SELECT * FROM comments JOIN users ON comments.user_id=users.user_id WHERE post_id='".$post_id."'"; 
			$result = mysqli_query($conn, $read_query);
			if (mysqli_num_rows($result)>0) {
				while($row = mysqli_fetch_assoc($result)){	
					echo "<div id='divComment'>";
					echo "<div id='PicUsername'>
					<a href='user.php?user_id=".$row['user_id']."' style='font-size:'>
					<img src='images/".$row['image']."' id='profilePic' width='30px' height='30px'> ".$row['username']."</a></div><br>";

					if(isset($_SESSION['user_id'])&&($row['user_id']==$user_id||$admin||$post_owner))
						echo "<a href='delete_comment.php?comment_id=" .$row['comment_id'] . "' style='color:red; float:right;'>Delete comment</a>";
					$comment_time = $row['comment_time'];
					$comment_time = date("H:i (d.m.y)", strtotime($comment_time));
					echo '<p id="commentTime">'.$comment_time.'</p>';
					echo '<p>'.$row['comment'].'</p>';
					echo "</div>";
				}
			}
			?>

		</main>
		<script>
			function addNewComment() {
				var x = document.getElementById("divNewComment");
				if (x.style.display === "none") {
					x.style.display = "block";
				} else {
					x.style.display = "none";
				}
			}
		</script>
	</body>
	</html>