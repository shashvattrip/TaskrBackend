<?php

	$DB_NAME = "Taskr-REST";
	$DB_HOST = "localhost";
	$DB_USER = "root";
	$DB_PASSWORD = "";
	// username="conquatd_taskr"
	// password: shashvat

	// $DB_NAME = "conquatd_Taskr-REST";
	// $DB_HOST = "localhost";
	// $DB_USER = "conquatd_taskr";
	// $DB_PASSWORD = "shashvat";

	$con=mysqli_connect($DB_HOST, $DB_USER,'','');

	mysqli_select_db($con,$DB_NAME);
	
	if(mysqli_connect_error($con))
	{
		$str['Status']= FALSE;
		$str['error']=mysqli_connect_error();
		echo json_encode($str);
	}
	else
	{
		
		$str['Status']= TRUE;
		//echo "Connected";	
		
	}


	$TASK_TABLE = "task_basic_info";
	$COMMENT_TABLE = "comment_basic_info";
	$TAG_TABLE = "tag_basic_info";
	$USER_TABLE = "user_login";
	$LOGIN_TABLE= "user_login";
	$PROJECT_TABLE="projects_info";

?>