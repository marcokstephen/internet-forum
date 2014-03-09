<?php session_start(); 
// LOGIN.PHP
// PRIMARY USER: Everyone
// PURPOSE: This page displays a form for users to login. The form POSTS to
// login-confirmation.php
// If the user is already detected to be logged in, returns an error message
// asking them if they would like to log out.

include 'header2.php';

//checks to see if the user is already logged in
if (isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true)
{
	//if they are indeed already logged in, returns a message asking if they would like to log out
	echo '<div class="error">You are already logged in! <a href="logout.php">Click here</a> to log out.</div>';
} else 
{ 	//if they are not already logged in, displays a form for them to log in
	//the form POSTS to confirm-login.php
	echo '<form action="confirm-login.php" method="post">
			Username: <input type="text" name="username" maxlength="15" /><br />
			Password: <input type="password" name="password"><br />
			<input type="submit" value="Submit Login" />
		</form>

		<form action="index.php" method="">
			<input type="submit" value="Cancel" />
		</form>';
}

include 'footer.php';
?>
