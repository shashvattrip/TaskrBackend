// Add due date functionality
// Add Star Marked/Favorited functionality
// Add Followers functionality on individual Tasks
// Add Tags on Individual Tasks
// Add Filter based on Tags

var myapp=angular.module('myapp',['ngRoute','ui.router','ngResource']);



myapp.directive('ngBlur', function() {
  return function( scope, elem, attrs ) {
    elem.bind('blur', function() {
      scope.$apply(attrs.ngBlur);
    });
  };
});


myapp.directive('ngFocus', function( $timeout ) {
  return function( scope, elem, attrs ) {
    scope.$watch(attrs.ngFocus, function( newval ) {
      if ( newval ) {
        $timeout(function() {
          elem[0].focus();
        }, 0, false);
      }
    });
  };
});



myapp.factory('RESTAPI',function($resource)
{
    var BASE_API_URI='http://localhost/api/:table/:operation/?id=:id';
    
    return $resource(BASE_API_URI,
            {
                id:"@id",
                table:"@table",
                operation:"@operation"
            },
            {
                List:{method:'GET',params:{id:'all',table:'tasks',operation:"view"},isArray:true},
                getOne:{method:'GET',params:{id:'',table:'tasks',operation:'view'},isArray:true},
                insert:{method:'POST',params:{table:'tasks',operation:'insert',id:''}},
                update:{method:'POST',params:{id:'',table:'tasks',operation:'update'},isArray:false},
                remove:{method:'POST',params:{id:'',table:'tasks',operation:'delete'}, isArray:false},
                getAllData:{method:'GET',params:{table:'GetAllData'},isArray:false},
                addPeopleToProject:{method:'POST',params:{table:'addNewMember',operation:'insert',id:''},isArray:false},
                addAssignedTo:{method:'POST',params:{table:'assignTask',operation:'update'},isArray:false}

            });
});


