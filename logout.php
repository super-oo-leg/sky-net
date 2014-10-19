<?php # This file should be named as "logout.php" and be located at the root of the project #
/**
 * This page automatically logs user out and redirects him to the main page.
 */

// Starting session for checking is user logged in
session_start();

// Including driver to get function destroying session
require_once('./includes/driver.inc.php'); // THIS FILE SHOULD HAVE A VALID LOCATION !!!

// Destroy session
if (isset($_SESSION['user'])) {
	Driver::destroySession();
}

// Redirect user to main page
$location = BASE_URL;
header("Location: $location");
exit();