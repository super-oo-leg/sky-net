<?php # This file should be named as "block.direct.access.php" and be located at "/includes/" #
/**
 * Redirects the user if the page which includes this script has been accessed directly.
 *
 * Each module which should not be accessible directly should require this file at its beginning
 */

// Need the BASE_URL, defined in the config file:
require( 'driver.inc.php' );

//Redirect to the index page:
header( sprintf( "Location: %s", BASE_URL ));
die(sprintf('You have turned off redirects. Please, <a href="%s">go to the main page</a>.', $url));