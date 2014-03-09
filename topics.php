<?php
// TOPICS.PHP
// PRIMARY USER: Everyone
// PURPOSE: Displays the list of topics found within a board. The topics are arranged
// according to their last post date. That means that most recently active topics
// appear at the top of the topic list. 

include 'header.php';
include 'connect.php';

//uses HTTP GET to obtain the board ID that we will be returning topics for
$bid = mysql_real_escape_string($_GET['bid']);

//This SQL query returns the minimum levels required to view the board, create topics
//on the board, and the board name
$levelQuery = "SELECT board_view,board_create,board_name FROM boards WHERE board_id = $bid";
$levelResult = mysql_query($levelQuery);
$levelRow = mysql_fetch_assoc($levelResult);
$bName = $levelRow['board_name'];

//if there is not a board in the database with an ID that matches the requested board ID, then the
//requested board must not be valid. Therefore, we return a 404 error.
if (mysql_num_rows($levelResult) == 0)
{
   echo '<div class="error"><B>404:</b> Board not found. <a href="index.php">Return to home</a>.</div>';
} else 
{
	//if the user is a sufficient level to create topics on the board, a link to "create new topic" is listed below the navbar
	//NOTE: If the user is not a sufficient level to create topics, the link will not appear, but this does not stop the user from
	//manually typing out the link and entering it. For this reason, we once again check the user level requirement on the
	//create-topic.php page, to ensure that the user cannot bypass the system with only simple URL manipulation
	if($_SESSION['user_level'] >= $levelRow['board_create'])
	{
		echo '<div id="board_list_title"><span style="font-size:16pt;">' . $bName . '</span><br /><a href="newtopic.php?bid=' . $bid . '">Create New Topic</a></div>';
	} else 
	{
		echo '<div id="board_list_title"><span style="font-size:16pt;">' . $bName . '</span></div>'; //if the user cannot create topics,
			//the only thing that appears below the navbar is the name of the board
	}
   
	if (($levelRow['board_view'] > 2) && !($_SESSION['signed_in']))
	{
		//if the minimum view level is greater than 2, the user must be signed in (and have an access level > 2) to view the page
		echo '<div class="error"><B>Access restricted!</B> You must <a href="login.php">login</a> to view this page.</div>';
	} else if ($levelRow['board_view'] <= 2)
	{
		//if the minimum view level is 2 or less, it can be viewed by any users, as well as any unregistered guests browsing the forum
        echo '<div id="board_list_title" class="dev"><table class="board_list_table"><tr>
	         <td width="25%" style="text-align:left;">Topic Title</td>
	         <td width="25%" style="text-align:left;">Topic Creator</td>
	         <td width="25%">Posts</td>
	         <td width="25%">Last Post</td>
	         </tr></table></div>';

			 //this SQL query returns the list of topics, ordered by the date of their last post
        $sql = "SELECT topic_id, topic_subject, topic_date, topic_board, topic_by, last_post, user_name
	         FROM topics LEFT JOIN users ON topics.topic_by = users.user_id WHERE topic_board = '" . mysql_real_escape_string($_GET['bid']) . "' ORDER BY last_post DESC";
        $result = mysql_query($sql);

        if(!$result)
		{
	        die('There is an error, try again later.');
        } else 
		{ //if the query is successful, we begin to list the topics
	        while($row = mysql_fetch_assoc($result))
	        {
				//this SQL query lists the number of posts in each topic
				$numberPosts = "SELECT COUNT(post_id) FROM posts WHERE post_topic = " . $row['topic_id'];
				$numPosts = mysql_query($numberPosts);
				$postCount = mysql_fetch_assoc($numPosts);
				
				//We create an overall layout in HTML for a single topic listing, and populate the layout with information returned
				//from the first row of the SQL query. For each row returned from the SQL query, we duplicate the HTML layout and
				//inject the next row's information into it. In this situation, we are displaying the information
				//in the form of a table.
				
				echo '<div class="board_section dev">
				<table style="width:100%;font-family:arial;"><tr>
		         <td width="25%">
			         <div class="board_title"><a href="posts.php?bid=' . $bid . '&tid=' . $row['topic_id'] . '">' . strip_tags($row['topic_subject']) . '</a></div>
		         </td>
		         <td width="25%">' . strip_tags($row['user_name']) . '</td>
		         <td width="25%" style="text-align:center;">' . $postCount['COUNT(post_id)'] . '</td>
		         <td width="25%" style="text-align:center;">' . $row['last_post'] . '</td></tr></table></div>';
	        }
        }
	} else 
	{ // if the minimum view level is GREATER THAN 2, we must perform additional checks to ensure
			// that a user has the permissions to view the list of topics
		if (($levelRow['board_view'] > 2) && ($levelRow['board_view'] <= $_SESSION['user_level']))
		{
			// if the permission checks are passed, we display the topics in the same method
			// as above (where the permissions required were less than level 2)
			echo '<div id="board_list_title" class="dev"><table class="board_list_table"><tr>
				<td width="25%" style="text-align:left;">Topic Title</td>
				<td width="25%" style="text-align:left;">Topic Creator</td>
				<td width="25%">Posts</td>
				<td width="25%">Last Post</td>
				</tr></table></div>';

			$sql = "SELECT topic_id, topic_subject, topic_date, topic_board, topic_by, last_post, user_name
				FROM topics LEFT JOIN users ON topics.topic_by = users.user_id WHERE topic_board = '" . mysql_real_escape_string($_GET['bid']) . "' ORDER BY last_post DESC";
			$result = mysql_query($sql);

			if(!$result)
			{
				die('There is an error, try again later.');
			} else 
			{
				while($row = mysql_fetch_assoc($result))
				{
					$numberPosts = "SELECT COUNT(post_id) FROM posts WHERE post_topic = " . $row['topic_id'];
					$numPosts = mysql_query($numberPosts);
					$postCount = mysql_fetch_assoc($numPosts);
	
					echo '<div class="board_section dev">
						<table style="width:100%;font-family:arial;"><tr>
							<td width="25%">
								<div class="board_title"><a href="posts.php?bid=' . $bid . '&tid=' . $row['topic_id'] . '">' . strip_tags($row['topic_subject']) . '</a></div>
							</td>
							<td width="25%">' . $row['user_name'] . '</td>
							<td width="25%" style="text-align:center;">' . $postCount['COUNT(post_id)'] . '</td>
							<td width="25%" style="text-align:center;">' . $row['last_post'] . '</td></tr></table></div>';
				}
			}
		} else
		{ //if permissions are not met, an access denied error is returned
			echo '<div class="error"><B>Access denied!</B> You do not have permission to view this board. <a href="index.php">Return to Home</a></div>';
		}
	}
}
include 'footer.php';
?>
