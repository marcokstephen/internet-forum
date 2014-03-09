<?php
// POSTS.PHP
// PRIMARY USER: Everyone
// PURPOSE: Displays the list of posts within a topic. Checks user permissions
// to ensure that a user has permission to view the topic, and if not, displays
// an access denied error.

include 'header.php';
include 'connect.php';

//Uses HTTP GET to obtain both the board ID and topic ID that will be searched in the database,
//to return any posts in the desired topic
$bid = mysql_real_escape_string($_GET['bid']);
$tid = mysql_real_escape_string($_GET['tid']);

//This query obtains the minimum level required to view the board, so that the page can later determine
//whether or not the user has the required user level to view the topics/posts
$levelQuery = "SELECT board_view FROM boards WHERE board_id = $bid";
$levelResult = mysql_query($levelQuery);
$levelRow = mysql_fetch_assoc($levelResult);

//obtains the name of the topic, to be placed near the top of the page (under the navbar, but above the posts)
$topicNameQuery = "SELECT topic_subject FROM topics WHERE topic_id = $tid";
$topicNameResult = mysql_query($topicNameQuery);
$topicNameRow = mysql_fetch_assoc($topicNameResult);

//if the minimum level required to view the board is greater than 2 and the user is not the required level, an
//access denied error is returned.
if (($levelRow['board_view'] > 2) && ($levelRow['board_view'] > $_SESSION['user_level']))
{
   echo '<div class="error"><B>Access denied!</B> You do not have access to view this topic. <a href="index.php">Return to home</a>.</div>';
} else 
{ 	//if the user DOES meet the required permissions OR the required permissions are not greater than level 2,
	//the following content is displayed

	if($_SESSION['signed_in']) //only displays add new reply if signed in
	{
		echo '<div id="board_list_title"><span style="font-size:16pt;">' . strip_tags($topicNameRow['topic_subject']) . '</span><br /><a href="topics.php?bid=' . mysql_real_escape_string($_GET['bid']) . '">Topic List</a>
				| <a href="reply.php?bid=' . mysql_real_escape_string($_GET['bid']) . '&tid=' . mysql_real_escape_string($_GET['tid']) . '">Add New Reply</a></div>';
	} else 
	{	//if the user is not signed in, they do not receive a prompt to add a reply. instead, they only
		//receive a prompt to return to the topic list
		echo '<div id="board_list_title"><span style="font-size:16pt;">' . strip_tags($topicNameRow['topic_subject']) . '</span><br /><a href="topics.php?bid=' . mysql_real_escape_string($_GET['bid']) . '">Topic List</a></div>';
	}
	
	//this query obtains all the posts in the topic
	$sql = "SELECT post_id, post_content, post_date, post_topic, post_by, user_name
			FROM posts LEFT JOIN users ON posts.post_by = users.user_id WHERE post_board = '" . mysql_real_escape_string($_GET['bid']) . "' AND post_topic = '" . mysql_real_escape_string($_GET['tid']) . "'";
	$result = mysql_query($sql);

	if(!$result)
	{ //if the query fails, this error message is generated
		die('There is an error, try again later.');
	} else if (mysql_num_rows($result) == 0)
	{ 
		//if the query is successful, but returns zero posts, the topic must not actually exist!
		//therefore, a 404 error is returned
		echo '<div class="error"><B>404</B>: Topic not found. <a href="index.php">Return to home</a>.</div>';
	} else 
	{ //otherwise, the topic is real so posts are displayed
		while($row = mysql_fetch_assoc($result))
	   
	   	//We create an overall layout for a post, and populate the layout with information returned
		//from the first row of the SQL query (the first post returned). Then, for each remaining post,
		//we duplicate the layout and inject the next posts' information into it.
		{
			//this is the post header, displaying information such as the user who posted it and the time of posting
			echo '<div class="message-body">
					<div class="post-info dev"><B>From:</B> <a href="user.php?bid=' . $bid . '&tid=' . $tid . '&uid=' . $row['post_by'] . '">' . strip_tags($row['user_name']) . '</a> <B>|</B> ' . $row['post_date'];

			if ($_SESSION['user_level'] >= 3)
			{ 	//this assumes that a moderator has a level of 3 or greater
				//displays moderation options for each post, assuming that the user is a moderator or administrator
				echo ' | <a href="delete-msg.php?pid=' . $row['post_id'] . '">Delete Message</a>';
				echo ' | <a href="delete-topic.php?tid=' . $row['post_topic'] . '">Delete Topic</a>';
			}
   
			//this is the post content, displaying the actual message that the user posted
			echo '</div>
					<div class="post-content dev">' . strip_tags($row['post_content'], '<p><br>') . '</div>
				</div>';
		}
	}
}
include 'footer.php';
?>