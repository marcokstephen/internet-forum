<?php 
// CONFIRM-REGISTRATION.PHP
// PRIMARY USER: Everyone
// PURPOSE: This page processes the HTTP POST request received when a user tries to register.
// Checks to see that all relevant information is supplied, and that the passwords in both
// fields match each other.

// Note: It would be nice [and fairly easy!] to merge this with the register.php page by making 
// the page POST to itself. However, I am lazy, so I will leave that as an exercise for you
// if you choose to do so.

include 'header2.php';
include 'connect.php';

//Initiates variables based on the form data. These variables will be used to check that form data is valid.
$value = mysql_real_escape_string($_POST['create-username']);
$value2 = mysql_real_escape_string($_POST['create-password']);
$value3 = mysql_real_escape_string($_POST['create-email']);
$value4 = mysql_real_escape_string($_POST['create-age']);
$value5 = mysql_real_escape_string($_POST['confirm-password']);

if (($value == '')||($value2 == '')||($value3 == '')||($value4 != 'Y'))
{ //ensures that form data is valid
	die('<div class="error">A field was missing. Please <a href="register.php">try again</a>, or return to the <a href="index.php">board list</a>.</div>');
}

if ($value2 != $value5){ //ensures that password matches the "confirm password" field
	die('<div>The two passwords did not match. Please <a href="register.php">try again</a>.</div>');
}

//if everything looks good, creates a new user with the given data
//currently, users are created with the default user level of 2
//if you add in an activation method, you can create a user with the default
//user level of 1, and then change them to 2 when they are successfully activated
$sql = "INSERT INTO users(user_name, user_pass, user_email, user_date, user_level)
	VALUES('" . strip_tags($value) . "',
		'" . sha1($value2) . "',
		'" . strip_tags($value3) . "', NOW(), 2)";
		
$result = mysql_query($sql);

if (!$result)
{
	die('Error: ' . mysql_error());
}
//confirms that the registration was completed
echo '<div class="error">Registration completed. Please check your email for the activation link. You can now <a href="login.php">login</a>.</div>';

include 'footer.php';
?>
