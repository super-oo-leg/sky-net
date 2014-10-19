<?php # File name: members.mod.php; Location: /modules/;

/*
 * This page displays members list and sugest users to join them or to drop them
 */

// Blocking direct access [For all modules]
if (!defined('BASE_URL')) {
	require('../includes/block.direct.access.php');
}

// Handle Joins adding or removing
if (isset($_POST['add'])) {
	$add = $driver->sanitize($_POST['add']);
	if (!$driver->query("SELECT * FROM friends WHERE user='$add' AND friend='$user'")->num_rows) {
		$driver->query("INSERT INTO friends(user, friend) VALUES ('$add', '$user')");
	}
} elseif (isset($_POST['remove'])) {
	$remove = $driver->sanitize($_POST['remove']);
	$driver->query("DELETE FROM friends WHERE user='$remove' AND friend='$user'");
}

// Showing all users
printf('<h3 class="center">%s Members</h3><table id="members">', APP_NAME);

// Retrieve all users from DB
$res = $driver->query('SELECT user FROM members ORDER BY user');

// Count users amount
$num = $res->num_rows;

// Display each user
for ($j = 0; $j < $num; ++$j) {
	echo '<tr>';

	// Extracting one person. If that's user don't show him.
	$guy = $res->fetch_assoc()['user'];
	if ($guy === $user) continue;

	// View user's thumbnail
	echo '<td><span class="thumbnail">';
	Driver::showThumbnail($guy);
	echo '</span></td>';

	// View user's username and as a link to his home
	printf('<td><a href="%s">%s</a></td>', BASE_URL . "$guy/home", $guy);

	// Button for join user
	$follow = 'Join';

	// Getting how the guy relates to the user
	$t1 = $driver->query("SELECT * FROM friends	WHERE user='$guy' AND friend='$user'")->num_rows;
	$t2 = $driver->query("SELECT * FROM friends	WHERE user='$user' AND friend='$guy'")->num_rows;

	// Outputting relation info
	if (($t1 + $t2) > 1) echo "<td>&harr;</td><td>mutually joined</td>";
	elseif ($t1) echo "<td>&larr;</td><td>you joined</td>";
	elseif ($t2) {	echo "<td>&rarr;</td><td>joined you</td>"; $follow = 'Recip'; }
	else echo '<td>&nbsp;</td><td>stranger</td>';

	// Making button to Follow / Recip / Drop
	echo '<td><form method="post" action="members">';
	if (!$t1) {
		printf('<input type="hidden" name="add" value="%s" />', $guy);
		printf('<input class="btn" type="submit" value="%s" />', $follow);
	} else {
		printf('<input type="hidden" name="remove" value="%s" />', $guy);
		echo '<input class="btn" type="submit" value="Drop" />';
	}
	echo '</form></td>';

	echo '</tr>';
}

// Finilizing the table
echo '</table>';
