<?php
// USER.PHP
// PRIMARY USER: Everyone
// PURPOSE: This page displays public information for a user. It is accessed by clicking
// on a users name, next to a post that they have made.

include 'header.php';
include 'connect.php';

//Using HTTP GET, obtains the user id that we want to lookup, as well as the
//board ID and topic ID that the user was found in. We only need the user ID to be
//able to look the user up, but we get the topic ID and board ID as well so that we can generate links
//on the page to be able to return to the topic that the username was originally clicked on.
$userID = mysql_real_escape_string($_GET['uid']);
$topicID = mysql_real_escape_string($_GET['tid']);
$boardID = mysql_real_escape_string($_GET['bid']);

//the SQL query that fetches the user's information, based on what their user ID is
$userQuery = "SELECT user_name,user_id,user_level,user_date FROM users WHERE user_id = " . $userID;
$userResult = mysql_query($userQuery);
$userRow = mysql_fetch_assoc($userResult);

//this SQL query fetches additional information about the user's level, such as the description
//for their level and the title for their level
//this query is done separately because level data is stored in a different SQL table than user information
$level = $userRow['user_level'];
$levelQuery = "SELECT level, title, description FROM levels WHERE level = " . $level;
$levelResult = mysql_query($levelQuery);
$levelRow = mysql_fetch_assoc($levelResult);

$levelTitle = $levelRow['title'];
$levelDesc = $levelRow['description'];

// if the user is a level that has not been defined in the SQL database, the level title and description
// default to the following information:
if ($levelTitle == "")
{
   $levelTitle = "Undefined";
   $levelDesc = "This user level is undefined";
}

//once all the information has been fetched, we can create a table to display the data neatly, populating
//it where required with the information that we fetched above.
echo '<div id="board_list_title"><a href="topics.php?bid=' . $boardID . '">Topic List</a> | 
		<a href="posts.php?bid=' . $boardID . '&tid=' . $topicID . '">Message List</a></div>';
echo '<table class="me-table">
   <tr>
      <td class="me-table-info">User Name</td>
      <td width=80% class="me-table-data">' . $userRow['user_name'] . '</td>
   </tr>
   <tr>
      <td class="me-table-info">User ID</td>
      <td class="me-table-data">' . $userRow['user_id'] . '</td>
   </tr>
   <tr>
      <td class="me-table-info">User Level</td>
      <td class="me-table-data">' . $userRow['user_level'] . ': ' . $levelTitle . '<br />
			<span style="font-weight:normal;">' . $levelDesc . '</span></td>
   <tr>
      <td class="me-table-info">Account Created</td>
      <td class="me-table-data">' . $userRow['user_date'] . '</td>
   </tr>
   </table>';

include 'footer.php';
?>