myapp.controller('DataCtrl',function($scope,$http,$stateParams,$location,JSONData,GetTags,GetProjectIDs,GetMembers,$resource,RESTAPI)
{
    
    $scope.state=$stateParams;
    $scope.routeTask_ID=$stateParams.Task_ID;
    $scope.routePID=((($stateParams.PID==null) || ($stateParams.PID==0))?0:$stateParams.PID);
    // console.log("RoutePID : " + $scope.routePID);
    // $scope.query.Task_Project_ID=$scope.routePID;
    $scope.ListAllTags=GetTags;
    // $scope.ListAllProjectIDs=GetProjectIDs;
    // $scope.ListAllMembers=GetMembers;

    // $scope.JsonData=JSONData;

    $scope.testing=function()
    {
        // console.log("Printing $scope.JsonData");
        // console.log($scope.JsonData);
        // var userLoginObj={
        //     "User_ID":"1",
        //     "Task_ID":"156",
        //     "Project_ID":"47"
        // };
                
        // console.log(userLoginObj);
        // $scope.updateTaskrREST("user_login",userLoginObj);
    }

    $scope.fullCallAPI=function()
    {

        //Redirect to my tasks
        $location.path('/');
        //gets all the tags/tasks/comments in the table
        //************this works************
        RESTAPI.getAllData({table:'GetAllData'}).$promise.then(function(value)
        {
            if(value.Status==false)
            {
                alert('fullCallAPI() received bad response!');
            }
            // console.log("Success");
            $scope.UserInfo=value.User;
            $scope.JsonData=value.allTasks;          
            if($scope.JsonData===undefined)
            {
                $scope.JsonData=[];
            }
            console.log("allTasks");
            console.log($scope.JsonData);
            $scope.ListAllProjects=value.Projects;
            // console.log($scope.UserInfo);
            if($scope.ListAllProjects===undefined)
            {
                $scope.ListAllProjects=[];
            }
            console.log($scope.ListAllProjects);

        },function(errResponse)
        {
            console.log(errResponse);
            //If you get an error here, check the alldata.php file if it has multiple echos making it not a JSONP
        });
        

        // for fetching the details of one task/tag/comment
        //******************this works********************
        // var apiTaskID=2;
        // RESTAPI.getOne({id:apiTaskID}).$promise.then(function(value)
        // {
        //     console.log("Success");
        //     console.log('Task ID : ' + value['0'].Task_ID);
        //     console.log('Task Name : ' + value['0'].Task_Name);
        // },function(errResponse)
        // {
        //     console.log(errResponse);
        // });


        // for inserting a new task/tag/comment
        // Note the POST data sent for tags/comments/tasks are different!
        //**********************this works****************
        // var objTemp={
        //     "name":"Shashvat Rules - kickass",
        //     "description":"shashvat kicks ass",
        //     "skills":"football"
        // }
        // var tableName="tasks";
        // RESTAPI.insert({table:tableName},objTemp).$promise.then(function(value)
        //     {
        //         console.log("Success");
        //     },function(errResponse)
        //     {
        //         console.log("Error");
        //     });


        // delete task/tag/comment
        //*******************this works***************
        // var tableName="tasks";
        // RESTAPI.remove({table:tableName},{id:43}).$promise.then(function(value)
        // {
        //     console.log("Deleting!");
        // },function(errResponse)
        // {
        //     console.log("Some error!");
        // });


        // update task/tag/comment
        //*************************this works******************
        // var apiUpdate={id:13,name:"Shashvat123Rulz",description:"HOHOHO!!!"};
        // var commentUpdate={id:13,body:"Updated Comment Body"};
        // var tableName="tasks";
        // RESTAPI.update({table:tableName},commentUpdate).$promise.then(function(value)
        // {
        //     console.log("success!");
        // },function(errResponse)
        // {
        //     console.log("Somethig went wrong!");
        // });
    }


    $scope.insertTaskrREST=function(tableName,obj)
    {
        // for inserting a new task/tag/comment
        // Note the POST data sent for tags/comments/tasks are different!
        //for inserting tasks, obj will be of type task
        //for inserting comment, obj will be of type Comment_Body
        //for inserting tag, obj will be of type Tag_Name

        console.log("in insertTaskrREST()");
        // console.log(obj.TN+obj.TD);
        RESTAPI.insert({table:tableName},obj).$promise.then(function(value)
        {
            //get the returned 'value'
            // console.log("Success");
           
            if(tableName=="tasks")
            {
                //find the task with Task_ID=-1
                console.log("Value : ");
                console.log(value);
                console.log("ID returned : " + value.Task_ID);
                console.log(obj);
                $scope.JsonData.push(obj);
                var tempIndex=$scope.getIndexOf(-1);
                // console.log(tempIndex);
                //add the Task_ID to local JSONData

                $scope.JsonData[tempIndex].Task_ID=value.Task_ID;    
                console.log("New Task added : " + $scope.JsonData[tempIndex].Task_Name);

                //update user_login table
                //add the newly created Task into Task_IDs column and Project into Project_IDs column

                //getting the current project
                var currProject=$location.path();
                currProject=currProject.substring(1);
                // console.log(currProject);
                
                var arr=[];
                arr=currProject.split("/");
                currProject=arr[0];
                // delimit at '/'
                if(currProject==='/' || currProject==='' || currProject=='0')
                {
                    currProject=$scope.UserInfo.User_ID;
                    // return currProject;
                }

                var userLoginObj={
                    "User_ID":$scope.UserInfo.User_ID,
                    "Task_ID":value.Task_ID,
                    // "Project_ID":$scope.JsonData[tempIndex].Project_ID
                    "Project_ID":currProject
                };

                // console.log("userLoginObj for updating user_login")
                // console.log(userLoginObj);
                // $scope.updateTaskrREST("user_login",userLoginObj);
            }
            else if(tableName=="comments")
            {
                console.log(obj);

                //set the obj.UserID to local Data
                // _USERID=obj.userID;

                //find the task the comment resides in
                var tempIndex=$scope.getIndexOf(obj.Task_ID);
                //append Comment_ID to the right comment
                console.log(tempIndex);
                obj.Comment_ID=value.Comment_ID;
                obj.User_Name=$scope.UserInfo.User_Name;
                console.log(obj.User_Name);
                console.log("Inserting comment"+obj.Comment_ID);
                //This Comment_ID should also be appended to the Tasks table in the column Comments(CSV)
                // Call the update function for tasks
                // RESTAPI.update();
                $scope.JsonData[tempIndex].Comments.push(obj);
            }
            else if(tableName=="tags")
            {
                console.log(obj.Task_ID);
                var tempIndex=$scope.getIndexOf(obj.Task_ID);
                obj.Tag_ID=value.Tag_ID;
                console.log(obj.Tag_Name);
                //update the Task 
                // Call the update function for tasks
                if($scope.JsonData[tempIndex].Tags==null)
                {
                    $scope.JsonData[tempIndex].Tags=[];   
                }
                console.log($scope.JsonData[tempIndex].Tags);
                $scope.JsonData[tempIndex].Tags.push(obj);
            }
            else if(tableName=="projects")
            {
                console.log(value);
                var DBProjectID=value.Project_ID;
                console.log("DBProjectID : ");
                console.log(DBProjectID);
                // $scope.newlyCreatedProjectID_temp=DBProjectID;
                var p={
                    "Project_ID":DBProjectID,
                    "Project_Name":obj.Project_Name
                };
                $scope.ListAllProjects.push(p);
                // $scope.addTaskbyAddingNewProject();
                //update $scope.JsonData for Projects
                // var tempIndex=$scope.getIndexOf()
                // $scope.JsonData.Projects
            }
            else
            {
                console.log("Incorrect URL parameter to the TASKR-REST-API");
            }
            
        },function(errResponse)
        {
            console.log("Error");
        });
    }

    $scope.deleteTaskrREST=function(tableName,obj)
    {
        // Here obj is Task_ID / Tag_ID / Comment_ID
        console.log("in deleteTaskrREST()");

        RESTAPI.remove({table:tableName},{id:obj}).$promise.then(function(value)
        {
            console.log("Deleting!");
        },function(errResponse)
        {
            console.log("Some error!");
        });
    }

    $scope.updateTaskrREST=function(tableName,obj)
    {
        // console.log("in updateTaskrREST()");
        // obj may be task object or Tag_Name
        
        // console.log(obj);
        RESTAPI.update({table:tableName},obj).$promise.then(function(value)
        {
            // console.log("success!");
            if(tableName=="tasks")
            {
                console.log("Updated Task");
                // console.log(value);

            }
            else if(tableName=="comments")
            {

            }
            else if(tableName=="tags")
            {

            }
            else if(tableName=="user_login")
            {
                console.log(value);
            }
            else
            {
                console.log("Incorrect URL parameter to the TASKR-REST-API");
            }
        },function(errResponse)
        {
            console.log("Somethig went wrong!");
        });
    }


    $scope.getIndexOf=function(Task_ID)
    {
        for (var i = 0; i < $scope.JsonData.length; i++) 
        {
            // console.log($scope.JsonData[i]);
            // console.log(Task_ID);
            if($scope.JsonData[i].Task_ID==Task_ID)
            {
                console.log("FOUND tempIndex");
                return i;
            }
                
        };
        
        // return ($scope.JsonData.indexOf(Task_ID));
    }

    //This PutJSONData function needs to be separate I think. But I don't know where to put it. Maybe in services? Will figure it out later
    PutJSONData=function(DataToPut)
    {
        var STORAGE_ID='Stoopid';
        // console.log('In PutJSONData factory\n I am not sure whether a factory is used to update model');
        // localStorage.setItem(STORAGE_ID,JSON.stringify(DataToPut));
    };

    $scope.CountOfChangesInJsonData=0;

    //Changes for every keystroke, it doesn't wait for the submit button to be pressed
    //Submit button is only to remove the input box and bring back the label
    //************************************************************
    /* .$watch is not a good function to use. Instead call RESTful API at every change to the $scope
     .$watch function's functionality must be custom implemented. */
    //************************************************************ 

    $scope.$watch('JsonData',function()
    {
        $scope.CountOfChangesInJsonData++;
        // console.log($scope.CountOfChangesInJsonData);
        PutJSONData($scope.JsonData);

        $scope.ListAllTags=GetTags;
    },true);
    

    //All these functions required for editing
    $scope.newTask = '';
    $scope.editedTaskName = null;
    $scope.editedProjectName=null;


    $scope.getProjectName=function()
    {
        var currProject=$location.path();
        currProject=currProject.substring(1);
        // console.log(currProject);
        
        var arr=[];
        arr=currProject.split("/");
        currProject=arr[0];
        // delimit at '/'
        if(currProject==='/' || currProject==='' || currProject=='0')
        {
            currProject='all my';
            return currProject;
        }
            
        // console.log("All Projects");
        // console.log($scope.ListAllProjects);
        if($scope.ListAllProjects)
        {
            // console.log($scope.ListAllProjects.length);
            for (var i = 0; i < $scope.ListAllProjects.length ; i++) 
            {
                if($scope.ListAllProjects[i].Project_ID==currProject)
                {
                    return $scope.ListAllProjects[i].Project_Name;
                }
            };
        }
        // console.log(currProject);
        // currProject=parseInt(currProject);
        // console.log($scope.JsonData.length());
        // for (var i = 0; i < $scope.JsonData.length; i++) 
        // {
        //     // console.log($scope.JsonData[i]);
        //     // console.log(Task_ID);
        //     if($scope.JsonData[i].Project_ID==currProject)
        //     {
        //         console.log("FOUND tempIndex");
        //         return $scope.JsonData[i].Project_Name;
        //     }
                
        // };   
        return currProject;
    }


    $scope.getProjectIDfromStateParams=function()
    {
        var currProject=$location.path();
        currProject=currProject.substring(1);
        
        var arr=[];
        arr=currProject.split("/");
        currProject=arr[0];
        currTask=arr[2];
        console.log(currTask);
        // delimit at '/'
        if(currProject==='/' || currProject==='')
            currProject=$scope.UserInfo.User_ID;
        
        currProject=parseInt(currProject);
        console.log(currProject);
        if(currProject==0)
        {
            //find the Project_ID of this task
            //you have the Task_ID
            var tempIndex=$scope.getIndexOf(currTask);
            // console.log($scope.JsonData[tempIndex].Task_Project_ID);
            currProject=$scope.JsonData[tempIndex].Task_Project_ID;
        }

        console.log("Final Project : " + currProject);
        for(var i=0;i<$scope.ListAllProjects.length;i++)
        {
            if(currProject==$scope.ListAllProjects[i].Project_ID)
            {
                return i;
            }    
        }
        
        // return currProject;

        // console.log(currProject);
    }
    
    $scope.addTask = function () 
    {
        var newTask = $scope.newTask.trim();
        if (!newTask.length) 
        {
            return;
        }

        var currProject=$location.path();
        currProject=currProject.substring(1);
        
        var arr=[];
        arr=currProject.split("/");
        currProject=arr[0];
        // delimit at '/'
        if(currProject==='/' || currProject==='')
            currProject=$scope.UserInfo.User_ID;
        
        currProject=parseInt(currProject);
        console.log(currProject);
        

        var tempData={
            
            "Task_ID":"-1",//This should not be sent to the server, but the server needs to return this on success so that it can be added to the localdata
            "Task_Name":newTask,
            "User_ID":$scope.UserInfo.User_ID,
            "Task_Description":' Task Description ',
            "Task_Project_ID":currProject,
            "Comments":[],
            "Task_Followers":[],
            "Task_Star":0,
            "Task_Clock":0,
            "DueDate":"14-2-2014",
            "Tags":[],
            "assignedTo":"Shashvat",
            "assignedBy":"Shashvat",
            "completed":false
        }
        // console.log(tempData);
        // $scope.JsonData.push(tempData);
        //Add the task to Database
        $scope.insertTaskrREST("tasks",tempData);

        //Update the Projects Table in the Database

        $scope.newTask = '';
    };


    $scope.startEditing = function (task,editWhat) 
    {
        switch(editWhat)
        {
            case "taskName":
                task.editingTaskName=true;
                $scope.editedTaskName = task;
                break;

            case "projectName":
                task.editingProjectName=true;
                $scope.editedProjectName=task;
                break;
        }
        
        
        var tempPath='/'+$scope.routePID+'/task/'+task.Task_ID;
        // console.log(tempPath);
        $location.path(tempPath);
    };

    //Submit button is only to remove the input box and bring back the label
    // The $scope.JsonData gets updated for every keystroke in the input as the input box is ng-model="task.TN"
    $scope.doneEditing = function (task,editWhat) 
    {
        switch(editWhat)
        {
            case "taskName":
                task.editingTaskName=false;
                task.Task_Name = task.Task_Name.trim();
                $scope.editedTaskName=null;
                if (!task.Task_Name) 
                {
                    $scope.removeTask(task);
                }   

                $scope.updateTaskrREST("tasks",task);
                break;
            case "projectName":
                task.editingProjectName=false;
                // task.PID = task.PID.trim();
                $scope.editedProjectName=null;
                if (!task.PID) 
                {
                    task.PID=0;
                }   
                break;
        }
         

    };


    $scope.removeTask = function (task) 
    {
        // tasksLocal.splice(tasksLocal.indexOf(task), 1);
        $scope.JsonData.splice($scope.JsonData.indexOf(task),1);
        $scope.deleteTaskrREST("tasks",task.Task_ID);
    };


    // $scope.clearDoneTasks = function () 
    // {
    //     $scope.JsonData = tasksLocal.filter(function (val) 
    //     {
    //         return !val.completed;
    //     });
    // };


    // $scope.markAll = function (task) 
    // {
    //     tasksLocal.forEach(function (task) 
    //     {
    //         tasksLocal.completed = done;
    //     });
    // };

    $scope.AddingANewTag=function()
    {
        $scope.AdddingANewTag=1;   
    }

    $scope.addTag=function(task)
    {
        // check if duplicate
        console.log("hoho");
        if($scope.ListAllTags.indexOf($scope.newTag)>-1)    
            return;
        var tempTag={
            "Tag_Name":$scope.newTag,
            "Task_ID":task.Task_ID
        }
        // console.log("hoho123");
        // add tag into the tag table
        $scope.insertTaskrREST("tags",tempTag);
        $scope.newTag='';
        $scope.AdddingANewTag=0;
        // ListAllTags=GetTags;
        // $scope.$apply;
    }

    $scope.removeTag=function(index,tag)
    {
        var ind=$scope.JsonData[index].Tags.indexOf(tag);
        if(ind>-1)
        {
            //update locally
            $scope.JsonData[index].Tags.splice(ind,1);
            //update the task
            $scope.updateTaskrREST("tasks",$scope.JsonData[index]);
            
        }
            
    }



    //Functions to get the filter by tag functionality working

    $scope.selectedTags=[];

    $scope.isChecked = function(tag) 
    {
        // console.log('$scope.selectedTags.indexOf(tag)');
        // console.log($scope.selectedTags.indexOf(tag));
        // console.log($scope.selectedTags);
        if($scope.selectedTags.indexOf(tag)>-1)
        // if($scope.ListAllTags[$scope.ListAllTags.(indexOf(tag))].filter==false)
        {
            return 'icon-ok';
        }
        return false;
    };

    // $scope.addPeopleToProject=function()
    // {
    //     if(!$scope.newTeamMember)
    //         return; 
    //     else
    //     {
    //         //check if the team member is already present in ListAllMembers
    //         console.log("here");

    //         $scope.newTeamMember='';
    //     }
    // }

    $scope.SetSelectedTags=function(tag)
    {
        // console.log(tag);
        if($scope.selectedTags.indexOf(tag)==-1)
        {
            $scope.selectedTags.push(tag);
            console.log($scope.selectedTags);
        }
            
            
        else if($scope.selectedTags.indexOf(tag)>-1)
        
            $scope.selectedTags.splice($scope.selectedTags.indexOf(tag),1);
            
        
        // console.log($scope.selectedTags);
    }


    $scope.addFollower=function(task)
    {
        
        $scope.AddingANewFollower=0;
        $scope.newFollower='';
    }

    $scope.removeFollower=function(task,follower)
    {
        task.Task_Followers.splice(task.Task_Followers.indexOf(follower),1);
        //update the task
        $scope.updateTaskrREST("tasks",task);
        
    }

    $scope.getAssignedTo=function(task)
    {
        // console.log(task.Task_assignedTo);
        return task.Task_assignedTo;
    }


    $scope.addAssignedTo=function(index)
    {
        // console.log("Passing index");
        // console.log(index);
        // if(!$scope.JsonData[index].Task_assignedTo)
        //     $scope.JsonData[index].Task_assignedTo="Shashvat";
        // else 
        //     $scope.JsonData[index].Task_assignedTo="changed";
        var obj={
            "User_ID":"5",
            "AssignedByUserID":$scope.UserInfo.User_ID,
            "User_Name":"Sneha"
        };

        RESTAPI.addAssignedTo({tableName:"assignTask"},obj).$promise.then(function(value)
        {
            console.log(value);
        },function(errResponse)
        {
            console.log(errResponse);
        });
        
    }

    $scope.removeAssignedTo=function(index)
    {
        $scope.JsonData[index].assignedTo=null;
    }

    

    $scope.newProject=function()
    {
        var newProject = $scope.newProjectName.trim();

        if (!newProject.length) 
        {
            return;
        }
        var obj=
        {
            "Project_Name":newProject,
            "User_ID":$scope.UserInfo.User_ID
        }
        // console.log(obj);

        //insert the new project into Project table
        //Insert new dummy task - this is done inside $scope.insertTaskrREST("projects",obj)
        //update user_login table
        //add the newly created Task into Task_IDs column and Project into Project_IDs column
        $scope.insertTaskrREST("projects",obj);
        console.log("Is this executed?");
        //yes it is

        //close the modal box
        $('#addAProject').modal('hide');
        //change the url to '/'
        $scope.closeAddProjectModalBox();    
    }
    $scope.closeAddProjectModalBox=function()
    {
        $location.path('/');
        $scope.newProjectName='';
    }

    $scope.addComment=function(task)
    {
        var newComment = $scope.comment.trim();
        console.log($scope.comment);
        if (!newComment.length) 
        {
            return;
        }
        console.log(task);
        console.log(task.Comments);
        var tempComment={
            "Comment_Body":newComment,
            "Task_ID":task.Task_ID,
            "User_ID":$scope.UserInfo.User_ID
        };

        // task.Comments.push(tempComment);
        //insert into comment table
        $scope.insertTaskrREST("comments",tempComment);
        //update the
        $scope.comment='';
    }

    $scope.starTask=function(task)
    {
        if(task.Task_Star==1)
        {
            task.Task_Star=0;
            $scope.updateTaskrREST("tasks",task);
        }
            
        else
        {
            $scope.updateTaskrREST("tasks",task);
            task.Task_Star=1;
        }
            
        // console.log(task);
    }

    $scope.clockTask=function(task)
    {
        if(task.Task_Clock==1)
        {
            $scope.updateTaskrREST("tasks",task);
            task.Task_Clock=0;
        }
            
        else
        {
            $scope.updateTaskrREST("tasks",task);
            task.Task_Clock=1;
        }
    }


    $scope.openDatePicker=function(task)
    {
        var nowTemp = new Date();
        //get current DateTime

        // Eg : now = Sun Feb 02 2014 21:59:16 GMT+0530 (India Standard Time)
        var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0); 
        $(".customDatePicker").datepicker('show');
        
        // var selectedDate;
        
        // http://www.eyecon.ro/bootstrap-datepicker/
        $(".customDatePicker").on('changeDate',function(ev)
        {
            console.log("onchangeDate event handler");
            var temp=ev.date;
            // console.log(temp);
            // task.DueDate=ev.date;
            // console.log("DueDate inside Event Handler");
            // console.log(temp);
            var str=temp.toString().split(" ");
            console.log(str);
            // console.log(str[1]);
            switch(str[1])
            {
                case "Jan":str[1]=1;break;
                case "Feb":str[1]=2;break;
                case "Mar":str[1]=3;break;
                case "Apr":str[1]=4;break;
                case "May":str[1]=5;break;
                case "Jun":str[1]=6;break;
                case "Jul":str[1]=7;break;
                case "Aug":str[1]=8;break;
                case "Sep":str[1]=9;break;
                case "Oct":str[1]=10;break;
                case "Nov":str[1]=11;break;
                case "Dec":str[1]=12;break;
            };


            task.DueDate=str[2] + '-' + str[1] + '-' + str[3];
            console.log(task.DueDate);
            $scope.updateTaskrREST("tasks",task);
            PutJSONData($scope.JsonData);
            // task.DueDate={
            //     "day":,"month":,"year":
            // };    
        });

        
     
    }

    $scope.SearchAllProjects=function()
    {
        $location.path('/');
    }


    $scope.addPeopleToProject=function()
    {
        console.log($scope.newTeamMember);

        //If the user isn't on a project, the invitation will be sent out and the new team member will be added but not associated with any project

        //Ensure that the user has selected a project

        var obj={
            "Project_ID":$scope.routePID,
            "User_ID":$scope.UserInfo.User_ID,
            "New_Member_Email":$scope.newTeamMember
        };

        $scope.newTeamMember='';
        RESTAPI.addPeopleToProject({table:'addPeople'},obj).$promise.then(function(value)
        {
            console.log("Successfully sent pachage to server");
            //value will contain
            // obj=
            // {
            //     "User_ID":newMemberID,
            //     "User_Name":newMemberName,
            // }

            //add this user to the right project

        },function(errResponse)
        {
            console.log("Error while adding a new team member");
        });
        
    }

    $scope.printTasks=function()
    {
        // var printCSS=new String('
        //     <link rel=\"stylesheet\" type=\"text/css\" href=\"css/bootstrap.min.css\">
        //     <link rel=\"stylesheet\" type=\"text/css\" href=\"css/bootstrap-datepicker.css\">
        //     <link rel=\"stylesheet\" type=\"text/css\" href=\"css/styles.css\">');

    }

});


