<?php 
session_start();
include('db_connect.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Register</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
	<nav>
		<ul>
			<li><a href="index.php">Home</a></li>
			<li style="float: right;"><a href="login.php">Log in</a></li>
		</ul>
	</nav>
	<main>
		<form class="LoginRegister" action="" method="POST" id="formRegister" onsubmit="return Validate()">
			<label for="enterUsername">Enter username</label>
			<input type="text" name="username" id="enterUsername" placeholder="Username" required>
			<label for="enterEmail">Enter email</label>
			<input type="email" name="email" id="enterEmail" placeholder="Email" required>
			<label for="enterPassword">Enter password</label>
			<input type="password" name="password" id="enterPassword" placeholder="Password" required>
			<label for="enterPassword2">Repeat password</label>
			<input type="password" name="password2" id="enterPassword2" placeholder="Password" required>
			<button type="submit" name="submit" id="register" onclick="Validate()">Register</button>
			<p>Already registered? <a href='login.php'>Log in</a></p>
		</form>
	</main>
</body>
</html>
<?php
if(isset($_POST['submit'])){
	
		$username = str_replace("'","`",$_POST['username']);
		$email = str_replace("'","`",$_POST['email']);
		$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
		$read_query = "SELECT * FROM  users where username='".$username."'";  
		$result = mysqli_query($conn, $read_query);
		$read_query2 = "SELECT * FROM  users where email='".$email."'";  
		$result2 = mysqli_query($conn, $read_query2);
		if (mysqli_num_rows($result)>0) {
			while($row = mysqli_fetch_assoc($result))
			echo "<p id='warning'>This username <i>'".$row['username']."'</i> is already taken.</p>";
		}
		elseif (mysqli_num_rows($result2)>0) {
			while($row = mysqli_fetch_assoc($result2))
			echo "<p id='warning'>This email <i>'".$row['email']."'</i> is already taken.</p>";
		}
		else {
			$insert_query = "INSERT INTO users (username,email,password) VALUES ('$username','$email','$password')";
			if (mysqli_query($conn, $insert_query)) {
				header('Location:login.php');
			} else {
				echo "Error: " . $insert_query . " - " . mysqli_error($conn);
			}
		}
}
?>
<script>
function Validate() {
  var password = document.forms["formRegister"]["password"].value;
  var password2 = document.forms["formRegister"]["password2"].value;
  if (password!==''&&password2!==''&&password!==password2) {
    alert("Passwords do not match.");
    return false;
  }
}
</script>