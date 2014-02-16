<?php
	$con=mysqli_connect('localhost','root','','');
	mysqli_select_db($con,'Taskr-REST');
	if(mysqli_connect_error($con))
	{
		echo "Some error in connect_db.php";
		mysqli_connect_error();
	}
	else
	{
		
		// echo "successfully connected to the database!<br>";
	}
?>
