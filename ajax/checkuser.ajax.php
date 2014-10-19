<?php # This file should be named as "checkuser.ajax.php" and be located at "/ajax/" #
/**
 * This script checks the availability of username with Ajax/
 *
 * The script receives a user's desired username with POST request and
 * checks it for availibility. It returns 0 in the case when username
 * already exists and 1 when the username is available for signing up.
 */

// Getting drvier for DB using
require_once('../includes/driver.inc.php');
$driver = new Driver();

// Checking username
if (isset($_POST['user'])) {
	$q = sprintf( "SELECT * FROM members WHERE user='%s'", $driver->sanitize( $_POST[ 'user' ] ));
	if ( $driver->query($q)->num_rows ) {
		echo '0'; // Unavailable
	} else {
		echo '1'; // Available
	}
}
