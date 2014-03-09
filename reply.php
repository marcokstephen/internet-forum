<?php
// REPLY.PHP
// PRIMARY USER: Everyone
// PURPOSE: This page checks to see if there is a current HTTP POST request.
// Depending on this, it will do one of two things:
// If there IS an HTTP POST request, it will process the request and add a post to the topic
// If there isn't a request, it will display a form where a user can submit a new post to the topic

include 'connect.php';
include 'header.php';

//using HTTP GET, gets the board ID and topic ID of the topic that the reply will be added to
$bid = mysql_real_escape_string($_GET['bid']);
$tid = mysql_real_escape_string($_GET['tid']);

//this SQL query returns the topic corresponding to the specified topic ID and board ID
$checkValidQuery = "SELECT topic_id FROM topics WHERE topic_id = $tid AND topic_board = $bid";
$checkValidResult = mysql_query($checkValidQuery);

//if there are no results for the query, the topic must not exist, and a 404 error is returned
if (mysql_num_rows($checkValidResult) == 0)
{
   echo '<div class="error"><B>404</B>: Topic not found. <a href="index.php">Return to home</a>.</div>';
} else 
{ //otherwise, the topic indeed exists, and the posting process continues

	//ensures that the user is logged in. Users must be logged in to post.
   if($_SESSION['signed_in'])
	{
   
		//this query obtains the minimum level required to view the board. It is assumed that if the
		//user does not have permission to view the board, they should not have the ability to post to the
		//board either. Therefore, if they do not meet the minimum viewing requirements of the board, we will
		//not let them post to the topic.
		$levelQuery = "SELECT board_view FROM boards WHERE board_id = $bid";
		$levelResult = mysql_query($levelQuery);
		$levelRow = mysql_fetch_assoc($levelResult);
		if (mysql_num_rows($levelResult) == 0)
		{
			//ensures that the board is a valid board
			echo '<div class="error"><B>404:</b> Board not found. <a href="index.php">Return to home</a>.</div>';
		} else if ($_SESSION['user_level'] < $levelRow['board_view'])
		{
			//ensures that the user meets the minimum posting level restriction
			echo '<div class="error"><B>Access denied!</B> You do not have permission to post to this board. 
				<a href="index.php">Return to home</a>.</div>';
		} else 
		{
	  
			//if the board is valid and permissions are met, we proceed with the posting process
			if($_SERVER['REQUEST_METHOD'] != 'POST')
			//detects if there is an existing HTTP POST request to be processed by the server
			//if there is not an existing request, we display a form prompting the user to create their post
			{
				echo '<form method="post" action="">
				      Message: <br />
				      <textarea style="width:80%;height:150px;" maxlength=4000 name="reply-txt"></textarea><br />
				      <input type="submit" value="Add Reply" />
				      </form>';
			}
			else
				//if there *IS* an existing POST request, we do not show the user the form. Instead,
				//we process the data and add it to the database via an SQL query
			{
				//note the use of the strip_tags() function. This function strips HTML tags that the
				//user may have entered, as these can cause glitches when displayed in the board. However,
				//we allow the <br> and <p> tags as these are harmless, yet very important to formatting the
				//users' posts.
				//example: If a user posts "<U>No end html tag!!!", all content AFTER the post will continue
				//to be underlined.
				  
			    $value1 = strip_tags(mysql_real_escape_string($_POST['reply-txt']), '<br><p>');
			    $strippedValue1 = strip_tags($value1);
			    $value2 = mysql_real_escape_string($_GET['tid']);
			    $value3 = $_SESSION['user_id'];
					
				//this SQL query inserts the post into the database, with all requried data
			    $sql = "INSERT INTO posts (post_content, post_date, post_topic, post_by, post_board) 
						VALUES ('$value1', NOW(), '$value2', '$value3', '$bid')";
				  
				//this SQL query updates the time of the last post for the topic, which is seen
				//when the topics are listed in topics.php
			    $updateBoardTime = "UPDATE topics SET last_post = NOW() WHERE topic_id = $value2";
					
				//executing the queries...
				$result = mysql_query($sql);
			    $result2 = mysql_query($updateBoardTime);
			    if(!$result)
			    {
					die('Error: ' . mysql_error());
			    } else 
				{ //if the query was successful, we return this confirmation message!
					echo '<div class="error">Message added. <a href="posts.php?bid=' . mysql_real_escape_string($_GET['bid']) . '&tid=' . mysql_real_escape_string($_GET['tid']) . '">Return Here</a></div>';
			    }
			}
		}
	}
   else
   { //if the user is not logged in, an error is returned.
		echo 'You must be <a href="login.php">logged in</a> to do that.';
   }
}

include 'footer.php';
?>
