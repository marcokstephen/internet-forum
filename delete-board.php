<?php
// DELETE-BOARD.PHP
// PRIMARY USER: Administrator
// PURPOSE: This page processes an HTTP POST request, sent by add-board.php.
// It removes a board, specified by the board name.

session_start();
include 'header.php';
include 'connect.php';

//ensures that the user is logged in, and is an administrator
if ($_SESSION['signed_in'])
{
	if ($_SESSION['user_level'] > 3)
	{
		//query to delete the board from the database
		$sql2 = "DELETE FROM boards WHERE board_name = '" . mysql_real_escape_string($_POST['remove-board']) . "'";
		$result2 = mysql_query($sql2);
		if(!$result2)
		{//if there is a database error, returns the error instead of the confirmation message
			die('Error: ' . mysql_error());
		} else
		{//if the board is successfully deleted, returns this confirmation message
			echo '<div class="error">Board "' . mysql_real_escape_string($_POST['remove-board']) . '" removed. <a href="index.php">Return to home</a>.</div>';
		}
	} else 
	{ //if user is not an admin, returns this error
		echo 'You are not a high enough user level to do that.';
	}
} else 
{ //if user is not logged in, returns this error
	echo 'You must be <a href="login.php">logged in</a> to do that.';
}

include 'footer.php';
?>