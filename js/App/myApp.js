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
                remove:{method:'POST',params:{id:'',table:'tasks',operation:'delete'}, isArray:false}

            });
});


myapp.controller('DataCtrl',function($scope,$http,$stateParams,$location,JSONData,GetTags,GetProjectIDs,GetMembers,$resource,RESTAPI)
{
    
    $scope.state=$stateParams;
    $scope.routeTID=$stateParams.TID;
    $scope.routePID=$stateParams.PID;
    $scope.ListAllTags=GetTags;
    $scope.ListAllProjectIDs=GetProjectIDs;
    $scope.ListAllMembers=GetMembers;


    $scope.testing=function()
    {
        console.log("Testing Function");
    }
    //this should NOT come from JSONData
    //this should come from RESTfulAPI
    var tasksLocal = $scope.JsonData=JSONData;
    
    // console.log(JSONData);
    
    $scope.fullCallAPI=function()
    {
        //gets all the tags/tasks/comments in the table
        //************this works************
        // RESTAPI.List().$promise.then(function(value)
        // {
        //     console.log("Success");
        //     //prints out array of tasks
        //     value.forEach(function(data)
        //     {
        //         console.log('Task ID : ' + data.Task_ID);
        //         console.log('Task Name : ' + data.Task_Name);
        //     })
        // });
        

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
            console.log("Success");
            // console.log("ID returned : " + value.Task_ID);
            
            if(tableName=="tasks")
            {
                //find the task with TID=-1
                var tempIndex=$scope.getIndexOf(-1);
                //add the TID to local JSONData
                $scope.JsonData[tempIndex].TID=value.Task_ID;    
            }
            else if(tableName=="comments")
            {
                console.log(obj);
                //set the obj.UserID to local Data
                // _USERID=obj.userID;

                //find the task the comment resides in
                var tempIndex=$scope.getIndexOf(obj.Task_ID);
                //append Comment_ID to the right comment
                obj.Comment_ID=value.Comment_ID;
                console.log("Inserting comment"+obj.Comment_ID);
                //This Comment_ID should also be appended to the Tasks table in the column Comments(CSV)
                // Call the update function for tasks
                // RESTAPI.update();
                $scope.JsonData[tempIndex].Comments.push(obj);
            }
            else if(tableName=="tags")
            {
                var tempIndex=$scope.getIndexOf(obj.Task_ID);
                obj.Tag_ID=value.Tag_ID;

                //update the Task 
                // Call the update function for tasks
                // $scope.updateTaskrREST("tasks",$scope.JsonData[tempIndex]);
                $scope.JsonData[tempIndex].tags.push(obj);
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
        console.log("in updateTaskrREST()");
        // obj may be task object or Tag_Name
        
        // console.log(obj);
        RESTAPI.update({table:tableName},obj).$promise.then(function(value)
        {
            // console.log("success!");
            if(tableName=="tasks")
            {
                console.log("Inside tasks update if-then-else");

            }
            else if(tableName=="comments")
            {

            }
            else if(tableName=="tags")
            {

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


    $scope.getIndexOf=function(TID)
    {
        for (var i = 0; i < $scope.JsonData.length; i++) 
        {
            if($scope.JsonData[i].TID==TID)
                return i;
        };
        
        // return ($scope.JsonData.indexOf(TID));
    }

    //This PutJSONData function needs to be separate I think. But I don't know where to put it. Maybe in services? Will figure it out later
    PutJSONData=function(DataToPut)
    {
        var STORAGE_ID='Stoopid';
        // console.log('In PutJSONData factory\n I am not sure whether a factory is used to update model');
        localStorage.setItem(STORAGE_ID,JSON.stringify(DataToPut));
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


    $scope.getProjectID=function()
    {
        var currProject=$location.path();
        currProject=currProject.substring(1);
        // console.log(currProject);
        
        var arr=[];
        arr=currProject.split("/");
        currProject=arr[0];
        // delimit at '/'
        if(currProject==='/' || currProject==='')
            currProject=0;
        // console.log(currProject);
        currProject=parseInt(currProject);
        return currProject;
    }


    
    $scope.addTask = function () 
    {
        var newTask = $scope.newTask.trim();
        if (!newTask.length) 
        {
            return;
        }

        //Generate a TID which is not used by any other task
        //This immitates the server's functionality to ensure unique TIDs
        var occupiedTIDs = [];
        for(var i=0;i<$scope.JsonData.length;i++)
            occupiedTIDs.push($scope.JsonData[i].TID);

        var _TID;
        // console.log(occupiedTIDs);
        for(var i=100;i>=0;i--)
            if(occupiedTIDs.indexOf(i)==-1)
            {
                _TID=i;
                break;
            }
            // console.log(i);

        var currProject=$location.path();
        currProject=currProject.substring(1);
        // console.log(currProject);
        
        var arr=[];
        arr=currProject.split("/");
        currProject=arr[0];
        // delimit at '/'
        if(currProject==='/' || currProject==='')
            currProject=0;
        // console.log(currProject);
        currProject=parseInt(currProject);
        $scope.JsonData.push(
        {
            "PID":currProject,
            "TID":-1,//This should not be sent to the server, but the server needs to return this on success so that it can be added to the localdata
            "TN":newTask,
            "TD":' Task Description ',
            "Comments":[],
            "fol":[],
            "star":0,
            "DueDate":"14-2-2014",
            "tags":[],
            "assignedTo":"Shashvat",
            "assignedBy":"Shashvat",
            completed:false
        });
        
        var tempData={
            "PID":currProject,
            "TID":-1,//This should not be sent to the server, but the server needs to return this on success so that it can be added to the localdata
            "TN":newTask,
            "TD":' Task Description ',
            "Comments":[],
            "fol":[],
            "star":0,
            "DueDate":"14-2-2014",
            "tags":[],
            "assignedTo":"Shashvat",
            "assignedBy":"Shashvat",
            completed:false
        }

        $scope.insertTaskrREST("tasks",tempData);
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
        
        
        var tempPath='/'+$scope.routePID+'/task/'+task.TID;
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
                task.TN = task.TN.trim();
                $scope.editedTaskName=null;
                if (!task.TN) 
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
        tasksLocal.splice(tasksLocal.indexOf(task), 1);
        $scope.deleteTaskrREST("tasks",task.TID);
    };


    $scope.clearDoneTasks = function () 
    {
        $scope.JsonData = tasksLocal = tasksLocal.filter(function (val) 
        {
            return !val.completed;
        });
    };


    $scope.markAll = function (task) 
    {
        tasksLocal.forEach(function (task) 
        {
            tasksLocal.completed = done;
        });
    };

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
            "Task_ID":task.TID
        }
        console.log("hoho123");
        // add tag into the tag table
        //the task gets update inside the RESTAPI.insert function of the tag
        $scope.insertTaskrREST("tags",tempTag);
        $scope.newTag='';
        $scope.AdddingANewTag=0;
        // ListAllTags=GetTags;
        // $scope.$apply;
    }

    $scope.removeTag=function(index,tag)
    {
        var ind=$scope.JsonData[index].tags.indexOf(tag);
        if(ind>-1)
        {
            //update the task
            $scope.updateTaskrREST("tasks",$scope.JsonData[index]);
            //update locally
            $scope.JsonData[index].tags.splice(ind,1);
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

    $scope.addPeopleToProject=function()
    {
        if(!$scope.newTeamMember)
            return; 
        else
        {
            //check if the team member is already present in ListAllMembers
            console.log("here");

            $scope.newTeamMember='';
        }
    }

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
        //check if the $scope.newFollower is a member of the project or not
        //if not a member, display an alert to add a member who is on present on Taskr, and an option to invite him to taskr.
        // return 

        // else continue with the function
        //update the task 
        $scope.updateTaskrREST("tasks",task);
        // task.fol.push($scope.newFollower);
        
        $scope.AddingANewFollower=0;
        $scope.newFollower='';
    }

    $scope.removeFollower=function(task,follower)
    {
        task.fol.splice(task.fol.indexOf(follower),1);
        //update the task
        $scope.updateTaskrREST("tasks",task);
    }

    $scope.addAssignedTo=function(index)
    {
        // console.log("Passing index");
        // console.log(index);
        if(!$scope.JsonData[index].assignedTo)
            $scope.JsonData[index].assignedTo="Shashvat";
        else 
            $scope.JsonData[index].assignedTo="changed";
        
    }

    $scope.removeAssignedTo=function(index)
    {
        $scope.JsonData[index].assignedTo=null;
    }

    $scope.newProject=function()
    {
        
        //Generate a TID which is not used by any other task
        //This immitates the server's functionality to ensure unique TIDs
        var occupiedPIDs = [];
        for(var i=0;i<$scope.JsonData.length;i++)
            occupiedPIDs.push($scope.JsonData[i].PID);

        var _PID;
        console.log(occupiedPIDs);
        
        for(var i=100;i>=0;i--)
            if(occupiedPIDs.indexOf(i)==-1)
            {
                _PID=i;
                break;
            }

        var occupiedTIDs = [];
        for(var i=0;i<$scope.JsonData.length;i++)
            occupiedTIDs.push($scope.JsonData[i].TID);

        var _TID;
        
        for(var i=100;i>=0;i--)
            if(occupiedTIDs.indexOf(i)==-1)
            {
                _TID=i;
                break;
            }
    
        console.log("New PID");            
        console.log(_PID);
        console.log("New TID");            
        console.log(_TID);
        $scope.JsonData.push(
        {
            "PID":_PID,
            "TID":_TID,
            "TN":"New Task",
            "TD":' Task Description ',
            // "TC":"Task Comments",
            "Comments":[],
            "fol":[],
            "star":0,
            "DueDate":"2013-1-24", 
            "tags":[],
            "assignedTo":"Shashvat",
            "assignedBy":"Shashvat",
            completed:false
        });
        // console.log($location.path());


        //Update Model
        PutJSONData($scope.JsonData);
        $scope.ListAllProjectIDs.push(_PID);
        console.log("ListAllPIDs");
        console.log($scope.ListAllProjectIDs);
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
            "Task_ID":task.TID,
            "UserID":1
        };

        // task.Comments.push(tempComment);
        //insert into comment table
        $scope.insertTaskrREST("comments",tempComment);
        //update the
        $scope.comment='';
    }

    $scope.starTask=function(task)
    {
        if(task.star==1)
        {
            task.star=0;
            $scope.updateTaskrREST("tasks",task);
        }
            
        else
        {
            $scope.updateTaskrREST("tasks",task);
            task.star=1;
        }
            
        // console.log(task);
    }

    $scope.clockTask=function(task)
    {
        if(task.clocked==1)
        {
            $scope.updateTaskrREST("tasks",task);
            task.clocked=0;
        }
            
        else
        {
            $scope.updateTaskrREST("tasks",task);
            task.clocked=1;
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
                url:"/task/:TID",
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