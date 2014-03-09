<?php
// CONNECT.PHP
// PRIMARY USER: Database
// PURPOSE: This page initiates connection to the database.
// Returns error message if there is a problem. This page MUST
// be included in every page that accesses the database.

define('DB_NAME', '____'); //fill in this field with your database name
define('DB_USER', '____'); //fill in this field with your database login name
define('DB_PASSWORD', '____'); //fill in this field with your database password
define('DB_HOST', 'localhost'); //this should be localhost

$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if (!$link) {
	die('Could not connect: ' . mysql_error());
}

$db_selected = mysql_select_db(DB_NAME, $link);
if (!$db_selected) {
	die('Can\'t use ' . DB_NAME . ': ' . mysql_error());
}
?>