<?php
session_start();
        //include '/php-sdk/facebook.php';
        require_once('/php/facebook/php-sdk/facebook.php');

          $config = array(
              'appId' => '589333571151255',
              'secret' => '6c80d2a59b63979beb65933fba4bae94',
              'fileUpload' => false, // optional
              'allowSignedRequest' => false, // optional, but should be set to false for non-canvas apps
          );
          $server = array_merge($_GET,$_REQUEST,$_POST);
        $ip = "localhost";
        $facebook = new Facebook($config);
        $user_id = $facebook->getUser();

        echo $user_id;
        // echo $_REQUEST['state'];
        // echo getType($_REQUEST['state']);
        // die();
        if($user_id)
        {
            $_SESSION['u_id']=$user_id;

      // We have a user ID, so probably a logged in user.
      // If not, we'll get an exception, which we handle below.
          try 
          {

            $user_profile = $facebook->api('/me','GET');
            //echo "Name: " . $user_profile['name'];
            //echo json_encode($user_profile);
            //echo $user_profile['email'];
            //setcookie($name,$value,$expire);
            

            $_SESSION['userdetails']=$user_profile;
            $name = $_SESSION['userdetails']['name'];
            $email = $_SESSION['userdetails']['email'];
            $fb_ID = $_SESSION['userdetails']['id'];
            //echo json_encode($_SESSION['userdetails'])
            include '/php/facebook/connect_db.php';
           // echo json_encode($_SESSION['userdetails']['name']);
            $rec = mysqli_fetch_array(mysqli_query($con, "SELECT Facebook_ID FROM $LOGIN_TABLE WHERE Facebook_ID = $fb_ID"));
            if($rec)
            {   
                $_SESSION['userdetails']['registered'] = "Hai bhai..nai chalega"   ;
            }
            else
            {
                
              
                //Check if the id is already present in the table or not
                // TODO better error handling

                //GET the LAST PRojectID
                //GET the LAST USerID
                //Check which is greater
                //insert that as the new USERID
                //INSERT THAT as the new projectID

                $users= mysqli_fetch_all(mysqli_query($con, "SELECT User_ID FROM $LOGIN_TABLE"));
                // echo json_encode($users);

                $user_count = count($users);
                if($user_count)
                    $UserIDofTheLastUser=$users[$user_count-1][0];
                else
                    $UserIDofTheLastUser = 0;

                $proj  = mysqli_fetch_all(mysqli_query($con, "SELECT Project_ID FROM $PROJECT_TABLE"));

                $proj_count = count($proj);
                
                if($proj_count)
                    $ProjectIDofTheLastProject=$proj[$proj_count-1][0];
                else
                    $ProjectIDofTheLastProject =0;

                if(((int)$UserIDofTheLastUser==0))
                {
                    $common_id=1;
                }
                elseif((int)$UserIDofTheLastUser>(int)$ProjectIDofTheLastProject)
                {
                    $common_id=(int)$UserIDofTheLastUser;

                    $common_id++;
                }
                else
                {
                    $common_id=(int)$ProjectIDofTheLastProject;
                    $common_id++;
                }

                //  $testString="15";
                // echo "TOTOTOT!\n";
                // echo $ProjectIDofTheLastProject . '+' . $UserIDofTheLastUser;
                // echo "+++";


                // $testString="15";
                // echo "LULZ!\n";
                // echo (int)$testString;
                // echo "+++";


                // echo "JSHSH!\n";
                // echo ($common_id) ;
                // echo "+++";

                // die("Madarchod!");


                $sql  = mysqli_query($con, "INSERT INTO $LOGIN_TABLE VALUES ('$common_id','$name', '$email',
                  '', '', NOW(), '','$common_id', '$fb_ID') ");
                if($sql)
                {
                    $_SESSION['userdetails']['register_status'] = "Yayy Chal gayiii" ;
                }
                else
                {
                    $_SESSION['userdetails']['register_status'] = "Not working" ;

                    $_SESSION['userdetails']['error'] =mysqli_error($sql);
                    $_SESSION['userdetails']['error2'] = mysqli_errno($sql);
                    echo json_encode($_SESSION['userdetails']['register_status']);
                    echo "WTFFFFFFF";
                    die();
                }


                /*$id_q=mysqli_query($con,"SELECT User_ID FROM $LOGIN_TABLE WHERE Facebook_ID = $fb_ID");
                if($id_q)
                {
                    $id_array = mysqli_fetch_array($id_q,MYSQLI_ASSOC);
                    $id = $id_array['User_ID'];
                }*/
                $p_name = $_SESSION['userdetails']['name']  . " project";
                //Check if the id is already present in the table or not
                // TODO better error handling
                // echo "Ba bla ala[akakka";
                // echo $common_id;
                // echo $p_name;
                
                $project  = mysqli_query($con, "INSERT INTO $PROJECT_TABLE VALUES ('$common_id', '$p_name',NOW(),'Default Project','$common_id','Active')");
                
                if($project)
                {
                    $_SESSION['userdetails']['project_status'] = "Added";
                }
                else
                {
                    $_SESSION['userdetails']['project_status'] = "Some Problem";
                    echo mysqli_error();
                    echo "Randaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaap";
                    die();
                }
                // getType($_REQUEST['state'])=="integer"
                if($server['pid'])
                {
                    // echo "Control is coming here";
                    // die();
                    $res = mysqli_fetch_array(mysqli_query($con,"SELECT Project_members FROM $PROJECT_TABLE WHERE Project_ID = '$server[pid]'"));
                    $tempProjectMembers=array();

                    $tempProjectMembers=explode(',',$res['Project_members']);

                    // echo "Project Members\n";  
                    // echo $res['Project_members'];  
                    // die();
                    $counter =0;
                    for($m=0 ; $m<count($tempProjectMembers) ; $m++)
                    {
                        if($tempProjectMembers[$m]==$common_id)
                            $counter++;
                    }
                    if($counter==0)
                    
                    {
                        array_push($tempProjectMembers, $common_id);
                    }
                    //echo "json encode";
                    //echo json_encode($tempProjectMembers);
                  
                    $membersCSV='';
                    for($i=0 ; $i<count($tempProjectMembers) ; $i++)
                    {
                        if($membersCSV=='')
                          $membersCSV = $tempProjectMembers[$i] ;  
                        else
                          $membersCSV = $membersCSV . ',' . $tempProjectMembers[$i] ;
                        
                    }

                    echo "CSV\n";  
                    echo $membersCSV;
                  
                    $project  = mysqli_query($con, "UPDATE $PROJECT_TABLE SET Project_Members = '$membersCSV' WHERE Project_ID = '$server[pid]'");

                    if($project)
                    {
                        echo "Done";
                    }
                    else
                    {
                        echo "not done in anyyyyyyy wayyyyyy" . mysql_error($project);
                        die();
                    }

                    $Proj_IDs = $common_id . "," . $server['pid'];
                    $update  = mysqli_query($con,"UPDATE $LOGIN_TABLE SET Project_IDs = '$Proj_IDs' WHERE User_ID = $common_id");
                    if($update)
                    {
                        echo "Updated";
                    }
                    else
                    {
                        echo "SQL problemmooooo";
                        die();
                    }

                }
            }
            $loginUrl = $facebook->getLoginUrl(
            array(
                'canvas'    => 1,
                'fbconnect' => 0,
                'scope' => 'email,publish_stream,offline_access',
                'redirect_uri' => 'http://' . $ip . '/github/taskrbackend/index.php', //the url to go to after a successful login
            ));

            echo "<script type='text/javascript'>top.location.href = '$loginUrl';</script>";
   

          } 
          catch(FacebookApiException $e) 
          {
            // If the user is logged out, you can have a 
            // user ID even though the access token is invalid.
            // In this case, we'll get an exception, so we'll
            // just ask the user to login again here.
            $login_url = $facebook->getLoginUrl(); 
            //echo 'Please <a href="' . $login_url . '">login.</a>';
            echo "<a href='" . $login_url . "' class = 'facebook'><i class='icon-facebook'></i></a>";

            error_log($e->getType());
            error_log($e->getMessage());
          }   
         } 
         else 
         {

          // No user, print a link for the user to login
          $login_url = $facebook->getLoginUrl();
          echo "<a href='" . $login_url . "' class = 'facebook'><i class='icon-facebook'></i></a>";

         }

?>