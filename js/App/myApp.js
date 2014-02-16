// Add due date functionality
// Add Star Marked/Favorited functionality
// Add Followers functionality on individual Tasks
// Add Tags on Individual Tasks
// Add Filter based on Tags

var myapp=angular.module('myapp',['ngRoute','ui.router']);



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







myapp.controller('DataCtrl',function($scope,$http,$stateParams,$location,JSONData,GetTags,GetProjectIDs,GetMembers)
{
    
    $scope.state=$stateParams;
    $scope.routeTID=$stateParams.TID;
    $scope.routePID=$stateParams.PID;
    $scope.ListAllTags=GetTags;
    $scope.ListAllProjectIDs=GetProjectIDs;
    $scope.ListAllMembers=GetMembers;
    $scope.test="Shashvat";
    console.log(JSONData);
    // $scope.sortOrder="TID";

    

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
        console.log(currProject);
        
        var arr=[];
        arr=currProject.split("/");
        currProject=arr[0];
        // delimit at '/'
        if(currProject==='/' || currProject==='')
            currProject=0;
        console.log(currProject);
        currProject=parseInt(currProject);
        return currProject;
    }


    var tasksLocal = $scope.JsonData=JSONData;
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
        console.log(occupiedTIDs);
        for(var i=100;i>=0;i--)
            if(occupiedTIDs.indexOf(i)==-1)
            {
                _TID=i;
                break;
            }
            // console.log(i);

        var currProject=$location.path();
        currProject=currProject.substring(1);
        console.log(currProject);
        
        var arr=[];
        arr=currProject.split("/");
        currProject=arr[0];
        // delimit at '/'
        if(currProject==='/' || currProject==='')
            currProject=0;
        console.log(currProject);
        currProject=parseInt(currProject);
        $scope.JsonData.push(
        {
            "PID":currProject,
            "TID":_TID,
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
        console.log(tempPath);
        $location.path(tempPath);
    };

    //Submit button is only to remove the input box and bring back the label
    // The $scope.JsonData gets updated for every keystroke in the input as the input box is ng-model="task.TN"
    $scope.doneEditing = function (task, editWhat) 
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
        task.tags.push($scope.newTag);
        $scope.newTag='';
        $scope.AdddingANewTag=0;
        // ListAllTags=GetTags;
        // $scope.$apply;
    }

    $scope.removeTag=function(index,tag)
    {
        var ind=$scope.JsonData[index].tags.indexOf(tag);
        if(ind>-1)
            $scope.JsonData[index].tags.splice(ind,1);
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
        task.fol.push($scope.newFollower);
        $scope.AddingANewFollower=0;
        $scope.newFollower='';
    }

    $scope.removeFollower=function(task,follower)
    {
        task.fol.splice(task.fol.indexOf(follower),1);
    }

    $scope.addAssignedTo=function(index)
    {
        console.log("Passing index");
        console.log(index);
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
        task.Comments.push(newComment);
        $scope.comment='';
    }

    $scope.starTask=function(task)
    {
        if(task.star==1)
            task.star=0;
        else
            task.star=1;
        // console.log(task);
    }

    $scope.clockTask=function(task)
    {
        if(task.clocked==1)
            task.clocked=0;
        else
            task.clocked=1;

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