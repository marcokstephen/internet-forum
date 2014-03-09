<?php session_start(); 
// CONFIRM-LOGIN.PHP
// PRIMARY USER: Everyone
// PURPOSE: This page processes the HTTP POST request received when a user tries to log in.
// Checks to see if their credentials match one in the database, and if so, initiates the
// session. If there is no match, returns a login error message.

// Note: It would be nice [and fairly easy!] to merge this with the login.php page by making 
// the page POST to itself. However, I am lazy, so I will leave that as an exercise for you
// if you choose to do so.

include 'header2.php';
include 'connect.php';

//Initiates a query to return the user that matches the given username/password
$sql = "SELECT user_id, user_name, user_level FROM users
	 WHERE user_name = '" . mysql_real_escape_string($_POST['username']) . "'
	 AND user_pass = '" . sha1($_POST['password']) . "'";

$result = mysql_query($sql);

if (!$result) {
	die('Error: ' . mysql_error());
}

//If there are no results that match the given username and password, the user must have given
//invalid credentials. This output lets them know that.
if (mysql_num_rows($result) == 0){
	echo '<div class="error">You have given an invalid username/password combination. <a href="login.php">Try again</a>.</div>';
} else
{
	//If the SQL query returns a result, their login must have been successful. We will now initialize the login process.
	//We store current login information in a PHP Session
	$_SESSION['signed_in'] = true; //sets the user to be signed in
	while($row = mysql_fetch_assoc($result))
	{
		$_SESSION['user_id'] = $row['user_id']; //fetches the user ID from the query results
		$_SESSION['user_name'] = $row['user_name']; //fetches the username from the query results
		$_SESSION['user_level'] = $row['user_level']; //fetches the user level from the query results.
		//The user level is particularly important, as it controls permissions for the user. ie, administrator functions are granted
		//depending on if your user_level is high enough
	}
	//Displays a login confirmation message
	echo '<div class="error">Welcome, ' . $_SESSION['user_name'] . '. Return to <a href="index.php">Board List</a>.</div>';
}

include 'footer.php';
?>
