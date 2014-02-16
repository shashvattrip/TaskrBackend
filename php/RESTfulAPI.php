<?php
function return_API_response($method, $api_response)
{
    include 'connect_db.php';
    // Define HTTP responses
    $http_response_code = array(
        200 => 'OK',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found'
    );
 
    // Set HTTP Response
    header('HTTP/1.1 '.$api_response['status'].' '.$http_response_code[ $api_response['status'] ]);
 
    // Process different methods
    if( strcasecmp($method,'GetAllTasks') == 0 )
    {
 
        header('Content-Type: application/json; charset=utf-8');
 
        $records=mysqli_query($con,"SELECT Task_ID, Task_Name FROM task_basic_info");
        $json=mysqli_fetch_all($records, MYSQLI_ASSOC);
        echo json_encode($json);
    }
    else if(strcasecmp($method,'GetAllTags')==0)
    {
        header('Content-Type: application/json; charset=utf-8');
        $records=mysqli_query($con,"SELECT Tag_ID, Tag_Name FROM tag_basic_info");
        $json=mysqli_fetch_all($records, MYSQLI_ASSOC);
        echo json_encode($json);   
    }
    else if(strcasecmp($method,'GetAllComments')==0)
    {
        header('Content-Type: application/json; charset=utf-8');
        $records=mysqli_query($con,"SELECT Comment_ID, Comment_Body FROM comment_basic_info");
        $json=mysqli_fetch_all($records, MYSQLI_ASSOC);
        echo json_encode($json);      
    }
    else
    {
 
        
        header('Content-Type: text/html; charset=utf-8');
 
        //in case of error
        echo "What the fuck?";
    }
    exit;
 
}
  
// Define API response codes and their related HTTP response
$api_response_code = array(
    0 => array('HTTP Response' => 400, 'Message' => 'Unknown Error'),
    1 => array('HTTP Response' => 200, 'Message' => 'Success'),
    2 => array('HTTP Response' => 403, 'Message' => 'HTTPS Required'),
    3 => array('HTTP Response' => 401, 'Message' => 'Authentication Required'),
    4 => array('HTTP Response' => 401, 'Message' => 'Authentication Failed'),
    5 => array('HTTP Response' => 404, 'Message' => 'Invalid Request'),
    6 => array('HTTP Response' => 400, 'Message' => 'Invalid Response Format')
);
 
// Set default HTTP response of 'ok'
$response['code'] = 1;
$response['status'] = 200;
$response['data'] = NULL; 
 
// Return Response to browser
return_API_response($_GET['method'], $response);
 
?>