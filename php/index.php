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
			echo "ID not assigned";
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

									$obj->DeleteTask($id);

									break;

								case 'comments':
									$obj = new CommentAPI();

									$obj->DeleteComment($id);

									break;

								case 'tags':
									$obj = new TagAPI();

									$obj->DeleteTag($id);

									break;

							}
						}
						

						else if($operation =='update')	
						{
							switch($type)
							{
								case 'tasks' :
									$obj = new TaskAPI();

									$obj->UpdateTask($id,$_POST['name'],$_POST['description']);

									break;

								case 'comments':
									$obj = new CommentAPI();

									$obj->UpdateComment($id,$_POST['body']);

									break;

								case 'tags':
									$obj = new TagAPI();

									$obj->UpdateTag($id,$_POST['name']);

									break;
							}

						}

						else if($operation == 'insert')	
						{
							switch($type)
							{
								case 'tasks' :
									$obj = new TaskAPI();

									$obj->InsertTask($_POST['name'],$_POST['description']);

									break;

								case 'comments':
									$obj = new CommentAPI();

									$obj->InsertComment($_POST['body']);

									break;

								case 'tags':
									$obj = new TagAPI();

									$obj->InsertTag($_POST['name']);

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