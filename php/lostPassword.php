<?php
	include 'connect_db.php';
	$email=$_POST['email'];
	$record=mysqli_query($con,"SELECT User_Email,User_Password FROM user_login WHERE User_Email='$email'");

	if($record->num_rows==0)
	{
		echo "No such Email Address exists!";
	}
	else if($record->num_rows==1)
	{
		echo "Done!";
	}
	else
	{
		echo "Some error";
	}
?>