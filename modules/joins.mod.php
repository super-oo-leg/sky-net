<?php # File name: joins..mod.php; Location: /modules/;

/*
 * This page shows user's or someone's joins.
 */

// Blocking direct access [For all modules]
if (!defined('BASE_URL')) {
	require('../includes/block.direct.access.php');
}

// Detet whos joins user wants to view
if (isset($_GET['view'])) {
	$view = $driver->sanitize($_GET['view']);
} else {
	$view = $user;
}

// Preparing appeals
if ($view === $user) {
	$name1 = 'You do not';
	$name2 = 'You are';
	$name3 = 'You';
} else {
	$name1 = "$view does not";
	$name2 = "$view is";
	$name3 = $view;
}

// His/Her info
echo '<div class="joins_owner">';
Driver::showThumbnail($view);
echo "<h1>$view</h1></div>";

// Retrieving followers
$followers = [];
$res = $driver->query("SELECT friend FROM friends WHERE user='$view'");
$num = $res->num_rows;
for ($j = 0; $j < $num; ++$j) {
	$followers[$j] = $res->fetch_assoc()['friend'];
}

// Retrieving followings
$following = [];
$res = $driver->query("SELECT user FROM friends WHERE friend='$view'");
$num = $res->num_rows;
for ($j = 0; $j < $num; ++$j) {
	$following[$j] = $res->fetch_assoc()['user'];
}

// Final arrays preparings
$mutual = array_intersect($followers, $following);
$followers = array_diff($followers, $mutual);
$following = array_diff($following, $mutual);

// Function for displaying list of joins
function display_list($people, $label) {
	if (!sizeof($people)) return FALSE;
	echo "<td><span class=\"subhead\">$label</span>";
	foreach ($people as $guy) {
		echo '<p><div class="join_unit">';
		echo '<span class="thumbnail">';
		Driver::showThumbnail($guy, "Visit {$guy}'s home");
		echo '</span>';
		echo $guy;
		echo '</div></p>';
	}
	echo '</td>';
	return TRUE;
}

// Display joins
echo '<table class="joins"><tr>';
$friends = display_list($mutual, "$name2 Mutually Joined");
$friends .= display_list($following, "$name3 Joined");
$friends .= display_list($followers, "Joined $name3");
echo '</tr></table>';

// For the case when he/she does not have any joins at all
if (!$friends) echo "<div class=\"info\">$name1 have any joins yet</div>";
