<?php 
session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Log in</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
	<nav>
		<ul>
			<li><a href="index.php">Home</a></li>
			<li style="float: right;"><a class="active" href="login.php">Log in</a></li>
		</ul>
	</nav>
	<main>
		<form class="LoginRegister" action="" method="POST">
			<label for="enterUsername">Enter username</label>
			<input type="text" name="username" id="enterUsername" placeholder="Username" required>
			<label for="enterPassword">Enter password</label>
			<input type="password" name="password" id="enterPassword" placeholder="Password" required>
			<button type="submit" name="submit">Log in</button>
			<p>Don`t have a profile yet? <a href='register.php'>Register</a></p>
		</form>
	</main>
</body>
</html>
<?php
include("db_connect.php");
if(isset($_POST['submit'])){
	$username = $_POST['username'];
	$password = $_POST['password'];
	$select_query = "SELECT * FROM `users` WHERE `username` = '$username'";
	$result = mysqli_query($conn, $select_query);
	if(mysqli_num_rows($result)>0){
		while($row = mysqli_fetch_assoc($result)){
			$verify = password_verify($password, $row['password']);
			if($verify){
				$_SESSION['user_id']=$row['user_id'];
				header('Location:index.php');
			}
			else{
				echo "<p>Wrong password.</p>";
			}	
		}	
	}
	else{
		echo "<p>Invalid username.</p>";
	}
}
echo "<p><a href='register.php'></a></p>";
?>