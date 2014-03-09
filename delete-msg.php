<?php
// DELETE-MSG.PHP
// PRIMARY USER: Administrator/Moderator
// PURPOSE: This page uses HTTP GET to identify a specific post, and overwrites
// it in the database, effectively "deleting" it.

session_start();
include 'header.php';
include 'connect.php';

//ensures that the user is a moderator
if ($_SESSION['signed_in'])
{
	if ($_SESSION['user_level'] > 2)
	{
		//query to overwrite the message with a placeholder, indicating the posts' deletion
		//The deletion message can be edited here:
		$sql = "UPDATE posts SET post_content = '[This message has been deleted at the request of a moderator or administrator]'
 		WHERE post_id = '" . mysql_real_escape_string($_GET['pid']) . "'";

		$result = mysql_query($sql);
		if(!$result)
		{
			die('Error: ' . mysql_error());
		} else 
		{ //displays a confirmation if the message is succesfully deleted
			echo '<div class="error">Message has been deleted. <a href="index.php">Return to Index</a>.</div>';
		}
			
	} else 
	{ //if they are not a moderator, they cannot delete posts
		echo '<div class="error">You are not a high enough user level to do that.</div>';
	}
} else
{ //if they are not a moderator, they cannot delete posts
	echo '<div class="error">You must be <a href="login.php">logged in</a> to do that.</div>';
}

include 'footer.php';
?>