<?php # File name: signup.php; Location: /modules/;

/*
 * Signing up page
 */

// Blocking direct access [For all modules]
if (!defined('BASE_URL')) {
	require('../includes/block.direct.access.php');
}

// JS for validating sign up form fields
echo '<script src="' . BASE_URL . 'includes/validate_signup_form.js"></script>';

echo '<h3 class="center">Please choose your details to sign up</h3>'; // Title

// Error Handling
$error_u = $error_p = $user = $pass = NULL;

// Logining out
if (isset($_SESSION['user'])) {
	Driver::destroySession();
}

// Sign up atempt
if (isset($_POST['user'])) {

	// Sanitizing the fields
	$user = $driver->sanitize($_POST['user']);
	$pass = $driver->sanitize($_POST['pass']);

	// Username validation
	if (strlen($user) < 2) {
		$error_u .= '&#x2718; Username must be at least 2 characters<br />';
	} else {
		if (preg_match('/[^a-zA-Z0-9_-]/', $user)) {
			$error_u .= '&#x2718; Only english letters, numbers and either - or _ sign allowed<br />';
		}
		if ((substr_count($user, '_') + substr_count($user, '-')) > 1) {
			$error_u .= '&#x2718; Only 1 either _ or - sign is allowed<br />';
		}
		if (!preg_match('/^[a-zA-Z]/', $user)) {
			$error_u .= '&#x2718; Only letter can be used as a first character<br />';
		}
		if (preg_match('/[-|_]$/', $user)) {
			$error_u .= '&#x2718; Login can not end with sign<br />';
		}
		if (!$error_u && $driver->query("SELECT user FROM members WHERE user='$user'")->num_rows) {
			$error_u .= '&#x2718; Sorry, this username is taken<br />';
		}
	}

	// Password validation
	if (strlen($pass) < 6) {
		$error_p .= '&#x2718; Password must be at least 6 characters<br />';
	} else {
		if (!preg_match('/[^0-9]/', $pass)) {
			$error_p .= '&#x2718; Password cannot consist of numbers only<br />';
		}
	}

	// Adding user
	if (empty($error_u) && empty($error_p)) {

		// Grabbing IP
		$ip = $_SERVER['REMOTE_ADDR'];

		// Salting password
		$pass = sha1('_)9' . $pass . 'd+nF');

		// Adding user to DB
		$driver->query("INSERT INTO members(user, pass, ip) VALUES ('$user', '$pass', '$ip')");
		$driver->query("INSERT INTO profiles(user) VALUES ('$user')");
		$pass = NULL;

		// Loggin in...
		$_SESSION['user'] = $user;
		$url = BASE_URL . 'home';
		header("Location: $url");

		// For the case when redirect fails
		Driver::end('Please, <a href="$url">click here</a> to continue...');

	} else {
		if ($error_u) {
			$error_u .= '<br />';
		}
		if ($error_p) {
			$error_p .= '<br />';
		}
	}
}

// HTML Form
echo <<<_END
<form method="post" action="signup" onSubmit="return validate(this)" style="width:300px;margin:auto;">
<input id="login" type="text" maxlength="16" name="user" value="$user" style="width:100%"
	placeholder="Desired nickname"
	onkeyup="validateUsername(this.value)" onblur="validateAndCheckUsername(this.value)" />
<div class="error"><span id="info_u">$error_u</span></div>
<input id="password" type="password" maxlength="16" name="pass" style="width:100%"
	placeholder="Choose the password"
	onkeyup="validatePassword(this.value)" />
<div class="error"><span id="info_p">$error_p</span></div>
<input type="submit" value="Sign up" style="width:100%" />
</form>
_END;
