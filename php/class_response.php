<?php 
    
    class onLoad
    {
        //Load all data in the required format
        //This includes making a JSON of all the tables ie tasks,tags,comments and returning one JSON obj for a user
        public function LoadAllData()
        {
            $count=mysqli_query($con,"SELECT COUNT(*) FROM Task_Info");

            $index=0;
            $tasksArray=array();
            //fetch all the tasks and store in the array $task
            while($index<$count)
            {
                $task = mysqli_query($con,"SELECT Task_ID, Task_Name, Task_Description, Task_Followers, Task_Tags
                FROM Task_Info
                WHERE Task_ID=$id");    
                array_push($tasksArray, $task);
            }

            

            // follow this format for JSON
            // create this JSON and send it
            //this function will be called on PageLoad
            //Load all tasks that a user has to-do
            // [
            //     {"project":
            //          [    
                        // {
                        //     "Project_ID":100,
                        //     "Project_Name":"Taskr",
                        //     "Project_status":"active",
                        //     "Project_Members":[UserID1,UserID2,UserID3],
                        //     "Project_CreatedOn":"Date"
                        // }
            //          ],
            //     "Task_ID":100,
            //     "Task_Name":"Study Red Black Trees",
            //     "Task_Description":"Upcoming Test on DSA",
            //     "Comments":[
            //        {"Comment_ID:21","Comment_Body":"Yay, this is a comment"},
            //        {"Comment_ID:21","Comment_Body":"Yay, this is a comment"},
            //        {"Comment_ID:21","Comment_Body":"Yay, this is a comment"}],
            //     "followers":[UserID1,UserID2,UserID3],
            //     "star":1,
            //     "DueDate":"14-2-2014",
            //     "tags":[
            //              {"Tag_ID1":222,"Tag_Name":"JS"},
            //              {"Tag_ID1":222,"Tag_Name":"JS"},
            //              {"Tag_ID1":222,"Tag_Name":"JS"}
            //            ]
            //     "assignedTo":["User_ID":123,"User_Name":"Shashvat"],
            //     "completed":true,
            //     "clocked":1}
            // ]


            // Structure of DATABASE
            // ************* user_login ************
            // User_ID | User_Name | User_Email | User_Type (Facebook/Custom) | User_LastLoggedIn | User_Contact | User_Password | User_CreatedDateTime | Project_IDs

            // ************* Tasks_Info ************
            // Task_ID | Task_Name | Task_Description | Task_CreatedDateTime | Task_Followers(CSV) | Task_Tags(CSV) | Task_CreatedOn | Task_Completed | Task_Followers | Task_assignedTo | Task_Star | Task_CreatedBy

            // ************* Tags_Info ************
            // Tag_ID | Tag_Name | Tag_CreatedDateTime 

            // ************* Comments_Info ************
            // Comment_ID | Comment_Body | Comment_CreatedDateTime | User_ID | Comment_Task_ID(this points to the Task_ID in which this comment was made)

            // ************* Projects_Info ************
            // Project_ID | Project_Name | User_CreatedDateTime | Project_Description | Project_Members | Project_Status

            // ************* User_Info ************
            // User_ID | (CSV)
        }
    }


    class TaskAPI
    {
    /**
     * Returns a JSON string object to the browser when hitting the root of the domain
     *
     * @url GET /
     */
        public function test()
        {
            return "Hello World";
        }

        /**
         * Logs in a user with the given username and password POSTed. Though true
         * REST doesn't believe in sessions, it is often desirable for an AJAX server.
         *
         * @url POST /login
         */


        public function getTask($id=0)
        {
            include 'connect_db.php';

            header('Content-Type: application/json; charset=utf-8');

            if($id!=0)
            {
                $records=mysqli_query($con,"SELECT Task_ID, Task_Name FROM $TASK_TABLE WHERE Task_ID=$id");
                $json=mysqli_fetch_all($records, MYSQLI_ASSOC);
                echo json_encode($json);
            }
            else if($id=='all')
            {
                $records=mysqli_query($con,"SELECT Task_ID, Task_Name FROM $TASK_TABLE");

                $json=mysqli_fetch_all($records, MYSQLI_ASSOC);
                echo json_encode($json);
            }
            else if($id==0)
            {
                $str['Status']=FALSE;
                $str['error']='ID Mismatch';  
                exit(json_encode($str));
            }
        }

        public function DeleteTask($id)
        {
            include 'connect_db.php';
            //echo $id;
            header('Content-Type: application/json; charset=utf-8');
        
            //This should not be a delete query
            //change column Deleted to TRUE
            $sql = "DELETE FROM $TASK_TABLE WHERE Task_ID=$id";
               
            if(mysqli_query($con,$sql))
            {
                $str['Status']=TRUE;
                //$str['error']='Type Mismatch';  
                exit(json_encode($str));                                   
            }
            else 
            {
                $str['Status']=FALSE;
                $str['error']='SQL Error in deleting task';  
                exit(json_encode($str));  
            }
        }

        public function InsertTask($objData)
        {
            include 'connect_db.php';
            $name=$objData->Task_Name;
            $desc=$objData->Task_Description;
            // echo json_encode($objData);
            $projectID=$objData->Task_Project_ID;
            // echo $projectID;
            // echo "\n\n";
            // $projectID=1;
            header('Content-Type: application/json; charset=utf-8');
            $sql = "INSERT INTO $TASK_TABLE VALUES(NULL,'$name','$desc','',$projectID,'','','','','')";

            if(mysqli_query($con,$sql))
            {

                $str['Status']=TRUE;
                
                $str['Task_ID']=mysqli_insert_id($con);
                echo json_encode($str);                                
            }
            else 
            {
                $str['Status']=FALSE;
                $str['error']='SQL Error in inserting task';  
                echo json_encode($str);
                exit();
            }
        }

        public function UpdateTask($objData)
        {
            include 'connect_db.php';
            $name=$objData->Task_Name;
            $desc=$objData->Task_Description;
            $id=$objData->Task_ID;
            
            //append CommentID
            // $newComment=$objData->

            header('Content-Type: application/json; charset=utf-8');
            $sql = "UPDATE $TASK_TABLE SET Task_Name='$name',Task_Description='$desc' WHERE Task_ID=$id";
            // echo json_encode($objData);

            if(mysqli_query($con,$sql))
            {
                $str['Status']=TRUE;
                $str['UpdatedTask']=TRUE;
                echo (json_encode($str));                                    
            }
            else 
            {
                $str['Status']=FALSE;
                $str['error']='SQL Error in updating task';  
                echo (json_encode($str));
            }
        }
    }

    class CommentAPI
    {
        public function getComment($id)
        {
            include 'connect_db.php';
            header('Content-Type: application/json; charset=utf-8');
        
            if($id!=0)
            {
                $records=mysqli_query($con,"SELECT Comment_ID, Comment_Body FROM $COMMENT_TABLE WHERE Comment_ID=$id");
                
                $json=mysqli_fetch_all($records, MYSQLI_ASSOC);
                echo json_encode($json); 
            }
            else if($id=='all')
            {
                $records=mysqli_query($con,"SELECT Comment_ID, Comment_Body FROM $COMMENT_TABLE");

                $json=mysqli_fetch_all($records, MYSQLI_ASSOC);
                echo json_encode($json);
            }
            else
            {
                $str['Status']=FALSE;
                $str['error']='id is 0';  
                exit(json_encode($str));
            }

        }

        public function DeleteComment($id)
        {
            include 'connect_db.php';
            
            header('Content-Type: application/json; charset=utf-8');
        
            $sql = "DELETE FROM $COMMENT_TABLE WHERE Comment_ID=$id";
               
            if(mysqli_query($con,$sql))
            {
                $str['Status']=TRUE;
                echo (json_encode($str));                                    
            }

            else 
            {
                $str['Status']=FALSE;
                $str['error']='SQL Error in deleting comment';  
                echo (json_encode($str));
            }
        }

        public function UpdateComment($objData)
        {
            //$objData will contain on Body
            include 'connect_db.php';
            // $id=$objData->CID;
            $body=$objData->Body;

            header('Content-Type: application/json; charset=utf-8');
        
            $sql = "UPDATE $COMMENT_TABLE SET Comment_Body='$body' WHERE Comment_ID=$id";
               
            if(mysqli_query($con,$sql))
            {
                $str['Status']=TRUE;
                exit(json_encode($str));                                   
            }

            else 
            {
                $str['Status']=FALSE;
                $str['error']='SQL Error in updating comment';  
                exit(json_encode($str));
            }
        }

        public function InsertComment($objData)
        {
            
            //$objData will contain only Body
            $body=$objData->Comment_Body;
            $userID=$objData->UserID;
            $Comment_Task_ID=$objData->Task_ID;
            // $dateCreatedOn=new Date();
            // echo $body;
            include 'connect_db.php';
            //add new Date() to the data for comment createdon column
            header('Content-Type: application/json; charset=utf-8');
        
            $sql = "INSERT INTO $COMMENT_TABLE VALUES(NULL,'$body','','$userID','$Comment_Task_ID')";
            if(mysqli_query($con,$sql))
            {
                $str['Status']=TRUE;
                $str['Comment_ID']=mysqli_insert_id($con);
                echo (json_encode($str));                                    
            }
            else 
            {
                $str['Status']=FALSE;
                $str['error']='SQL Error in inserting comment';  
                echo (json_encode($str));   
            }
        }
    }



    class ProjectAPI
    {
        
        public function DeleteProject($id)
        {
            
        }

        public function InsertProject($objData)
        {
            //$objData will contain only Body
            $name=$objData->Project_Name;
            // echo $objData->Project_Name;
            // $name="Shashvat";
            // $dateCreatedOn=new Date();
            //add new Date() to the data for comment createdon column
            include 'connect_db.php';
            
            header('Content-Type: application/json; charset=utf-8');
        
            $sql = "INSERT INTO $PROJECT_TABLE VALUES(NULL,'$name','','','','')";
            if(mysqli_query($con,$sql))
            {
                

                $str['Status']=TRUE;
                $str['Project_ID']=mysqli_insert_id($con);
                // echo $str['Project_ID'];
                echo (json_encode($str));                                    
            }
            else 
            {
                $str['Status']=FALSE;
                $str['error']='SQL Error in inserting comment';  
                echo (json_encode($str));   
            }
        }
    }

    class User_LoginAPI
    {

        public function UpdateUser($objData)
        {
            //$objData will contain only Body
            $Task_ID=$objData->Task_ID;
            $User_ID=$objData->User_ID;
            $Project_ID=$objData->Project_ID;
            // echo $User_ID;
            include 'connect_db.php';
            
            header('Content-Type: application/json; charset=utf-8');
        
            $sql = "SELECT User_ID,Task_IDs,Project_IDs FROM $LOGIN_TABLE WHERE USER_ID=$User_ID";
            if($rec=mysqli_query($con,$sql))
            {
                // $json=mysqli_fetch_all($sql, MYSQLI_ASSOC);
                $rec2=mysqli_fetch_all($rec,MYSQLI_ASSOC);
                // echo json_encode($rec2);
                $task_ids=$rec2[0]['Task_IDs'];
                $task_ids = $task_ids . ',' . $Task_ID;
                $project_ids=$rec2[0]['Project_IDs'];
                //check if the $Project_ID is present in the $project_ids string
                $project_id_array=array();
                $project_id_array=explode(',', $project_ids);

                $project_id_is_present=in_array($Project_ID, $project_id_array);
                if(!$project_id_is_present)
                {
                    $project_ids=$project_ids . ',' . $Project_ID;
                }
                
                // echo $task_ids;

                $sql2 = "UPDATE $LOGIN_TABLE SET Task_IDs='$task_ids', Project_IDs='$project_ids' WHERE User_ID='$User_ID'";
                
                
                // echo json_encode($json);
                if($rec3=mysqli_query($con,$sql2))
                {
                    $str['Status']=TRUE;
                    
                    // echo $str['Project_ID'];
                    echo (json_encode($str));                                    
                }
                else 
                {
                    $str['Status']=FALSE;
                    $str['error']='SQL Error in updating User_Login table';  
                    echo (json_encode($str));   
                }
            }
            else
            {
                $str['Status']=FALSE;
                $str['error']='SQL Error in fetching from user login table. Query Returned False';  
                echo (json_encode($str));   
            }


            
        }
    }

    class TagAPI
    {
        public function getTag($id)
        {   
            include 'connect_db.php';
            header('Content-Type: application/json; charset=utf-8');
            
            if($id!=0)
            {
                $records=mysqli_query($con,"SELECT Tag_ID, Tag_Name FROM $TAG_TABLE WHERE TAG_ID=$id");
                
                $json=mysqli_fetch_all($records, MYSQLI_ASSOC);
                echo json_encode($json);
            }

            else if($id=='all')
            {
                $records=mysqli_query($con,"SELECT Tag_ID, Tag_Name FROM $TAG_TABLE");
            
                $json=mysqli_fetch_all($records, MYSQLI_ASSOC);
                
                echo json_encode($json);
            }
            else
            {
                    $str['Status']=FALSE;
                    $str['error']='id is 0';  
                    exit(json_encode($str));
            }
        }

        public function DeleteTag($id)
        {
            include 'connect_db.php';
            
            header('Content-Type: application/json; charset=utf-8');
        
            $sql = "DELETE FROM $TAG_TABLE WHERE Tag_ID=$id";
               
            if(mysqli_query($con,$sql))
            {
                $str['Status']=TRUE;
                 
                exit(json_encode($str));                                    
                
            }

            else 
            {
                $str['Status']=FALSE;
                $str['error']='SQL Error in deleting tag';  
                exit(json_encode($str));  
            }
        }

        public function InsertTag($objData)
        {
            include 'connect_db.php';
            $name=$objData->Tag_Name;
            header('Content-Type: application/json; charset=utf-8');
            //add created on date value
            // $date=new Date();

            $sql = "INSERT INTO $TAG_TABLE VALUES(NULL,'$name','')";
               
            if(mysqli_query($con,$sql))
            {
                $str['Status']=TRUE;
                $str['Tag_ID']=mysqli_insert_id($con);
                exit(json_encode($str));                                    
            }

            else 
            {
                $str['Status']=FALSE;
                $str['error']='SQL Error in inserting tag';  
                exit(json_encode($str));   
            }
        }

        public function UpdateTag($id,$name)
        {
            include 'connect_db.php';
            
            header('Content-Type: application/json; charset=utf-8');
        
            $sql = "UPDATE $TAG_TABLE SET Tag_Name='$name' WHERE Tag_ID=$id";
               
            if(mysqli_query($con,$sql))
            {
                $str['Status']=TRUE;
                
                exit(json_encode($str));                                    
                
            }
            else 
            {
                $str['Status']=FALSE;
                $str['error']='SQL Error in updating tag';  
                exit(json_encode($str));
            }
        }

    }
?>