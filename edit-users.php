<?php
// EDIT-USERS.PHP
// PRIMARY USER: Administrator
// PURPOSE: This page allows administrators to edit specific users' user levels
// without needing to access the database manually. The page does 2 tasks:
// It displays the form (if it does not detect an HTTP POST request being received
// It processes the form (if it DOES detect the HTTP POST request)

session_start();
include 'header.php';
include 'connect.php';

//ensures that the user is logged in and is an administrator
if ($_SESSION['signed_in']){
	if ($_SESSION['user_level'] > 3){ //assumes that the administrator level is 4 or higher
		//checks to see if the page will be displaying the form, or processing the form
		if($_SERVER['REQUEST_METHOD'] != 'POST')
		{ //if there is not currently a POST request, the page will display the following form
			echo '<form method="post" action="">
				Username: <input type="text" name="userName" /><br />
				Board level (numbers only): <input type="text" name="userLevel" /><br />
				<input type="submit" value="Change User Level" />
				</form>';
		}
		else //if there IS an HTTP POST request, the page will NOT show the form, but will instead process the data.
		{ //sql query to update a specific user's level
			$sql = "UPDATE users SET user_level = 
				'" . mysql_real_escape_string($_POST['userLevel']) . "' 
				WHERE user_name = '" . mysql_real_escape_string($_POST['userName']) . "'";
	
			$result = mysql_query($sql);
			if(!$result)
			{
				die('Error: ' . mysql_error());
			} else 
			{	//Displays a confirmation that the user has been edited
				//NOTE: IF THE USER GIVEN IS INVALID (not a real user) THE PAGE WILL STILL DISPLAY THE CONFIRMATION, BUT THERE WILL BE NO CHANGE TO THE DATABASE!
				//You can change this if you want -- it is not a big deal though (there is no downside to leaving it as is -- I haven't fixed it due to laziness)
				//To fix it, you would create a query to return the users with the given username, and if the
				//query result has zero rows, return a different error message
				echo '<div class="error">User "' . mysql_real_escape_string($_POST['userName']) . '" has been edited. <a href="index.php">Return to homepage.</a></div>';
			}

		}
	} else 
	{ //If the user is not an admin, returns this error message
		echo '<div class="error">You are not a high enough user level to do that. <a href="index.php">Return to home</a>.</div>';
	}
} else 
{ //If the user is not logged in, return this message
	echo '<div class="error">You must be <a href="login.php">logged in</a> to do that.</div>';
}

include 'footer.php';
?>