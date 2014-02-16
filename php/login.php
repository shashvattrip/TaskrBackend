<?php
	include_once 'connect_db.php';
	session_start();
	$email=$_POST['email'];

	$records=mysqli_query($con,"SELECT User_Password,User_ID,User_Contact FROM user_login WHERE User_Email='$email'");

	if($records==FALSE)
	{
		echo "Something went wrong! Write try/catch for this!";
	}

	if($records->num_rows==1)
	{
		//user exists
		$records_array=mysqli_fetch_array($records);
		$password=$_POST['password'];
		if($records_array['User_Password']==md5($password))
		{
			$_SESSION['id']=$records_array['User_ID'];
			$_SESSION['login']=TRUE;

			// echo "Redirect to index.html";
			header('Location: ../index.php');
			//to make sure that the code below does not get executed when we redirect
			exit;
		}
		else
		{
			//invalid password
			echo "invalid Password";
			$_SESSION['invalid_password']=TRUE;
			header('Location: ../login.php');
		}
	}
	else
	{
		//user does not exist
		$_SESSION['invalid_user']=TRUE;
	} 
?>