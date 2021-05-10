<?php 
session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Profile</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
	<?php 
	include('db_connect.php');
	$profile_id = $_GET['user_id'];
	if(isset($_SESSION['user_id']))
	{
		?>
		<nav>
			<ul>
				<li><a href="index.php">Home</a></li>
				<?php
				$read_query = "SELECT * FROM  users where user_id='".$_SESSION['user_id']."'";
				$result = mysqli_query($conn, $read_query);
				if (mysqli_num_rows($result)>0) {
					while($row = mysqli_fetch_assoc($result)){
						echo "<li>";
						if($row['user_id']==$profile_id){
							echo "<a class='active' href='user.php?user_id=".$row['user_id']."'>".$row['username']."</a><li>";
							echo "<li><a href='update_user.php?user_id=" .$row['user_id'] . "'>Edit your profile</a></li>";
						}
						else {
							echo "<a href='user.php?user_id=".$row['user_id']."'>".$row['username']."</a><li>";
						}
						?>
						<li style="float: right;"><a href="logout.php">Log out</a></li>
						<?php 
					} 
				} 
				?>
			</ul>
		</nav>
		<main>
			<div id="divProfile">
				<?php
				$read_query = "SELECT * FROM users where user_id='".$profile_id."'"; 
				$result = mysqli_query($conn, $read_query);
				if (mysqli_num_rows($result)>0) {
					while($row = mysqli_fetch_assoc($result)){	
						echo '<h1>'.$row['username'].'</h1>';
						echo "<img src='images/".$row['image']."' aling='left'>";
						echo '<p>'.$row['description'].'</p>';
					} 
				}
				?>
			</div>
			
			<?php
			$read_query = "SELECT * FROM posts where user_id='".$profile_id."' ORDER BY post_time DESC"; 
			$result = mysqli_query($conn, $read_query);
			if (mysqli_num_rows($result)>0) {
				while($row = mysqli_fetch_assoc($result)){
					echo "<div id='divPostsIndex'>";
					echo '<p><a href="post.php?post_id='.$row["post_id"].'">'
					.$row['title'].
					'</a></p><br>';
					$post_time = $row['post_time'];
					$post_time = date("H:i (d.m.y)", strtotime($post_time));
					echo '<p id="commentTime" style="margin-top:-10px;">'.$post_time.'</p>';
					if(strlen($row['post_text'])>500)
						echo "<p>".substr($row['post_text'],0,500)."...</p>";
					else echo "<p>".$row['post_text']."</p>";
					echo "</div>";
				}
			}
		}else {
			header('Location:index.php');
		}
		?>
		
	</main>
</body>
</html>