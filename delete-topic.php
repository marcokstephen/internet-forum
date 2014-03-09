<?php
// DELETE-TOPIC.PHP
// PRIMARY USER: Administrator/Moderator
// PURPOSE: This page uses HTTP GET to identify a specific topic, and removes
// it from the database (including all posts in the topic).

session_start();
include 'header.php';
include 'connect.php';

//fetches the topic ID that is desired to be deleted, from the URL of the page (HTTP GET)
$tid = mysql_real_escape_string($_GET['tid']);

//ensures that the user is logged in, and is a moderator
if ($_SESSION['signed_in']){
	if ($_SESSION['user_level'] > 2){ //assumes the moderator level is > 2
		//query to remove the entire topic from the database
		$sql = "DELETE FROM topics WHERE topic_id = " . $tid;

		$result = mysql_query($sql);
		if(!$result)
		{
			die('Error: ' . mysql_error());
		} else 
		{ //if no errors occur, the topic is succesfully removed from the database and this confirmation is shown
			echo '<div class="error">Topic has been deleted. <a href="index.php">Return to Index</a>.</div>';
		}
			
	} else 
	{ //if the user is not a moderator, returns this error
		echo '<div class="error">You are not a high enough user level to do that.</div>';
	}
} else 
{ //if the user is not logged in, returns this error
	echo '<div class="error">You must be <a href="login.php">logged in</a> to do that.</div>';
}

include 'footer.php';
?>