myapp.filter('tagFilter', function() {
  return function(Data, selectedTags) {
    console.log('selectedTags array');
    console.log(selectedTags);
    if (selectedTags.length === 0) {
      return Data;
    } else {
      tempData = [];
      for(var k in selectedTags) {
              var value = selectedTags[k];
              for(var i  in Data) {
                  for(var z in Data[i].tags) {
                      if(value==Data[i].tags[z]) {
                         tempData.push(Data[i]);
                         break;
                      } 
                  }
              }
            }
            return tempData;
    }
  };
});


myapp.config(function($stateProvider,$urlRouterProvider,$routeProvider)
{
  //
  // For any unmatched url, send to /route1
  $urlRouterProvider.otherwise("") 
   

  $stateProvider

  .state('route2',
    {
        url:"/calendar",
        views:
        {
            "ProjectPane":
            {
                templateUrl:"css/partials/calendar.html",
                controller:"DataCtrl"        
            },
            "TaskPane":
            {
                templateUrl:"css/partials/emptyIndi.html"
            }
        }
        
      
    })
    
    .state('inbox',
    {
        url:"/inbox",
        views:
        {
            "ProjectPane":
            {
                templateUrl:"css/partials/inbox.html",
                controller:"InboxCtrl"
            }
        }
    })

    .state('route1',
    {
        url:"/:PID",
        views:
        {
            "ProjectPane":
            {
            templateUrl:"css/partials/Tasks.html",
            controller:"DataCtrl"
            }, 
            "TaskPane":
            {
                templateUrl:"css/partials/emptyIndi.html"
            }
        }
      
    })

        // .state('route1.task',
            .state('route1.task',
            {
                url:"/task/:Task_ID",
                views:
                {
                    // The @ suffix addresses the view in the higher state
                    "TaskPane@": 
                    {
                        templateUrl:"css/partials/IndiTasks.html",
                        controller:"DataCtrl"        
                    }
                    
                }
                
            })
});