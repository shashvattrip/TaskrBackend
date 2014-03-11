<?php
	include 'connect_db.php';
    header('Content-Type: application/json; charset=utf-8');
	$u_id= "1";//$_GET['id'];
	
	$data["User"] = mysqli_fetch_array(mysqli_query($con, "SELECT User_Name FROM $USER_TABLE WHERE User_ID = $u_id"),MYSQLI_ASSOC);
	
	$p_ids = mysqli_query($con, "SELECT Project_IDs FROM $USER_TABLE WHERE User_ID = $u_id");

	
	//$finalArray=array($data);
	$array = mysqli_fetch_array($p_ids, MYSQLI_ASSOC);

	//echo $array['Project_IDs'][0];
	//echo json_encode($array);
	//echo $data;
	// echo "string";
	$string = $array['Project_IDs'];
	//$string = implode(",", $array[0]['Project_IDs']);
	// echo json_encode($string);
	
	$pid_loop=explode(',',$string);

	//echo json_encode($pid_loop);
	for($x=0;$x<count($pid_loop);$x++)
	{

		$recor = mysqli_query($con,"SELECT Project_ID,Project_Name,Project_Description,Project_Members,Project_Status FROM $PROJECT_TABLE 
		WHERE Project_ID=$pid_loop[$x]");

		$projects = mysqli_fetch_array($recor, MYSQLI_ASSOC);

		$data["Projects"][] = $projects;
		//array_merge($data["Projects"],$projects);
	}

		$reco = mysqli_query($con, "SELECT Task_IDs FROM $USER_TABLE WHERE User_ID = $u_id");
		$t_ids = mysqli_fetch_array($reco, MYSQLI_ASSOC);
		// $data = array_map("str_getcsv", preg_split('/\r*\n+|\r+/', $c)); // for separating the csv values and storing them in array	
		$id_loop=explode(',',$t_ids['Task_IDs']); // for separating the csv values and storing them in array

		//echo json_encode($data);
		//$array = array('1','2','3');
		for($i=0; $i<count($id_loop); $i++)
		{
			$record = mysqli_query($con, "SELECT Task_ID, Task_Name, Task_Description, Task_Followers, Task_Tags,Task_Comments FROM $TASK_TABLE
					WHERE Task_ID= $id_loop[$i]");
			$result = mysqli_fetch_array($record, MYSQLI_ASSOC);

			//echo json_encode($result);

			$data["allTasks"][] = $result;

			$tag_data = explode(',', $result['Task_Tags']) ;// for separating the csv values and storing them in array	
			
			//echo json_encode($tag_data);
			for ($j=0 ; $j<count($tag_data); $j++)
			{
				$records = mysqli_query($con, "SELECT Tag_ID, Tag_Name FROM $TAG_TABLE
					WHERE Tag_ID= $tag_data[$j]");

				$tags = mysqli_fetch_all($records, MYSQLI_ASSOC);

				foreach ($tags as $key => $value) {
					$data["allTasks"][$i]["Tags"][] = $value;
				}
				

				//array_push($data["allTasks"]["Task$i"]["Tags"], $tags);

			}


			$rec= mysqli_query($con, "SELECT Comment_ID, Comment_Body FROM $COMMENT_TABLE WHERE Comment_Task_ID= $id_loop[$i]");

			$comment_data = mysqli_fetch_all($rec, MYSQLI_ASSOC);

			foreach ($comment_data as $key => $value) {
				
			$data["allTasks"][$i]["Comments"][]= $value;

				/*echo json_encode($key);

				echo json_encode($value);*/
			}
			
					
		}
	

	echo json_encode($data);
?>