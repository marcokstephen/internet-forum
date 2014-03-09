<?php session_start(); 
// HEADER.PHP
// PRIMARY USER: Everyone
// PURPOSE: This page displays the header for every page on the website
// that uses this document. Allows for cleaner code in all other pages.
// Edit the header here, and it will be updated on all pages that use it!

?>
<!DOCTYPE html>

<!-- Coded by: Stephen Marcok @ the University of Waterloo
     Last Update: March 8, 2014
     http://www.stephenmarcok.com 
     **This forum is free for public use!**
     Want to create your own PHP message board?
     View http://www.stephenmarcok.com/blog for the source code -->
     
     
<html><head><title>Stephen's Message Boards</title>

<meta name="description" content="Stephen's Message Boards">
<meta name="keywords" content="stephen,marcok,waterloo,computer,science">
<meta name="author" content="Stephen Marcok">
<meta charset="UTF-8">

<link rel="stylesheet" type="text/css" href="reset.css">
<link rel="stylesheet" type="text/css" href="style.css">

</head>

<body>
<div id="wrapper">
<div id="header" class="dev">
	<B>Stephen's Message Boards</B>
</div>

<div id="navigation" class="dev">
	<ul>

<?php
include 'connect.php';

//if the user is signed in, the header displays a different navbar than if they are not logged in
if($_SESSION['signed_in'])
{
	//This code fetches the user name and user level stored in the PHP session, and uses it to display
	//the data in the navbar
	$username = $_SESSION['user_name'];
	$acctQuery = "SELECT user_level FROM users WHERE user_name = \"$username\"";
	$acctResult = mysql_query($acctQuery);
	$acctRow = mysql_fetch_assoc($acctResult);
	$_SESSION['user_level'] = $acctRow['user_level'];

	//displays the navbar for a user that is logged in
	echo '<li class="navbar"><a href="me.php">' . $_SESSION['user_name'] . ' (' . $_SESSION['user_level'] . ')</a></li>';
	echo '<span style="color:white;">|</span><li class="navbar"><a href="index.php">Board List</a></li>';
	echo '<span style="color:white;">|</span><li class="navbar"><a href="help.php">Help</a></li>';
	echo '<span style="color:white;">|</span><li class="navbar"><a href="logout.php">Log Out</a></li>';

} else 
{ //if the user is not logged in, the navbar prompts them to register or login
	echo '<li class="navbar"><a href="register.php">Register</a></li>';
	echo '<span style="color:white;">|</span><li class="navbar"><a href="login.php">Login</a></li>';
	echo '<span style="color:white;">|</span><li class="navbar"><a href="index.php">Board List</a></li>';
	echo '<span style="color:white;">|</span><li class="navbar"><a href="help.php">Help</a></li>';
}

if ($_SESSION['user_level'] > 3)
{ 	//if the user is an administrator, they view additional icons in the
	//navbar relating to administrative tasks
	echo '<span style="color:white;">|</span><li class="navbar"><a href="add-board.php">Edit Boards</a></li>';
	echo '<span style="color:white;">|</span><li class="navbar"><a href="edit-users.php">Edit Users</a></li>';
}

//You may notice that below, the HTML is incomplete. This is because the header page is not a complete, webpage. It
//is complemented by the main body of the page, as well as footer.php.
//The end of the HTML (ie, the </html> tag) can be found in footer.php
?>

</ul>
</div>

<div id="content" class="dev">
