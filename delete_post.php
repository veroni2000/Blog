<?php
session_start();
include('db_connect.php');	
$post_id = $_GET['post_id'];
$admin = false;
$user_id;
if(isset($_SESSION['user_id']))
{
	$read_query = "SELECT * FROM  users WHERE user_id='".$_SESSION['user_id']."'";
	$result = mysqli_query($conn, $read_query);
	if (mysqli_num_rows($result)>0) {
		while($row = mysqli_fetch_assoc($result)){
			$user_id=$row['user_id'];
			if($row['role']=='admin')$admin=true;
		}
	}
	$read_query = "SELECT * FROM  posts WHERE post_id = $post_id";
	$result = mysqli_query($conn, $read_query);
	if (mysqli_num_rows($result)>0) {
		while($row = mysqli_fetch_assoc($result)){
			if($row['user_id']!=$user_id&&!$admin)
				return header('Location: index.php');
		}
	}
	$delete_query = "DELETE FROM posts WHERE post_id = $post_id ";
	$delete_query2 = "DELETE FROM comments WHERE post_id = $post_id ";
	$result = mysqli_query($conn, $delete_query);
	$result2 = mysqli_query($conn, $delete_query2);
	if ($result&&$result2) {
		return header('Location: index.php');  
	}
}
else 
	return header('Location: index.php');
?>