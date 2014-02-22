<!DOCTYPE html>
<html ng-app="myapp" lang="en">
<head>
	<title>Taskr</title>
	<script src="js/App/angular.min.js"></script>
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script src="js/App/angular-ui-router.js"></script>
	<!--<script src="js/App/modules/keypress/keypress.js"></script> -->
	<script src="js/App/angular-animate.min.js"></script>
	<script type="text/javascript" src="js/App/angular-route.js"></script>
	<script type="text/javascript" src="js/App/angular-resource.min.js"></script>

	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap-datepicker.css">
	<script type="text/javascript" src="js/App/modules/bootstrap-datepicker.js"></script>
	
	
	
	<link rel="stylesheet" type="text/css" href="css/styles.css">
	<!-- <link rel="stylesheet" type="text/css" href="css/animations.css"> -->
</head>



<style>


.modal-backdrop {
  position: fixed;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  z-index: 1040;
  background-color: #7a927b;
  
}
.modal-backdrop.fade {
  opacity: 0.2;
}
	/* set the background-color to red */
.navbar-inner
{
    background-color: #259542;
    /* remove the gradient */
    background-image: none;
    /* set font color to white */
    color: white;
    border:none;
    box-shadow: none;
}   

/* menu items */

    /* set the background of the menu items to pink and default color to white */

        .navbar .nav > li > a {
          background-color: pink;   
          color: white;

        }


    /* set hover and focus to lightblue */
    .navbar .nav > li > a:focus,
    .navbar .nav > li > a:hover {
      background-color:#259542;
      color: white;
    }
        /* set active item to darkgreen */
        .navbar .nav > .active > a,
        .navbar .nav > .active > a:hover,
        .navbar .nav > .active > a:focus 
        {

          background-color: darkgreen;
          color: white;
        }

    .navbar-inner .brand
    {
    	color:white;
    	font-weight: normal;
    	font-size: 22px;
    }

    .navbar-inner
    {
    	height:40px;
    }

    

	body
	{
		margin:0px;
		overflow-y:hidden;
		overflow-x:hidden; 
	}

	.span1,.span2,.span3,.span4,.span5,.span6,.span7,.span8
	{
		margin:0px;
	}

	.row
	{
		margin:0px;
	}

</style>

