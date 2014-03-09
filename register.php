<?php session_start(); 
// REGISTER.PHP
// PRIMARY USER: Everyone
// PURPOSE: Displays a registration form for users to create accounts
// on the forum. If the user is already signed in, returns an error.
// If not, the form is displayed and POSTS to confirm-registration.php

include 'header2.php';

//checks to see if the user is already logged in
if (isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true)
{
	echo '<div class="error">You are already logged in! <a href="logout.php">Click here</a> to log out.</div>';
} else
{ //if the user is not already logged in, displays the registration form
	echo '<div id="notice">As a precaution, keep in mind that you should always use different passwords on different websites.</div><br />

	<form action="confirm-registration.php" method="post">
		Username: <input type="text" name="create-username" maxlength="15" /><br />
		Password: <input type="password" name="create-password" /><br />
		Confirm Password: <input type="password" name="confirm-password" /><br />
		E-mail: <input type="text" name="create-email" maxlength="45" /><br />
		I am over 13 years of age <input type="checkbox" name="create-age" value="Y" /><br />
		<input type="submit" value="Complete Registration">
	</form>

	<form action="index.php" method="">
		<input type="submit" value="Cancel">
	</form>';
}

include 'footer.php';
?>
