<?php # This file should be named as "getmessages.ajax.php" and be located at "/ajax/" #
/**
 * This script outputs someone's wall messages and handles message deleting with Ajax.
 */

// Checking whether user logged in and diying if not
session_start();
if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
	die('You have no access for this function');
}

// Making DB Connection
require_once('../includes/driver.inc.php');
$driver = new Driver();

// Making username safe
$user = $driver->sanitize($_SESSION['user']);

// Handling message deleting
if (isset($_POST['id']) && !empty($user)) {
	$q = sprintf("DELETE FROM messages WHERE id='%d' AND recip='%s'", $_POST['id'], $user);
	$driver->query($q);
}

// View all messages
if (isset( $_POST[ 'visit' ] ) && !empty( $user )) {
	$q = sprintf( "SELECT * FROM messages WHERE recip='%s' ORDER BY time DESC",
		$driver->sanitize( $_POST[ 'visit' ] ));
	$res = $driver->query( $q );
	$num = $res->num_rows;
	for ( $j = 0; $j < $num; ++$j ) {

		// Retreiving one message
		$row = $res->fetch_assoc();

		// Show the message
		if ( $row['pm'] == 0 || $row['auth'] === $user || $row['recip'] === $user ) {

			// Making title
			$title = $row['auth'];
			$title .= ($row['pm']) ? ' whispered' : ' yelled';
			if (date('mjY', time()) === date('mjY', $row['time'])) {
				$title .= ' at' . date( ' g:ia', Driver::timeToUserTime( $row[ 'time' ] ));
			} else {
				$title .= ' on ' . date( ' M j, Y', Driver::timeToUserTime( $row[ 'time' ] ));
			}

			// Outputting a block with title
			echo '<div class="wall_message">';
			echo "<div id=\"wall_msg_title\">$title</div>";

			// Distinguishing whether it private or public message
			if ($row['pm'] == 1) {
				echo '<div class="pm">';
			} else {
				echo '<div class="not_pm">';
			}

			// Display Thumbnail
			echo '<span class="thumbnail">';
			Driver::showThumbnail($row['auth']);
			echo '</span>';

			// Display text of a message
			echo '<span class="textofmsg">' . $row['message'] . '</span>';

			// Display erase buttons for own wall
			if ($row['recip'] === $user) {
				echo '<div class="erase"><form><input type="button" onclick="showMessages(' .
				$row['id'] . ')" value="" /></form></div>';
			}

			// Finilizing HTML
			echo '</div></div>';
		}
	}

	// Making extra space
	echo '<br /><br /><div style="clear"></div><br /><br />';

	// "No messages" message when user have no ones yet
	if (!$num) echo '<div class="info">No messages yet</span></div>';
}
