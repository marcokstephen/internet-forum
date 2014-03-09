<?php
// HELP.PHP
// PRIMARY USER: Everyone
// PURPOSE: This page displays help information. It is linked-to by the 
// navbar found in header.php
// This page can display whatever you like, but currently, it displays
// some information regarding using HTML in posts, the level system,
// and the current users registered on the board.

include 'header.php';
include 'connect.php';

//This query returns the list of user permission levels. It is sorted to return
//in the order of the level(ie, -2, -1, 1, 2, 3...etc)
$levelQuery = "SELECT level,title,description FROM levels ORDER BY level";
$levelResult = mysql_query($levelQuery);
//This query returns the list of users currently registered on the forum.
$userQuery = "SELECT user_id,user_name,user_level,user_date FROM users ORDER BY user_id";
$userResult = mysql_query($userQuery);

echo '<div style="width:900px;margin-left:auto;margin-right:auto;padding-top:10px;padding-bottom:10px;">';
echo '<div class="help-title">HTML in Posts</div>';
echo 'You may use &lt;p&gt; and &lt;br&gt; tags in your messages to create line breaks. For 
	example, to achieve this<br /><br />effect, use &lt;br&gt;&lt;br&gt;!';
echo '<div class="help-title">User Levels</div>';
echo '<table id="help-table">';
//Creates a new table row for each level, where each row gives the level number, level name, and
//a description of the level
while ($row = mysql_fetch_assoc($levelResult))
{
   echo '<tr><td style="padding-top:1px;padding-bottom:1px;"><B>' . $row['level'] . ': ' . $row['title'] . '</B><br />' . $row['description'] . '</td></tr>';
}
echo '</table>';

echo '<div class="help-title">Current Users</div>';
echo '<table style="text-align:center;" id="help-table">';
echo '<tr style="font-weight:bold;"><td style="padding-bottom:4px;" width=25%>User ID</td>
	<td width=25%>Username</td><td width=25%>User Level</td><td width=25%>Registration Date</td></tr>';
	
//Creates a new table row for each user, where each row gives the user ID, username, user level, and account creation date
while ($rowUser = mysql_fetch_assoc($userResult))
{
   echo '<tr><td>' . $rowUser['user_id'] . '</td><td>' . $rowUser['user_name'] . '</td><td>' . $rowUser['user_level'] . ': ';
	//Here, we have a query to get more information regarding each user level, since the levels and their information are stored in a different
	//table from the users.
   $specificLevel = "SELECT title FROM levels WHERE level = " . $rowUser['user_level'];
   $specificResult = mysql_query($specificLevel);
   $rowLevel = mysql_fetch_assoc($specificResult);
   echo $rowLevel['title'];
   echo '</td><td>' . $rowUser['user_date'] . '</td></tr>';
}
echo '</table>';

echo '</div><!-- end wrapper for this page -->';
include 'footer.php';
?>