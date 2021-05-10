<?php 
session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Edit post</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
	<?php 
	include('db_connect.php');
	$profile_id;
	$post_id=$_GET['post_id'];
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
						$profile_id=$row['user_id'];
						echo "<li><a href='user.php?user_id=".$row['user_id']."'>".$row['username']."</a><li>";
						?>
						<li style="float: right;"><a href="logout.php">Log out</a></li>
						<?php 
					} 
				} 
				?>
			</ul>
		</nav>
		<main>
			<?php
			$read_query = "SELECT * FROM  posts WHERE post_id=".$post_id;
			$result = mysqli_query($conn, $read_query);
			if (mysqli_num_rows($result)>0) {
				while($row = mysqli_fetch_assoc($result)){
					if($row['user_id']==$profile_id){
						?>
						<form method="post" action="" class="inline" id="formEditPost">
							<label>Title</label>
							<input type="text" name="title" value="<?= $row['title']?>">
							<label>Post</label>
							<textarea name="post_text" cols="150" rows="10"><?= $row['post_text']?></textarea>	
							<button type="submit" name="submit">Save</button>
							<input type="hidden" name="post_id" value="<?= $row['post_id']?>">	
						</form>
					</main>
					<?php 
				}
				else{
					header("Location:post.php?post_id=".$post_id);
				}
			}
			if (isset($_POST['submit'])) {
				$title=str_replace("'","`",$_POST['title']);
				$post_text=str_replace("'","`",$_POST['post_text']);
				$update_query = "UPDATE `posts` SET `title`= '$title', `post_text`= '$post_text' WHERE `post_id` = '$post_id' ";
				$result = mysqli_query($conn, $update_query);
				if ($result) {
					header("Location:post.php?post_id=".$post_id);
				} else {
					echo "Error: " . $update_query . " - " . mysqli_error($conn);
				}
			}
		}
	}else {
		header('Location:index.php');
	}
	?>
</body>
</html>