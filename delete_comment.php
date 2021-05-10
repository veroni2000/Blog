<?php
session_start();
include('db_connect.php');	
$comment_id = $_GET['comment_id'];
$admin = false;
$user_id;
$post_id;
$post_owner=false;
$comment_owner=false;
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
	$read_query = "SELECT * FROM  comments WHERE comment_id = $comment_id";
	$result = mysqli_query($conn, $read_query);
	if (mysqli_num_rows($result)>0) {
		while($row = mysqli_fetch_assoc($result)){
			$post_id=$row['post_id'];
			if($row['user_id']==$user_id)
				$comment_owner=true;
		}
	}
	$read_query = "SELECT * FROM  posts WHERE post_id = $post_id";
	$result = mysqli_query($conn, $read_query);
	if (mysqli_num_rows($result)>0) {
		while($row = mysqli_fetch_assoc($result)){
			if($row['user_id']==$user_id)
				$post_owner=true;

			if(!$comment_owner&&!$admin&&!$post_owner)
				return header('Location: index.php');
		}
	}
	$delete_query = "DELETE FROM comments WHERE comment_id = $comment_id ";
	$result = mysqli_query($conn, $delete_query);
	if ($result) {
		return header('Location: post.php?post_id='.$post_id);  
	}
}
else 
	return header('Location: index.php');
?>