<?php
	include 'connect_db.php';
	// session_start();
	$name=$_POST['name'];
	$email=$_POST['email'];
	// $contactno=$_POST['contactno'];
	$password=md5($_POST['password']);
	$dateTime=date('d-m-Y H:i:s');
	
	$records=mysqli_query($con,"INSERT INTO Taskr-REST (User_Name,User_Email,User_Password,User_JoinedDateTime) VALUES ('$name','$email','$password','$dateTime')");
	
	if($records===TRUE)
	{
		echo "Start the session!";
		header('Location: index.html');

	}
	else
	{
		echo "Failed to login!";
		// header('Location:../login.html');
	}
?>
