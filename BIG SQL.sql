-- User_ID=User_ID
-- Get the Tasks from User_Info
-- loop over $id(s) in User_Info.Task_IDs
-- get Task_ID=$id
$task['$id'] = SELECT Task_ID, Task_Name, Task_Description, 				Task_Followers, Task_Tags
				FROM Task_Info
				WHERE Task_ID=$id;

(CSV)$tags=$task['$id']->Task_Tags
-- make_array($tags)
-- Gets all Tags IDs
$tags=$task['tags'];
$tagArray=make_array(tags)
-- Get all Tags from Tag_Info where Tag_ID in the tagArray
-- loop i in $tagArray
$tagArray = SELECT Tag_ID, Tag_Name
			FROM Tag_Info
			WHERE Tag_ID=$tagArray[i]

-- Get Comments from Comment_Info where Task_ID=$id=Comment_Task_ID
$comments = SELECT Comment_ID, Comment_Body, 							Comment_CreateDateTime
				WHERE Co
				mment_Task_ID=Task_ID
-- make JSON of Comments