<body id="BODYID" ng-controller="DataCtrl" style="overflow-y:hidden;">
	<!--
	<script>
	$(document).ready(function()
	{
		$('#BODYID').fadeIn("slow");
	});
	</script>
	-->
	<!-- UpperNavBar -->
	<div class="container-fluid century" style="width:1366px;height:40px;margin:0px;background-color:#259541;padding:0px;">
		
	
		<div class="navbar" style="margin-bottom:0px;">
		  	<div class="navbar-inner" style="padding-left:10px">
		    	<a class="nav" href="#">
		    		<img src="logo.png"  style="margin-top:8px;margin-left:3px;height:25px;">
		    	</a>

		    	<div class="nav century" style="margin-top:5px;margin-right:0px;">
		    		<form class="form-search searchquery" ng-submit="SearchAllProjects()">

  						<input id="IDsearchString" type="text" placeholder="search" placeholder-color="#ffffff" style="border:0px;font-size:16px;color:#ffffff;" class=" century input-medium text" ng-model="SearchString">
  					</form>
		    	</div>

		    	<a class="nav" href="#">
		    		<img src="search.png"  style="margin-top:5px;margin-left:0px;height:28px;padding-left:0px;">
		    	</a>
		    	<a class="nav" href="" style="color:white;">
		    		Hit enter to search all the projects!
		    	</a>
		    	<a class="nav" ng-href="#/calendar" style="color:white;">
		    		view calendar!
		    	</a>
		    	<form method="post" action="php/logout.php">
		    		<button type="submit">Logout</button>
		    	</form>

		  	</div>
	  	</div>
	</div>
	<!--  UpperNavBar Ends -->

	<!-- Rest of the content -->
	<div class="container-fluid" style="width:1366px;height:600px;background-color:#ffffff;margin:0px;padding:0px;">
		
		<!-- Left NavBar -->
		<div style="left:0px;top:30px;width:290px;float:left;height:100%;margin-left:-259px;margin-right:0px;background-color:#E3E3E3;border-right:1px solid #d1d1d1;">
			
			<!-- Project Name -->
			<div class="row century" style="background-color:#E3E3E3;margin:0px;height:40px;">
				<div style="height:40px;width:100%;">
					<div id="ProjectName" >
						<a class="link" href="">
							<p class="projectmain">Project {{GetProjectID()}}</p>
						</a>
					</div>
					
					<!-- Place for the arrow -->
					<div class="mainarrow">
						<a href="">
							<img src="images/1.png">
						</a>
					</div>
				</div>
			</div>


			<div class="row" style="background-color:#259541;height:4px;width:100%; left:0px;">
			</div>

			<!-- My Tasks + Inbox + Display Pic -->
			<div class="row century" style="width:100%;background-color:#E3E3E3;margin:0px;height:70px;padding-top:4px;">
				<div style="height:70px;width:100%;background-color:#ffffff;">
					
					<!-- My Tasks -->
					<div id="nav1" class="mi" >
						<div style="margin-top:5px;">
							<a class="link" style="color:#747474" href="#/">
								my tasks
								
							</a>
							<a class="link" href="#/">
							<img src="images/2.png" class="img_small">
							</a>
						</div>
						
					</div>

					<!-- Inbox -->
					<div id="nav1"class="mi" >
						<div style="margin-top:5px;">
							<a class="link" style="color:#747474" ng-href="#/inbox">inbox</a>
							<a class="link" href="#">
								<img src="images/4.png" class="img_small" ng-href="#/inbox">
							</a>
						</div>
					</div>

					<!-- Display Picture -->
					<div style="height:52px;width:50px;background-color:#868686;position:relative;top:9px;left:8px;margin:0px;border-radius:4px">

					</div>

				</div>
			</div>



			<div class="row" style="height:12px;width:100%;background-color:#e3e3e3;">
			</div>

			<!-- Add People to project -->
			<div class="row century" style="background-color:#EEEEEE;height:80px;width:100%;border-bottom:solid 1px #cbcbcb">
	
					<div style="width:100%">
						<div style="margin-left:28px;margin-top:6px; color:#8d8d8d;float:left;font-size:16px;margin-bottom:0px;margin-top:8px">
							<p style="margin-bottom:0px">add people to project</p>
						</div>
						<div style="height:30px;float:right; margin-top:6px;margin-right:7px">
							<a href="#">
								<img style="height:20px"src="images/3.png" > 
							</a>
						</div>
					</div>

				
					<input ng-model="newTeamMember" type="text" class="input-medium century" style="color:#989898;margin-left:24px;margin-top:9px;background-color:#ffffff;border:0px solid #ffffff;" placeholder="enter team mate's email id"  >
					
					<button ng-click="fullCallAPI()"style="margin-left:5px;margin-top:0px;border-radius:1px;font-size:12px"class="century btn btn-info">Add</button>
					<button ng-click="callRESTApi()">Test</button>
			</div>

			<div class="row" style="height:8px;width:100%;background-color:#e3e3e3;border-bottom:solid 1px #cbcbcb">
			</div>


			
			<!-- Teammates -->
			<div class="row century" style="background-color:#E3E3E3;height:96px;width:100%;overflow:auto;">

				<div ng-repeat="member in ListAllMembers" id="nav1" class="row team" style="border-bottom:solid 1px #cbcbcb">

					<div>
						<a href="#" >
							<img class="teampic"src="images/pic.png" >
						</a>
					</div>

					<div >
						<p class="teamnamemain">{{member}}</p>
					</div>

				</div>
				
			</div>

			<br>
			<br>
			
			<div class="row century" style="background-color:#EEEEEE;height:90px;width:100%;border-top:solid 1px #cbcbcb;border-bottom:solid 1px #cbcbcb;line-height:32px;font-size:14px;overflow:auto;">
				
				

				<div id="nav1" class="project" ng-repeat="project in ListAllProjectIDs" ng-hide="project==0">
					<img src="images/right.png" class="project_pic">
					<a class="link" ng-href="#/{{project}}" style="color:#989898;font-size:14px">Project {{project}}</a><br>
				</div>

			</div>
			
			<!-- add Project -->
			<div id="nav1"class="row century addproject" >
				<p style="cursor:pointer;color:#989898;font-size:16px;margin-left:40px;margin-top:4px" ng-click="newProject()">
					add project <strong>+</strong>
				</p>
			</div>

			<div style="height:50px;width:100%;background-color:#e3e3e3">

			</div>


			<!-- leftnav footer -->
			<div class="row century" style="background-color:#6abc7f;height:50px;width:100%;text-align:center;padding-top:20px; color:#259542">
				<a href="#feedback" id="nav2" class="link footer" data-toggle="modal">feedback</a> 
				|
				<a href="#about" id="nav2" class="link footer" data-toggle="modal">about us</a> 
				|
				<a href="#help" id="nav2" class="link footer" data-toggle="modal">help</a> 
				
			</div>


		</div>

		<!-- Center Pane -->
		<div class="century" style="width:586px;margin-left:290px;top:40px;height:600px;margin-right:0px;background-color:#eeeeee;border-right:1px solid #d1d1d1;">
			<!-- username -->
			<div class="row century" style="background-color:#ffffff;margin:0px;height:44px;border-bottom:1px solid #d1d1d1;padding:6px;padding-left:22px;overflow:hidden;font-size:22px;">
				
				<!-- Display Picture -->
				<div style="height:40px;width:40px;background-color:#868686;float:left; ">
				</div>
				
				<div style="color:#747474;height:44px;line-height:44px;float:left;padding-left:10px;">
					Project {{getProjectID()}}
				</div>

				<div style="color:#747474;height:44px;line-height:44px;float:left;padding-left:10px;">
					tasks
				</div>

				<div style="color:#747474;float:right;margin-right:20px;width:35px;margin-top:12px">
					<img src="images/down.png" style="cursor:pointer;" ng-click="printTasks()">
				</div>

			</div>

			<!-- sort and filter bar -->
			<div class="century container" style="width:586px;background-color:#E3E3E3;height:25px;border-bottom:1px solid #d1d1d1;">
				<div id="nav1"class="mid">
				    <select class="selectpicker span2 century sort" ng-model="sortOrder">
					    <option value="PID">By Project</option>
					    <option value="DueDate">By Due Date</option>
					    <option value="-completed">Status</option>
					    <option value="TID">Task ID</option>
				  </select>
  				</div>

				<div id="nav1"class="mid">
				    <select class="selectpicker span2 century sort"   name='Choose one of the following...'>
					    <option selected="selected" disabled="disabled">filter</option>
					    <option ng-repeat="tag in ListAllTags">{{tag}}</option>
				  </select>
  				</div>
			</div>

			<!-- New Task Row -->
			<div class="container century" style="background-color:#eeeeee;height:38px;border-bottom:1px solid #d1d1d1;color:#989898;">
				
				<form ng-submit="addTask()"; style="margin-bottom:0px">
					<p style="margin-left:30px;margin-top:9px; float:left">
						What need's to be done?
					</p>
					<input class="input-large century" id="new-todo" ng-model="newTask"  autofocus style="float:left;color:#989898;margin-left:24px;margin-top:9px;background-color:#ffffff;border:0px solid #ffffff;">
				</form>
			</div>

			<!-- Dynamically created tasks --> <!-- Pane width:586px -->
			<div ui-view="ProjectPane" class="row" style="height:408px;background-color:#f9f9f9;border-bottom:2px solid #d1d1d1;border-top:0px solid #d1d1d1;overflow:auto; font-size:15px; color:#989898;">
				<p style="margin-left:30px;margin-top:6px">
					Loading Tasks...
				</p>
				
				

			</div>
				
		</div>

		<!-- Task Pane -->
		<div ui-view="TaskPane" style="position:absolute;top:40px;left:876px;width:488px;height:600px;;margin:0px;background-color:#6abc7f;border-left:1px solid #d1d1d1;">

		</div>

	</div>
		
	<!--Modal Boxes-->

	<!--feedback-->
	<div id="feedback" class="modal century hide fade" style="display: none;">  
	    <div class="modal-header" style="background-color:#77D28E">  
	    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="float:right;color:#fff;opacity:1">&times;</button> 
	        <h1 style="font-size:30px;margin-top:0px;margin-bottom:0px;color:#fff">Feedback</h1>  
	        
	    </div>      
         

	    <div class="modal-body century" style="color:#747474">  
	        <form class="form-horizontal" style="padding-left:0px">
				<fieldset>
					<!-- Text input-->
					<div class="control-group">
					  <label class="control-label" for="textinput" style="margin-left:0px">Name</label>
					  <div class="controls">
					    <input id="textinput" name="textinput" type="text" placeholder="" class="input-xlarge century" style="color:#fff" required="">
					    
					  </div>
					</div>

					<!-- Text input-->
					<div class="control-group">
					  <label class="control-label" for="textinput">Email ID</label>
					  <div class="controls">
					    <input id="textinput" name="textinput" type="email" placeholder="" class="input-xlarge century" style="color:#fff"required="">
					    
					  </div>
					</div>

					<!-- Textarea -->
					<div class="control-group">
					  <label class="control-label" for="textarea"></label>
					  <div class="controls century">                     
					    <textarea class="century"id="textarea" name="textarea"style="color:#fff;width:270px" required=""placeholder="write in your feedback..." ></textarea>
					  </div>
					</div>
					
					<hr>	

					<button ng-click="isChecked()"style="float:right;margin-right:65px;margin-top:0px;border-radius:1px;font-size:12px;width:100px;height:30px;font-size:14px"class="century btn btn-info">Submit</button>

				</fieldset>
			</form>

	    </div>  
	</div>

	<!--about us-->
	<div id="about" class="modal century hide fade" style="display: none;">  
	    <div class="modal-header" style="background-color:#77D28E">  
	    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="float:right;color:#fff;opacity:1">&times;</button> 
	        <h1 style="font-size:30px;margin-top:0px;margin-bottom:0px;color:#fff">About Us</h1>  
	        
	    </div>      
	    <div class="modal-body century" style="color:#747474">  
	        <p>Emails, chats, code snippets, documents and notebooks and many such tools once did wonders for a team’s productivity. However, with increasing complexity and speed of modern work, managing all tools while being constantly in touch is now a huge task in its own. Communication and collaboration now takes more time than actual work. Hence, to unleash a team’s potential, we came up with Taskr!</p>
