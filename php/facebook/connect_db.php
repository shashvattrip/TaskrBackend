<?php

	$DB_NAME = "Taskr-REST";
	$DB_HOST = "localhost";
	$DB_USER = "root";
	$DB_PASSWORD = "";

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
	$TAG_TABLE = "tags_basic_info";
	$LOGIN_TABLE = "user_login";
	$USER_TABLE = "user_info";
	$PROJECT_TABLE = "projects_info";

?>