<?php 
session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Edit profile</title>
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
							echo "<a href='user.php?user_id=".$row['user_id']."'>".$row['username']."</a><li>";
							echo "<li><a class='active' href='update_user.php?user_id=" .$row['user_id'] . "'>Edit your profile</a></li>";
						}
						else {
							header('Location:user.php?user_id='.$profile_id);
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
			<?php
			$read_query = "SELECT * FROM  users where user_id='".$_SESSION['user_id']."'";
			$result = mysqli_query($conn, $read_query);
			if (mysqli_num_rows($result)>0) {
				while($row = mysqli_fetch_assoc($result)){
					?>
					<div id="editUser">
						<form method="post" action="" class="inline" id="formEditUser" style="width: 40%">
							<label>Username</label>
							<input type="text" name="username" value="<?= $row['username']?>">
							<label>Email</label>
							<input type="email" name="email" value="<?= $row['email']?>">
							<label>Description</label>
							<input type="text" name="description" value="<?= $row['description']?>">
							<button type="submit" name="submit">Save</button>	
							<input type="hidden" name="user_id" value="<?= $row['user_id']?>">
						</form>

						<form method="POST" action="" enctype="multipart/form-data" id="formPicture" style="width: 30%">
							<?php
							echo "<img src='images/".$row['image']."' height='170px'>";
							?>
							<input type="file" name="uploadfile" value="" accept="image/*"/>
							<button type="submit" name="upload">Upload</button>
						</form>
					</div>
				</main>
				<?php 
			}
		}
		if (isset($_POST['submit'])) {
			$username=str_replace("'","`",$_POST['username']);
			$email=str_replace("'","`",$_POST['email']);
			$description=str_replace("'","`",$_POST['description']);
			$update_query = "UPDATE `users` SET `username`= '$username', `email`= '$email', `description`= '$description' WHERE `user_id` = '$profile_id' ";
			$result = mysqli_query($conn, $update_query);
			if ($result) {
				header("Location:user.php?user_id=".$profile_id);
			} else {
				echo "Error: " . $update_query . " - " . mysqli_error($conn);
			}
		}
		if (isset($_POST['upload'])) {
			
			$temp = explode(".", $_FILES["uploadfile"]["name"]);
			$filename = round(microtime(true)) . '.' . end($temp);
			$tempname = $_FILES["uploadfile"]["tmp_name"];    
			$folder = "images/".$filename;

			$update_query = "UPDATE `users` SET `image` = '$filename' WHERE `user_id` = '$profile_id'";
			
			$result = mysqli_query($conn, $update_query);
			
			if ($result && move_uploaded_file($tempname, $folder))  {
				header("Location:user.php?user_id=".$profile_id);
			}else{
				$msg = "Failed to upload image";
			}
		}
	}else {
		header('Location:index.php');
	}
	?>
</body>
</html>