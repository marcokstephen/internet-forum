<?php
// LOGOUT.PHP
// PRIMARY USER: Everyone
// PURPOSE: This page logs out a user by destroying their PHP session.

session_start();
session_destroy(); //effectively logs the user out
include 'header.php';
//displays a confirmation that they have been logged out
echo '<div class="error">You have successfully logged out. Return to <a href="index.php">board list</a>.</div>';

include 'footer.php';
?>
