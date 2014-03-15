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
            $userID=$objData->User_ID;
            $projectID=$objData->Task_Project_ID;

            header('Content-Type: application/json; charset=utf-8');
            $sql = "INSERT INTO $TASK_TABLE VALUES(NULL,'$name','$desc',NOW(),'$projectID','','','','','','')";

            if(mysqli_query($con,$sql))
            {
                $str['Task_ID']=mysqli_insert_id($con);

                $sql="SELECT Task_IDs FROM $LOGIN_TABLE WHERE User_ID=$userID";
                if($rec=mysqli_query($con,$sql))
                {
                    $row=mysqli_fetch_array($rec,MYSQLI_ASSOC);
                    $task_ids=$row['Task_IDs'];

                    // echo $task_ids;
                    if($task_ids!='')
                    {
                        $task_ids=$task_ids . ',' . $str['Task_ID'];    
                    }
                    else
                    {
                        $task_ids=$str['Task_ID'];       
                    }
                    
                    $sql="UPDATE $LOGIN_TABLE SET Task_IDs='$task_ids' WHERE User_ID='$userID'";
                    if(mysqli_query($con,$sql))
                    {
                        $str['Status']=TRUE;
                        echo (json_encode($str));
                    }
                    else
                    {
                        $str['Status']=FALSE;
                        $str['error']="Problem updating USER_LOGIN table with Csv of Task_IDs";
                        echo json_encode($str);
                    }

                }
                else
                {

                }
                                              
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
            $star=$objData->Task_Star;
            $clocked=$objData->Task_Clock;
            


            //change followers to a CSV string
            $followers=array();            
            $followers=$objData->Task_Followers;
            if($followers!=null)
            {
                $CSVfollowers=$followers[0]->User_ID;
                for($i=1 ; $i<count($followers) ; $i++)
                {
                    $CSVfollowers=$CSVfollowers . ',' . $followers[$i]->User_ID;
                }
                // echo $CSVfollowers;    
            }
            else
            {
                $CSVfollowers=null;
            }

            //change comments to a CSV string
            $comments=array();
            $comments=$objData->Comments;
            // echo json_encode($comments);
            if($comments[0]!="")
            {
                $CSVcomments=$comments[0]->Comment_ID;
                for($i=1 ; $i<count($comments) ; $i++)
                {
                    $CSVcomments=$CSVcomments . ',' . $comments[$i]->Comment_ID;
                }
                // echo $CSVcomments;    
            }
            else
            {
                $CSVcomments=null;
            }
            

            //change comments to a CSV string
            $tags=array();
            $tags=$objData->Tags;
            if($tags!=null)
            {
                $CSVtags=$tags[0]->Tag_ID;
                for($i=1 ; $i<count($tags) ; $i++)
                {
                    $CSVtags=$CSVtags . ',' . $tags[$i]->Tag_ID;
                }
                // echo $CSVtags;    
            }
            else
            {
                $CSVtags=null;
            }
            

            header('Content-Type: application/json; charset=utf-8');
            $sql = "UPDATE $TASK_TABLE SET Task_Name='$name',Task_Description='$desc',Task_Followers='$CSVfollowers',Task_Comments='$CSVcomments', Task_Tags='$CSVtags',Task_Star='$star',Task_Clock='$clocked' WHERE Task_ID=$id";
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
            $userID=$objData->User_ID;
            $Comment_Task_ID=$objData->Task_ID;
            // $dateCreatedOn=new Date();
            // echo $body;
            include 'connect_db.php';
            //add new Date() to the data for comment createdon column
            header('Content-Type: application/json; charset=utf-8');
        
            $sql = "INSERT INTO $COMMENT_TABLE VALUES(NULL,'$body',NOW(),'$userID','$Comment_Task_ID')";
            if(mysqli_query($con,$sql))
            {

                $str['Comment_ID']=mysqli_insert_id($con);

                $sql = "SELECT Task_Comments FROM $TASK_TABLE WHERE Task_ID=$userID";
                if($rec=mysqli_query($con,$sql))
                {
                    $row=mysqli_fetch_array($rec,MYSQLI_ASSOC);
                    $comment_ids=$row['Task_Comments'];
                    // echo $task_ids;
                    if($comment_ids!='')
                    {
                        $comment_ids=$comment_ids . ',' . $str['Comment_ID'];
                        // echo $comment_ids; 
                        // echo "\n\n";
                        // echo $Comment_Task_ID;
                        // echo "\n\n";
                    }
                    else
                    {
                        $comment_ids=$str['Comment_ID'];       
                    }

                    $sql="UPDATE $TASK_TABLE SET Task_Comments='$comment_ids' WHERE Task_ID='$Comment_Task_ID' ";
                    if(mysqli_query($con,$sql))
                    {
                        $str['Status']=TRUE;
                        echo (json_encode($str));    
                    }
                    else
                    {
                        $str['Status']=FALSE;
                        $str['error']="Something went wrong while adding comment to the Task Table!";
                        echo (json_encode($str));
                    }
                }
                else
                {
                    $str['Status']=FALSE;
                    $str['error']="Something went wrong while getting Comments from Task Table!";
                    echo (json_encode($str));
                }
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
            // $objData->Project_Name;
            // $name="Shashvat";
            // $dateCreatedOn=new Date();
            //add new Date() to the data for comment createdon column
            include 'connect_db.php';
            
            header('Content-Type: application/json; charset=utf-8');
        
            $sql = "INSERT INTO $PROJECT_TABLE VALUES(NULL,'$name',NOW(),'','','')";
            if(mysqli_query($con,$sql))
            {
                //Update the LOGIN_TABLE to add this new project into the CSV column
                $str['Project_ID']=mysqli_insert_id($con);
                $sql="SELECT Project_IDs FROM $LOGIN_TABLE WHERE User_ID=$objData->User_ID";
                if($record=mysqli_query($con,$sql))
                {
                    $row=mysqli_fetch_array($record,MYSQLI_ASSOC);
                    $CSV=$row['Project_IDs'];
                    $CSV=$CSV . ',' . $str['Project_ID'];
                    $sql = "UPDATE $LOGIN_TABLE SET Project_IDs='$CSV' WHERE User_ID='$objData->User_ID'";
                    if(mysqli_query($con,$sql))
                    {
                        $str['Status']=TRUE;
                        $str['ppp']=$CSV;
                        // echo $str['Project_ID'];
                        echo (json_encode($str));    
                    }
                    else
                    {
                        $str['Status']=FALSE;
                        $str['CSV_Project_IDs']=$CSV;
                        // echo $str['Project_ID'];
                        echo (json_encode($str));       
                    }
                }
                else
                {
                    $str['Status']=FALSE;
                    $str['error']="Check InsertProject in class_response";
                }
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


    class AssignTask
    {
        public function assign($objData)
        {
            $userID=$objData->User_ID;
            echo json_encode($objData);
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

            $sql = "INSERT INTO $TAG_TABLE VALUES(NULL,'$name',NOW())";
               
            if(mysqli_query($con,$sql))
            {
                $str['Tag_ID']=mysqli_insert_id($con);
                $sql="SELECT Task_Tags FROM $TASK_TABLE WHERE Task_ID=$objData->Task_ID";
                if($rec=mysqli_query($con,$sql))
                {
                    $row=mysqli_fetch_array($rec,MYSQLI_ASSOC);
                    $task_ids=$row['Task_Tags'];
                    // echo $task_ids;
                    if($task_ids!='')
                    {
                        $task_ids=$task_ids . ',' . $str['Tag_ID'];    
                    }
                    else
                    {
                        $task_ids=$str['Tag_ID'];       
                    }
                    
                    $sql="UPDATE $TASK_TABLE SET Task_Tags='$task_ids' WHERE Task_ID='$objData->Task_ID'";
                    if(mysqli_query($con,$sql))
                    {
                        $str['Status']=TRUE;
                        echo (json_encode($str));
                    }
                    else
                    {
                        $str['Status']=FALSE;
                        $str['error']="Problem adding the Tag to TASK_TABLE CSV";
                        echo json_encode($str);
                    }
                }
                else
                {
                    $str['Status']=FALSE;
                    $str['error']="Problem Accessing Task table while adding a tag";
                    echo json_encode($str); 
                }
                
                
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