<p>
Taskr keeps teams, conversations, and tasks together. Now one can easily create, assign, follow, and comment on tasks, so you always know what's getting done and who's doing it. 
</p>
<p>
Taskr’s Inbox and notifications make it effortless to stay on top of the details that matter to you, wherever you are. Taskr lets one create anything from a simple to-do lists to complex workflows with the help of comments, history of jobs done in a project or under a person. With all of the team's ideas, tasks, and files in one place, Taskr will make it easy and fun to organize and divide work among teammates. One can assign each task to a single owner, then add followers to keep the right teammates up to speed. 
</p>
<p>
One can also set priorities and due dates to communicate what's important, what's next, and what's falling behind. This means that all the information that the user needs is available to him/her on a single HTML page. Taskr’s User Interface is specially designed keeping in mind the needs of team-leaders and other collaborators. 
</p>  
	    </div>  
	</div>

	<!--help-->
	<div id="help" class="modal century hide fade" style="display: none;">  
	    <div class="modal-header" style="background-color:#77D28E">  
	    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="float:right;color:#fff;opacity:1">&times;</button> 
	        <h1 style="font-size:30px;margin-top:0px;margin-bottom:0px;color:#fff">Help</h1>  
	        
	    </div>      
	    <div class="modal-body century" style="color:#747474">  
	        <iframe width="500" height="282" style="margin-left:15px;border:3px"src="//www.youtube.com/embed/t22j9_S-OAc" frameborder="1" theme="light" enablejsapi="1" fs=0></iframe> 
	    </div>  
	</div>

	
	
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/App/myApp.js"></script>
	<script type="text/javascript" src="js/App/InboxCtrl.js"></script>
	<script type="text/javascript" src="js/App/services/eventData.js"></script>

</body>
</html>
