<?php # File name: main.mod.php; Location: /modules/;

/*
 * Home page of unlogged user
 * This page describes the site and ask user to log in or sign up.
 */


// Blocking direct access [For all modules]
if (!defined('BASE_URL')) {
	require('../includes/block.direct.access.php');
}

// Send authorized user to his home page
if ($loggedin) {
	$url = BASE_URL . 'home';
	header("Location: $url");

	// For the case when redirect fails
	Driver::end("Please, <a href=\"$url\">click here</a> to go home.");
}

// Welcome header
printf('<h1 class="center">Welcome to %s, stranger</h1>', APP_NAME);

// Get amount of signed up users
$amount = $driver->query('SELECT COUNT(*) FROM members')->fetch_row()[0];

$site_root = BASE_URL;
// Site description
echo <<<_END
<p class="center">$amount users already signed up</p>
<p class="center">Bla bla bla ... about site</p>
<p class="center">Please, <a href="{$site_root}signup">sign up</a> or
<a href="{$site_root}login">log in</a> to continue...</p>
_END;
