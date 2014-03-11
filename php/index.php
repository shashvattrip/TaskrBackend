<?php
		include 'class_response.php';

		//THE CODES IN THE BRACES BELOW IS FOR EXTRACTING THE ID AND THE TYPE FROM THE URI

		//{
		//$x= $_SERVER['REQUEST_URI'];

		//echo $x;
		// /api/task/op/:id
		$path = ltrim($_SERVER['REQUEST_URI'], '/'); // Trim leading slash(es) 
		
		$elements = explode('/', $path);
		
		array_shift($elements); //SHIFTS ONE ELEMENT LEFT

		$type = $elements[0];
		$operation=$elements[1];
		
		if($_REQUEST['id'])
		{
			$id=$_REQUEST['id'];
		}
		else
		{
			// echo "ID not assigned";
		}

		if($type=='tags' || $type=='comments' || $type=='tasks')
		{
			if($operation=='view'||$operation=='update'||$operation=='insert'||$operation=='delete')
			{
				$method = $_SERVER['REQUEST_METHOD'];
		
				//$request = split("/", substr(@$_SERVER['PATH_INFO'], 1));

				//echo $request[0];
		
					if ($method == 'GET' && $operation ='view')
					{
						
						switch($type)
						{
							case 'tasks' :
								$obj = new TaskAPI();

								$obj->getTask($id);

								break;

							case 'comments':
								$obj = new CommentAPI();

								$obj->getComment($id);

								break;

							case 'tags':
								$obj = new TagAPI();

								$obj->getTag($id);

								break;
						}
							
			
					}

					else if($method== 'POST')
					{
						if($operation == 'delete')	
						{
							switch($type)
							{
								case 'tasks' :
									$obj = new TaskAPI();
									$data = file_get_contents("php://input");
									$objData=json_decode($data);
									$obj->DeleteTask($objData->id);

									break;

								case 'comments':
									$obj = new CommentAPI();
									$data = file_get_contents("php://input");
									$objData=json_decode($data);
									$obj->DeleteComment($objData->id);

									break;

								case 'tags':
									$obj = new TagAPI();
									$data = file_get_contents("php://input");
									$objData=json_decode($data);
									$obj->DeleteTag($$objData->id);

									break;

							}
						}

						else if($operation == 'update')	
						{
							switch($type)
							{
								case 'tasks' :
									$obj = new TaskAPI();
									$data = file_get_contents("php://input");
									$objData=json_decode($data);
									
									$obj->UpdateTask($objData);

									break;

								case 'comments':
									$obj = new CommentAPI();
									$data = file_get_contents("php://input");
									$objData=json_decode($data);
									$obj->UpdateComment($objData);

									break;

								case 'tags':
									$obj = new TagAPI();
									$data = file_get_contents("php://input");
									$objData=json_decode($data);
									$obj->UpdateTag($objData);

									break;
							}

						}

						else if($operation == 'insert')	
						{
							switch($type)
							{
								case 'tasks' :
									$obj = new TaskAPI();
									$data = file_get_contents("php://input");
									$objData=json_decode($data);
									$obj->InsertTask($objData->TN,$objData->TD);

									break;

								case 'comments':
									$obj = new CommentAPI();
									$data = file_get_contents("php://input");
									$objData=json_decode($data);
									$obj->InsertComment($objData);

									break;

								case 'tags':
									$obj = new TagAPI();
									$data = file_get_contents("php://input");
									$objData=json_decode($data);
									$obj->InsertTag($objData);

									break;
							}
						}
					}	


			}
			else //Operations
			{
				$str['Status']= FALSE;
				$str['error']='Operation  Mismatch';
				exit(json_encode($str));	
			}
		}
		else
		{
			$str['Status']=FALSE;
			$str['error']='Type Mismatch';	
			exit(json_encode($str));
		}
	
		//THE CODES IN THE BRACES BELOW IS FOR EXTRACTING THE ID AND THE TYPE FROM THE REQUEST VARABLE INSTEAD

		/*{	
			$path = ltrim($_GET['request'], '/'); 

			echo $_GET['request'];

			$elements = explode('/', $path);

			array_shift($elements);
		}*/
	
?>