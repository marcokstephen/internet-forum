<?php
// NEWTOPIC.PHP
// PRIMARY USER: Everyone
// PURPOSE: This page displays a form from which a user can create a new topic on a specified
// board (assuming their user level is high enough). The form POSTS to the same page.
// If the page detects an HTTP POST request, the form will not be displayed, and instead
// the form data will be processed, and a confirmation will be displayed.

include 'connect.php';
include 'header.php';

//gets the ID of the board that the topic is going to be created on, using HTTP GET
$bid = mysql_real_escape_string($_GET['bid']);

//ensures that the user is logged in (you must be logged in to post!)
if($_SESSION['signed_in'])
{
	//this query fetches the board that the topic will be created on, and detects if the board exists
	//if the board does not exist, a 404 error is returned
	$levelQuery = "SELECT board_create FROM boards WHERE board_id = $bid";
	$levelResult = mysql_query($levelQuery);
	$levelRow = mysql_fetch_assoc($levelResult);


	if (mysql_num_rows($levelResult) == 0)
	{
		//if no results are returned when a specific board_id is searched, then the board must not be a real
		//board, and the following 404 error will be returned
		echo '<div class="error"><B>404:</b> Board not found. <a href="index.php">Return to home</a>.</div>';
	} else //else, the board must exist, so we will begin the topic creation process!
	{
		if ($levelRow['board_create'] <= $_SESSION['user_level'])
		{
			//this checks to ensure that the user is a high enough level to create a topic on the board.

			if($_SERVER['REQUEST_METHOD'] != 'POST')
			//this checks to see if there is a current HTTP POST request. If so, the form is processed. If not, a new form is displayed
			{
			    echo '<form method="post" action="">
				      Topic Title: <input type="text" maxlength=80 name="topic-title" /><br />
				      Topic Message: <br /><textarea name="topic-message" maxlength=4000 style="width:80%;height:150px;" /></textarea><br />
				      <input type="submit" value="Create Topic" />
				      </form>';
			}

			else //There is a current POST request to be processed.
			{
				//Note the use of the strip_tags() function. This PHP function discards
				//any HTML tags that are input by the user in their posts, which can often
				//create glitches and exploits.
			    $value1 = strip_tags(mysql_real_escape_string($_POST['topic-title']));
			    $value2 = $bid;
			    $value3 = $_SESSION['user_id'];
				  
				//this sql query creates a new topic, with the subject being specified by the user
			    $sql = "INSERT INTO topics (topic_subject, topic_date, topic_board, topic_by) 
						VALUES ('$value1', NOW(), '$value2', '$value3')";

			    $result = mysql_query($sql);
			    if(!$result)
			    {
					die('Error: ' . mysql_error());
			    } else 
				{	//if there is no error creating the topic, we will create the first post in the topic
					//this function returns the ID of the topic that we just created, so we can use the topic ID
					//when we are adding the first post to the database
					$tid = mysql_insert_id();
					$messageText = strip_tags(mysql_real_escape_string($_POST['topic-message']), '<p><br>');
				  
					//inserts the new post into the database, under the topic ID of the topic that we just created
					$addMessage = "INSERT INTO posts (post_content, post_date, post_topic, post_by, post_board)
									VALUES ('$messageText', NOW(), '$tid', '$value3', '$bid')";
				  
					//this query updates the "Last Post Time" of the topic list, based on what the current time of posting is
					$updateTopicTime = "UPDATE topics SET last_post = NOW() WHERE topic_id = $tid";

					$result2 = mysql_query($addMessage);
					$result3 = mysql_query($updateTopicTime);
			
					if(!$result2)
					{
						die('Error: ' . mysql_error());
			        } else 
					{	//if there are no errors with posting, this confirmation message is returned
						//a link back to the current topic is constructed based on the topic id
            			echo '<div class="error">Topic created. <a href="posts.php?bid=' . $bid . '&tid=' . $tid . '">Return Here</a></div>';
            		}
			    }
			}
	    } else 
		{	//if the user is not a high enough level to create topics, this message is returned.
			echo '<div class="error"><B>Access Restricted</B>: You do not have permission to create topics on this board. <a href="index.php">Return to Home</a></div>';
	    }
	}
}
else
{ //if the user is not logged in, they cannot create any topics.
	echo '<div class="error">You must be <a href="login.php">logged in</a> to do that.</div>';
}

include 'footer.php';
?>
