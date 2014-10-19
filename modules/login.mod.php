<?php # File name: login.mod.php; Location: /modules/;

/*
 * This page checks user's login and password, if they are valid user comes home.
 */

// Blocking direct access [For all modules]
if (!defined('BASE_URL')) {
	require('../includes/block.direct.access.php');
}

// Ask user to fill the fields
echo '<h3 class="center">Please enter your details to log in</h3>';

// Initiate vars for error handling and last login remembering
$error = $user = NULL;

// When we get login atempt
if (isset($_POST['user'])) {
	$user = $driver->sanitize($_POST['user']);
	$pass = $driver->sanitize($_POST['pass']);

	// Checking for not filled fields
	if (empty($user) || empty($pass)) {
		$error = '&#x2718; Not all fields were entered';
	} else {

		// Salting password and looking for user at DB
		$pass = sha1('_)9' . $pass . 'd+nF');
		$res = $driver->query("SELECT user FROM members WHERE user='$user' AND pass='$pass'");
		$pass = NULL;

		// Display error message or redirect user to his home according
		// to right or wrong data was entered
		if (!$res->num_rows) {
			$error = '&#x2718; Username/Password invalid';
		} else {
			$row = $res->fetch_assoc();
			$_SESSION['user'] = $row['user'];
			$url = BASE_URL . 'home';
			header("Location: $url");

			// For the case when redirect fails
			Driver::end('Please, <a href="$url">click here</a> to continue...');
		}
	}
}

// Expand error message
if (!empty($error)) $error .= '<div class="clear"></div><br />';

// Outputting login form
echo <<<_END
<form method="post" action="login" style="width:300px;margin:auto;">
<div class="error">$error</div>
<input id="login" type="text" maxlength="16" name="user" value="$user"
	placeholder="Enter your nickname" style="width:100%" />
<div class="clear"></div>
<input id="password" type="password" maxlength="16" name="pass"
	placeholder="Password" style="width:100%" />
<div class="clear"></div>
<input type="submit" value="Login" style="width:100%" />
</form>
_END;
