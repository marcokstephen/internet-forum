<?php
// ME.PHP
// PRIMARY USER: Everyone
// PURPOSE: This page displays information unique to the currently logged-in
// user. This page is accessed by clicking your username in the navbar that can
// be found in header.php. This page displays information in the form of a table.

session_start();
include 'header.php';
include 'connect.php';

//ensures that a user is currently logged in. If no user is logged in, there is no
//data to display.
if ($_SESSION['signed_in'])
{
	//uses the PHP session to create an SQL query to display more information about the specific user
	$userQuery = "SELECT user_email,user_date FROM users WHERE user_id = " . $_SESSION['user_id'];
	$userResult = mysql_query($userQuery);
	$userRow = mysql_fetch_assoc($userResult);

	//uses the user's PHP session (specifically their current user level) to create an SQL query
	//which will return more information about the specific user level, including the level name and
	//level description
	$levelQuery = "SELECT level, title, description FROM levels WHERE level = " . $_SESSION['user_level'];
	$levelResult = mysql_query($levelQuery);
	$levelRow = mysql_fetch_assoc($levelResult);

	$levelTitle = $levelRow['title'];
	$levelDesc = $levelRow['description'];

	//if the user is a level that is not defined in the database, the page returns the default information
	//that the level is undefined, and the description is undefined
	if ($levelTitle == "")
	{
		$levelTitle = "Undefined";
		$levelDesc = "This user level is undefined";
	}
	//the user information on this page is displayed in the form of a table
	echo '<table class="me-table">
		<tr>
			<td class="me-table-info">User Name</td>
			<td width=80% class="me-table-data">' . $_SESSION['user_name'] . '</td>
		</tr>
		<tr>
			<td class="me-table-info">User ID</td>
			<td class="me-table-data">' . $_SESSION['user_id'] . '</td>
		</tr>
		<tr>
			<td class="me-table-info">User Level</td>
			<td class="me-table-data">' . $_SESSION['user_level'] . ': ' . $levelTitle . '<br /><span style="font-weight:normal;">' . $levelDesc . '</span></td>
		<tr>
			<td class="me-table-info">Account Created</td>
			<td class="me-table-data">' . $userRow['user_date'] . '</td>
		</tr>
		<tr>
			<td class="me-table-info">Private E-Mail</td>
			<td class="me-table-data">' , $userRow['user_email'] . '</td>
		</tr>
		</table>';
} else 
{	//if the user is not logged in, there is no info to display, and this error message is returned.
	echo '<div class="error">You must be <a href="login.php">logged in</a> to do that.</div>';
}

include 'footer.php';
?>
