<?php 
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

                //return json_encode($json);

            }

            else if($id=='all')
            {
                $records=mysqli_query($con,"SELECT Task_ID, Task_Name FROM $TASK_TABLE");

                $json=mysqli_fetch_all($records, MYSQLI_ASSOC);
                
                echo json_encode($json);

                //return json_encode($json);

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
            //header('Content-Type: application/json; charset=utf-8');
        
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
                    $str['error']='SQL Error';  
                    exit(json_encode($str));  
                }
            


        }

        public function InsertTask($name,$desc)
        {
            echo $name;
            echo $desc;
            include 'connect_db.php';
            
            //header('Content-Type: application/json; charset=utf-8');
        
            $sql = "INSERT INTO $TASK_TABLE VALUES(NULL,'$name','$desc','','','','')";
               
            if(mysqli_query($con,$sql))
                {
                    $str['Status']=TRUE;
                    echo "Mui Mui";
                    echo json_encode($str);                                
                    
                }

            else 
                {
                    $str['Status']=FALSE;
                    $str['error']='SQL Error';  
                    exit(json_encode($str));
                }
            


        }

         public function UpdateTask($id,$name,$desc)
        {
            include 'connect_db.php';
            
            //header('Content-Type: application/json; charset=utf-8');
        
            $sql = "UPDATE $TASK_TABLE SET Task_Name='$name',Task_Description='$desc' WHERE Task_ID=$id";
               
            if(mysqli_query($con,$sql))
                {
                    $str['Status']=TRUE;
                     
                    exit(json_encode($str));                                    
                    
                }

            else 
                {
                    $str['Status']=FALSE;
                    $str['error']='SQL Error';  
                    exit(json_encode($str));
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
            
            //header('Content-Type: application/json; charset=utf-8');
        
            $sql = "DELETE FROM $COMMENT_TABLE WHERE Comment_ID=$id";
               
            if(mysqli_query($con,$sql))
                {
                    $str['Status']=TRUE;
                      
                    exit(json_encode($str));                                    
                    
                }

            else 
                {
                    $str['Status']=FALSE;
                    $str['error']='SQL Error';  
                    exit(json_encode($str));
                }
            


        }


        public function UpdateComment($id,$body)
        {
            include 'connect_db.php';
            
            //header('Content-Type: application/json; charset=utf-8');
        
            $sql = "UPDATE $COMMENT_TABLE SET Comment_Body='$body' WHERE Comment_ID=$id";
               
            if(mysqli_query($con,$sql))
                {
                    $str['Status']=TRUE;
                      
                    exit(json_encode($str));                                   
                    
                }

            else 
                {
                    $str['Status']=FALSE;
                    $str['error']='SQL Error';  
                    exit(json_encode($str));
                }
            


        }

        public function InsertComment($body)
        {
            include 'connect_db.php';
            
            //header('Content-Type: application/json; charset=utf-8');
        
            $sql = "INSERT INTO $COMMENT_TABLE VALUES('','$body')";
               
            if(mysqli_query($con,$sql))
                {
                    $str['Status']=TRUE;
                      
                    exit(json_encode($str));                                    
                    
                }

            else 
                {
                    $str['Status']=FALSE;
                    $str['error']='SQL Error';  
                    exit(json_encode($str));   
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
                $records=mysqli_query($con,"SELECT Tag_ID, Tag_Name FROM $TAG_TABLE WHERE Tag_ID=$id");
            
                $json=mysqli_fetch_all($records, MYSQLI_ASSOC);
                
                return json_encode($json);
            }

            else if($id=='all')
            {
                $records=mysqli_query($con,"SELECT Tag_ID, Tag_Name FROM $TAG_TABLE");
            
                $json=mysqli_fetch_all($records, MYSQLI_ASSOC);
                
                return json_encode($json);
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
            
            //header('Content-Type: application/json; charset=utf-8');
        
            $sql = "DELETE FROM $TAG_TABLE WHERE Tag_ID=$id";
               
            if(mysqli_query($con,$sql))
                {
                    $str['Status']=TRUE;
                     
                    exit(json_encode($str));                                    
                    
                }

            else 
                {
                    $str['Status']=FALSE;
                    $str['error']='SQL Error';  
                    exit(json_encode($str));  
                }
            


        }

        

        

        public function InsertTag($name)
        {
            include 'connect_db.php';
            
            //header('Content-Type: application/json; charset=utf-8');
        
            $sql = "INSERT INTO $TAG_TABLE VALUES('','$name')";
               
            if(mysqli_query($con,$sql))
                {
                    $str['Status']=TRUE;
                    
                    exit(json_encode($str));                                    
                    
                }

            else 
                {
                    $str['Status']=FALSE;
                    $str['error']='SQL Error';  
                    exit(json_encode($str));   
                }
            


        }

       

        

        public function UpdateTag($id,$name)
        {
            include 'connect_db.php';
            
            //header('Content-Type: application/json; charset=utf-8');
        
            $sql = "UPDATE $TAG_TABLE SET Tag_Name='$name' WHERE Tag_ID=$id";
               
            if(mysqli_query($con,$sql))
                {
                    $str['Status']=TRUE;
                    
                    exit(json_encode($str));                                    
                    
                }

            else 
                {
                    $str['Status']=FALSE;
                    $str['error']='SQL Error';  
                    exit(json_encode($str));
                }
            
        }

}

    

?>