<?php
	include_once 'connect_db.php';
    header('Content-Type: application/json; charset=utf-8');
	$u_id= $_GET['id'];
	// $u_id= 2;
	
	
	$errorHandling['errors'] = array();
	$errorHandling['Status']=TRUE;

	// fetch User_ID and User_Name
	$data["User"] = mysqli_fetch_array(mysqli_query($con, "SELECT User_Name, User_ID FROM $USER_TABLE WHERE User_ID = $u_id"),MYSQLI_ASSOC);
	
	// fetch ProjectIDs
	$p_ids = mysqli_query($con, "SELECT Project_IDs FROM $USER_TABLE WHERE User_ID = $u_id");

	//in case no projects were found in the $USER_TABLE
	if($p_ids===FALSE)
	{
		$errorHandling['Status']=FALSE;
		array_push($errorHandling['errors'], "Some Error while retreiving ProjectIDs in alldata.php");
	}

	$array = mysqli_fetch_array($p_ids, MYSQLI_ASSOC);
	// echo count($array['Project_IDs']);

	if($array['Project_IDs']=="")
	{
		// unset($str);
		// $errorHandling['Status']=FALSE;
		// array_push($errorHandling['errors'], "The user does not have any projects! Add Projects for the user!");
		// echo json_encode($str);
	}
	

	$string = $array['Project_IDs'];
	$pid_loop=explode(',',$string);

	// echo json_encode($pid_loop);
	// if there are no projects then $pid_loop will be = [""] and hence
	// $pid_loop[0]=""	
	
	for($x=0; ( $x<count($pid_loop) && $pid_loop[0]!="" );$x++)
	{

		$record = mysqli_query($con,"SELECT Project_ID,Project_Name,Project_Description,Project_Members,Project_Status FROM $PROJECT_TABLE 
		WHERE Project_ID=$pid_loop[$x]");

		if($record===FALSE)
		{
			$errorHandling['Status']=FALSE;
			array_push($errorHandling['errors'], "In alldata.php, Error in retreiving pids in the $pid_loop");
		}
		else
		{
			$projects = mysqli_fetch_array($record, MYSQLI_ASSOC);
			// echo "\n\n";
			
			$arrProjects=explode(',', $projects['Project_Members']);


			if($arrProjects[0]=="")
			{
				$projects['Project_Members']=null;
			}
			else
			{
				$projectMembers=array();
				for($i=0 ; $i<count($arrProjects) ; $i++)
				{
					$sql="SELECT User_ID,User_Name FROM $LOGIN_TABLE WHERE User_ID=$arrProjects[$i]";
					$record=mysqli_query($con,$sql);
					if($record)
					{
						$row=mysqli_fetch_array($record,MYSQLI_ASSOC);
						$member['User_ID']=$row['User_ID'];
						$member['User_Name']=$row['User_Name'];
						array_push($projectMembers, $member);
					}
					else
					{
						$errorHandling['Status']=FALSE;
						array_push($errorHandling['errors'], "line 84");
					}
				}

				$projects['Project_Members']=$projectMembers;	
			}
			// echo json_encode($projects);
			$data["Projects"][] = $projects;
			//array_merge($data["Projects"],$projects);
		}
	}

		unset($record);
		

		/******************************************/
		$record = mysqli_query($con, "SELECT Task_IDs FROM $USER_TABLE WHERE User_ID = $u_id");
		$t_ids = mysqli_fetch_array($record, MYSQLI_ASSOC);
		/******************************************/

		/******************************************/
		// $pid_loop is an array of all the PIDs
		$TASK_IDS_SH=array();
		for($x=0; ($x<count($pid_loop) && $pid_loop[0]!="" );$x++)
		{
			$record2 = mysqli_query($con,"SELECT Task_ID FROM $TASK_TABLE WHERE Task_Project_ID=$pid_loop[$x]");
			if($record2===FALSE)
			{
				$errorHandling['Status']=FALSE;
				array_push($errorHandling['errors'], "In alldata.php, Error in retreiving task_ids in the $pid_loop");
			}
			else
			{
				//Here I have to generate an array of the Task_IDs whose details we need to send to the client
				// $temp_task_ids=mysqli_fetch_all($record2,MYSQLI_ASSOC);
				// echo json_encode($temp_task_ids);

				$temp_task_ids=mysqli_fetch_all($record2,MYSQLI_ASSOC);
				foreach ($temp_task_ids as $column => $value) 
				{
					foreach ($value as $key => $id) 
					{
						// echo "column : " . $id . " ";
						array_push($TASK_IDS_SH, $id);
					}
					
				}
				// echo json_encode($temp_task_ids);
				// echo "\n";
			}
		}
		
		// echo "\n";
		// echo json_encode($TASK_IDS_SH);
		// echo "\n\n";
		

		/******************************************/

		//Get the Task_IDs of all the tasks that we want to give to the client
		// $id_loop=explode(',',$t_ids['Task_IDs']); // for separating the csv values and storing them in array
		$id_loop=$TASK_IDS_SH;
		//echo json_encode($data);
		//$array = array('1','2','3');
		if($id_loop[0]=="")
		{

		}

		for($i=0; ($i<count($id_loop) && $id_loop[0]!="") ; $i++)
		{
			$temp_id_loop=$id_loop[$i];
			// echo "string";
			$record = mysqli_query($con,"SELECT Task_ID, Task_Name, Task_Description, Task_Project_ID,Task_Followers,Task_Comments,Task_Star,Task_Clock,Task_assignedTo,Task_Completed FROM $TASK_TABLE
					WHERE Task_ID = $temp_id_loop");			

			$result = mysqli_fetch_array($record, MYSQLI_ASSOC);
			// echo $result['Task_Followers'];
			// echo "string";
			$taskFollowers=explode(',',$result['Task_Followers']) ;
			// echo $taskFollowers[0];
			// echo json_encode($taskFollowers);
			$taskFollowersObj=array();
			if($taskFollowers[0]=="")
			{
				$result['Task_Followers']=null;
			}
			// $tempUserIDs = array(); --same as $taskFollowers
			for($anotherLoop=0; ($anotherLoop<count($taskFollowers) && $taskFollowers[0]!=""); $anotherLoop++)
			{
				$temp=array();
				$sqlQuery="SELECT User_ID,User_Name FROM $LOGIN_TABLE WHERE User_ID=$taskFollowers[$anotherLoop]";
				$resultSqlQuery=mysqli_query($con,$sqlQuery);
				if(!$resultSqlQuery)
				{
					echo "Something went wrong!";
					die();
				}
				$recordSqlQuery=mysqli_fetch_array($resultSqlQuery,MYSQLI_ASSOC);
				$temp['User_ID']=$recordSqlQuery['User_ID'];
				$temp['User_Name']=$recordSqlQuery['User_Name'];
				array_push($taskFollowersObj, $temp);
			}
			// echo "\n\n";
			// echo json_encode($taskFollowersObj);
			// echo "\n\n";
			// echo json_encode($taskFollowersObj);
			// echo "\n\n";
			if($taskFollowers[0]!="")
			{
				$result['Task_Followers']=$taskFollowersObj;
			}
			
			// echo "aaa\n\n";
			// echo json_encode($result['Task_Followers']);
			// echo "\n\n";
			$data["allTasks"][] = $result;

			$taglist = mysqli_fetch_array(mysqli_query($con, "SELECT Task_Tags FROM $TASK_TABLE WHERE Task_ID= $temp_id_loop"),MYSQLI_ASSOC);
			$tag_data = explode(',', $taglist['Task_Tags']) ;
			// for separating the csv values and storing them in array	
			//echo json_encode($tag_data);

			if($tag_data[0]=="")
			{
				$data["allTasks"][$i]["Tags"]=null;
			}

			for ($j=0 ; ( $j<count($tag_data) && $tag_data[0]!=""); $j++)
			{
				$records = mysqli_query($con, "SELECT Tag_ID, Tag_Name FROM $TAG_TABLE
					WHERE Tag_ID= $tag_data[$j]");

				$tags = mysqli_fetch_all($records, MYSQLI_ASSOC);

				foreach ($tags as $key => $value) {
					$data["allTasks"][$i]["Tags"][] = $value;
				}
				

				//array_push($data["allTasks"]["Task$i"]["Tags"], $tags);

			}


			$rec= mysqli_query($con, "SELECT Comment_ID, Comment_Body,User_ID,Comment_CreatedDateTime FROM $COMMENT_TABLE WHERE Comment_Task_ID= $temp_id_loop");

			$comment_data = mysqli_fetch_all($rec, MYSQLI_ASSOC);

			// if there are no comments
			if(count($comment_data)===0)
			{
				$data["allTasks"][$i]["Comments"][]=null;
			}

			$tempUserIDs = array();
			for($anotherLoop=0; ($anotherLoop<count($comment_data) && count($comment_data)>0); $anotherLoop++)
			{
				array_push($tempUserIDs, $comment_data[$anotherLoop]['User_ID']);
			}
			
			// $tempUserIDs contains all the userIDs in all the comments for a task
			// echo json_encode($tempUserIDs);
			
			//fetch all the names for corresponding UserIDs in $tempUserIDs
			for($inner=0; $inner<count($tempUserIDs); $inner++)
			{
				
				$username=mysqli_query($con,"SELECT User_Name FROM $USER_TABLE WHERE User_ID = $tempUserIDs[$inner]");	
				$username=mysqli_fetch_array($username,MYSQLI_ASSOC);
				$comment_data[$inner]['User_Name']=$username['User_Name'];	
			}
			
			foreach ($comment_data as $key => $value) 
			{
				$data["allTasks"][$i]["Comments"][]= $value;
			}
		}
	
		//Getting all the Project Members


	$data['Status']=$errorHandling['Status'];
	$data['errors']=$errorHandling['errors'];
	echo json_encode($data);
	file_put_contents('results.json', json_encode($data));
?>