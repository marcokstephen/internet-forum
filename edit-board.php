<?php
// EDIT-BOARD.PHP
// PRIMARY USER: Administrator
// PURPOSE: This page processes an HTTP POST request, intially sent by add-board.php
// This page updates the entry of a specified board in the database.

session_start();
include 'header.php';
include 'connect.php';

//Ensures that the user is logged in and is an administrator
if ($_SESSION['signed_in']){
	if ($_SESSION['user_level'] > 3){ //assumes that an administrator is level 4 or higher
		//sql query to update the board information with data specified in the form
		//that can be found at add-board.php
		$sql = "UPDATE boards SET board_name = '" . mysql_real_escape_string($_POST['newbName']) . "', 
				board_description = '" . mysql_real_escape_string($_POST['newBoardDesc']) . "',
				board_create = '" . mysql_real_escape_string($_POST['newMinCreateLevel']) . "',
				board_view = '" . mysql_real_escape_string($_POST['newMinViewLevel']) . "' 
				WHERE board_name = '" . mysql_real_escape_string($_POST['bName']) . "'";

		$result = mysql_query($sql);
		if(!$result)
		{
			die('Error: ' . mysql_error());
		} else 
		{ //if no errors, returns the confirmation message that the board has been updated
			echo 'Board has been updated.';
		}
			
	} else 
	{ //if the user is not an admin, returns this error
		echo 'You are not a high enough user level to do that.';
	}
} else 
{ //if the user is not logged in, returns this error
	echo 'You must be <a href="login.php">logged in</a> to do that.';
}

include 'footer.php';
?>