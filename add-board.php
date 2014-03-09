<?php
// ADD-BOARD.PHP
// PRIMARY USER: Administrators
// PURPOSE: This page is for creation of boards, including board creation, board property editing, and board deletion
// The page checks to see if a current HTTP POST request has been made. If so, it processes the request. If not, it loads
// the forms from which a POST request can be made.

session_start();
include 'header.php';
include 'connect.php';

if ($_SESSION['signed_in']){ //This page is only accessible if you are in an administrator account!
	if ($_SESSION['user_level'] > 3){ //The user must be an Administrator
			if($_SERVER['REQUEST_METHOD'] != 'POST')
			{
			//This form is for creating a new board. It POSTS to this same page (add-board.php).
			echo '<form method="post" action="">
				Board name: <input type="text" name="create-board-name" /><br />
				Board minimum view level (numbers only): <input type="text" name="min-view-level" /><br />
				Board minimum create-topic level (numbers only): <input type="text" name="min-create-level" /><br />
				Board description: <br /><textarea name="create-board-desc" style="width:80%;height:50px;"></textarea><br />
				NOTE: IF THE BOARD DOES NOT REQUIRE A LEVEL > 2 TO VIEW, IT WILL BE VIEWABLE TO ALL GUESTS AS WELL!<br />
				However, users must always be logged in to create topics/post.<br />
				<input type="submit" value="Add Board" />
				</form>';

			echo '<br /><hr><br />';
			//This form is for deleting a board. It POSTS to a different page (delete-board.php)
			echo '<form method="post" action="delete-board.php">
				Remove Board (type name exactly): <input type="text" name="remove-board" /><br />
				<input type="submit" value="Remove Board" />
				</form>';
			echo '<br /><hr><br />';
			
			//This form is for editing a board. It POSTS to a different page (edit-board.php)
			//Note that this form is currently quite sloppy, it updates every field in the specified database entry
			//even if nothing is entered into the respective form field! As such, whenever you update one
			//field of a board, you must copy and paste all other information to avoid rewriting the database
			//entry with blank data.
			
			echo '<form method="post" action="edit-board.php">
				<B>Ensure that all fields are filled, even if you are only updating one thing!</B><br />
				Board Name: <input type="text" name="bName" /><br />
				New Board Name: <input type="text" name="newbName" /><br />
				New board minimum view level: <input type="text" name = "newMinViewLevel" /><br />
				New board minimum create-topic level: <input type="text" name="newMinCreateLevel" /><br />
				New board description:<br />
				<textarea name="newBoardDesc" style="width:80%;height:50px;"></textarea><br/>
				<input type="submit" value="Edit Board" />';
			}
			else //If the page detects that a POST request has been recieved, it processes the request instead of displaying the forms
			{ //Note that this page only processes creating a new board -- the other forms on the page are processed in different files
				$sql = "INSERT INTO boards(board_name, board_description, board_view, board_create) VALUES
 				('" . mysql_real_escape_string($_POST['create-board-name']) . "', 
				'" . mysql_real_escape_string($_POST['create-board-desc']) . "',
				'" . mysql_real_escape_string($_POST['min-view-level']) . "',
				'" . mysql_real_escape_string($_POST['min-create-level']) . "')";
				//Creates a new board and places it into the database
				$result = mysql_query($sql);
				if(!$result)
				{
				die('Error: ' . mysql_error());
				} else {
				echo '<div class="error">New board "' . mysql_real_escape_string($_POST['create-board-name']) . '" has been created. <a href="index.php">Return to home</a>.</div>';
				}

			}
	} else { //if the user is not detected to be an administrator, returns this error
		echo '<div class="error"><B>Access denied!</b> You are not a high enough user level to do that. <a href="index.php">Return to home</a>.</div>';
		}
} else { //if the user is not logged in, prompts them to log in
	echo 'You must be <a href="login.php">logged in</a> to do that.';
}

include 'footer.php';
?>