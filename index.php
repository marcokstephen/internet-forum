<?php
// INDEX.PHP
// PRIMARY USER: Everyone
// PURPOSE: This page displays the list of all different board categories that
// are currently on the site. The page checks the minimum board-view-level, and the current level
// of the signed in user to determine if the user has permission to view the board. If they do not
// have permission to view the board, the board will not be listed for them! Additional information
// for each board is visible if the user is an administrator.

include 'header.php';
include 'connect.php';
?>

<!-- Creates a table to display the header information for the list of boards -->
<div id="board_list_title" class="dev">
<table class="board_list_table"><tr>
	<td width="50%">Board Title</td>
	<td width="25%">Topics</td>
	<td width="25%">Last Post</td>
	</tr></table>
</div>
<?php
// This query returns a list of all boards currently created on the forum
$sql = "SELECT board_id, board_name, board_description, board_view, board_create FROM boards";
$result = mysql_query($sql);

if(!$result)
{
	echo 'There is an error, try again later.';
} else 
{	//if there is not a database error, returns the information

	while($row = mysql_fetch_assoc($result))
	//We create an overall layout for a single board entry, and populate the layout with information returned
	//from the first row of the SQL query. For each row returned from the SQL query, we duplicate the layout and
	//inject the next row's information into it. In this situation, we are displaying the information
	//in the form of a table.
    {
		//query to determine how many topics are on each board.
        $numberTopics = "SELECT COUNT(topic_id) FROM topics WHERE topic_board = " . $row['board_id'];
        $numTopics = mysql_query($numberTopics);
        $topicCount = mysql_fetch_assoc($numTopics);
		
		//query to determine when the last post was that occurred on the specific board
		$recentPost = "SELECT last_post FROM topics WHERE topic_board = '" . $row['board_id'] . "' ORDER BY last_post DESC LIMIT 1";
		$recentPosts = mysql_query($recentPost);
		$mostRecentPost = mysql_fetch_assoc($recentPosts);
	
		//determines if the board requires a level > 2 in order to view the board (ie, boards for moderators
		//or administrators.
		//NOTE: IF THE BOARD DOES NOT REQUIRE A LEVEL > 2 TO VIEW, IT WILL BE VIEWABLE TO ALL GUESTS AS WELL!
        if ($row['board_view'] > 2)
		{	//if the board requires a level greater than 2 to view, extra security
			//checks must be made to ensure that the user has access to the board
			if ($_SESSION['user_level'] >= $row['board_view'])
			{	//if the user level is >= the requirement, the board appears
				echo '<div class="board_section dev">
					<table style="width:100%;font-family:arial;"><tr>
					<td width="50%">
					<div class="board_title"><a href="topics.php?bid=' . $row['board_id'] . '">' . $row['board_name'] . '</a></div>
					<div class="board_summary">' . $row['board_description'] . '</div>
					</td>
					<td width="25%" style="text-align:center;">' . $topicCount['COUNT(topic_id)'] . '</td>
					<td width="25%" style="text-align:center;">' . $mostRecentPost['last_post'] . '</td></tr></table>';
				if ($_SESSION['user_level'] >= 4)
				{	//assumes that administrators are level 4 or above
					//this section displays the minimum view level and minimum create level for each board, to easily check permissions
					echo '<i style="font-size:7pt;">Minimum view level: ' . $row['board_view'] . ' | Minimum create level: ' . $row['board_create'] . '</i>';
				}
				echo '</div>';
			} 
		} else 
		{	// if the board does not require a level > 2, it is always viewable, regardless of whether or not the user is logged in
			echo '<div class="board_section dev">
					<table style="width:100%;font-family:arial;"><tr>
						<td width="50%">
							<div class="board_title"><a href="topics.php?bid=' . $row['board_id'] . '">' . $row['board_name'] . '</a></div>
							<div class="board_summary">' . $row['board_description'] . '</div>
						</td>
						<td width="25%" style="text-align:center;">' . $topicCount['COUNT(topic_id)'] . '</td>
						<td width="25%" style="text-align:center;">' . $mostRecentPost['last_post'] . '</td></tr></table>';
			
			if ($_SESSION['user_level'] >= 4)
			{ 	//assumes that administrators are level 4 or above
				//this section displays the minimum view level and minimum create level for each board, to easily check permissions
				echo '<i style="font-size:7pt;">Minimum view level: ' . $row['board_view'] . ' | Minimum create level: ' . $row['board_create'] . '</i>';
			}
			echo '</div>';
		}
    }
}

include 'footer.php';
?>
