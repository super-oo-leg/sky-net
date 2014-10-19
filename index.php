<?php # This file should be named as "index.php" and be located in the root of the project #
/**
 * This is a bootstrap file for SKY-NET
 *
 * This page loads configurations and makes a DB connection with an object "$driver";
 * Quering DB:			$driver->query( 'Some Query' );
 * Making string safe:	$safe = $driver->sanitize( $unsafe );
 * @author SuperOleg <devilaz@ukr.net>
 * @copyright 2014
 */

// Require the configuration file before any PHP code:
require('./includes/driver.inc.php'); // THIS FILE SHOULD HAVE VALID LOCATION !!!
$driver = new Driver(); // It makes a DB connection which is needed for each page

// Validate what page to show:
if (isset($_GET['p'])) {
	$p = $_GET['p'];
} elseif (isset($_POST['p'])) {
	$p = $_POST['p'];
} else {
	$p = NULL;
}

switch ($p) { // Determine what page to display:

	case 'home': // Home page
		$page = 'home.mod.php';
		$log_req = TRUE; // Login is required
	break;

	case 'joins': // User's joins
		$page = 'joins.mod.php';
		$log_req = TRUE; // Login is required
	break;

	case 'signup': // Sign up form
		$page = 'signup.mod.php';
		$log_req = FALSE; // Login is not required
	break;

	case 'login': // Login form
		$page = 'login.mod.php';
		$log_req = FALSE; // Login is not required
	break;

	case 'members': // Project members list
		$page = 'members.mod.php';
		$log_req = TRUE; // Login is required
	break;

	case 'profile': // User's profile
		$page = 'profile.mod.php';
		$log_req = TRUE; // Login is required
	break;

	default: // Default is to include the main page
		$page = 'main.mod.php';
		$log_req = FALSE; // Login is not required
	break;

} // End of main switch.

// Make sure the file exists:
if (!file_exists(BASE_URI . 'modules/' . $page)) {
	$page = 'main.mod.php';
	$log_req = FALSE; // Login is not required
}

// Include the header file:
require_once(BASE_URI . 'includes/header.inc.html');

// Include the content-specific module:
// $page is determined from the above switch.
// Included module can use: $driver, $user, $loggedin.
require_once(BASE_URI . 'modules/' . $page);

// Include the footer file to complete the template:
require_once(BASE_URI . 'includes/footer.inc.html');

?>