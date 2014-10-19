<?php # File name: profile.php; Location: /modules/;

/*
 * This page enables users to edit their profile
 */

// Blocking direct access [For all modules]
if (!defined('BASE_URL')) {
	require('../includes/block.direct.access.php');
}

// Auto-growing textarea
printf('<script src="%s"></script>', BASE_URL . 'includes/auto-grow-txt.js');
echo '<script>$(function() { $("#about").autogrow(); } );</script>';

// Updating posted info
if (isset($_POST['text']) && isset($_POST['firstname']) && isset($_POST['lastname'])) {

	// Sanitizing content
	$text = $driver->sanitize($_POST['text']);
	$firstname = $driver->sanitize($_POST['firstname']);
	$lastname = $driver->sanitize($_POST['lastname']);

	// Escaping unnecessary spacing
	$text = preg_replace('/\s\s+/', ' ', $text);
	$firstname = preg_replace('/\s\s+/', ' ', $firstname);
	$lastname = preg_replace('/\s\s+/', ' ', $lastname);

	// Update info whether profile exists otherwise create one
	if ($driver->query("SELECT * FROM profiles WHERE user='$user'")->num_rows) {
		$q = "UPDATE profiles SET
		text='$text', firstname='$firstname', lastname='$lastname' WHERE user='$user'";
		$driver->query($q);
	} else {
		$driver->query("INSERT INTO profiles (user, text, firstname, lastname)
		VALUES ('$user', '$text', '$firstname', '$lastname')");
	}
}

// Update avatar image
if (isset($_FILES['image']['name'])) {

	// Setting pathes
	$saveto_l = BASE_URI . THUMBS_DIR . strtolower("$user.jpg");
	$saveto_b = BASE_URI . AVATARS_DIR . strtolower("$user.jpg");

	// Move uploaded file to correct directory
	move_uploaded_file($_FILES['image']['tmp_name'], $saveto_b);

	// Validation var
	$typeok = TRUE;

	// Creating apropriate image from uploaded one
	switch($_FILES['image']['type']) {
		case 'image/gif'	:	$src = imagecreatefromgif($saveto_b);	break;
		case 'image/jpeg'	:	// Allow both regular and progressive JPEGs //
		case 'image/pjpeg'	:	$src = imagecreatefromjpeg($saveto_b);	break;
		case 'image/png'	:	$src = imagecreatefrompng($saveto_b);	break;
		default			:	$typeok = FALSE;						break;
	}

	// Resizing and Saving images
	if ($typeok) {

		// Get original size
		list($w, $h) = getimagesize($saveto_b);

		// Setting max sizes of profile avatar and thumbnail
		$max_b = 302;
		$max_l = 65;

		// Initiate temp width and height for both images
		$tw_b = $tw_l = $w;
		$th_b = $th_l = $h;

		// Get max possible size of avatar
		if ($w > $h && $max_b < $w) {
			$th_b = $max_b / $w * $h;
			$tw_b = $max_b;
		}
		elseif ($h > $w && $max_b < $h) {
			$tw_b = $max_b / $h * $w;
			$th_b = $max_b;
		}
		elseif ($max_b < $w) $tw_b = $th_b = $max_b;

		// Get max possible size of thumbnail
		if ($w > $h && $max_l < $w) {
			$th_l = $max_l / $w * $h;
			$tw_l = $max_l;
		}
		elseif ($h > $w && $max_l < $h) {
			$tw_l = $max_l / $h * $w;
			$th_l = $max_l;
		}
		elseif ($max_l < $w) $tw_l = $th_l = $max_l;

		// Resizing and saving to apropriate derictories
		$tmp_b = imagecreatetruecolor($tw_b, $th_b);
		$tmp_l = imagecreatetruecolor($tw_l, $th_l);
		imagecopyresampled($tmp_b, $src, 0, 0, 0, 0, $tw_b, $th_b, $w, $h);
		imagecopyresampled($tmp_l, $src, 0, 0, 0, 0, $tw_l, $th_l, $w, $h);
		imageconvolution($tmp_b, [[-1, -1, -1], [-1, 16, -1], [-1, -1, -1]], 8, 0);
		imageconvolution($tmp_l, [[-1, -1, -1], [-1, 16, -1], [-1, -1, -1]], 8, 0);
		imagejpeg($tmp_b, $saveto_b);
		imagejpeg($tmp_l, $saveto_l);
		imagedestroy($tmp_b);
		imagedestroy($tmp_l);
		imagedestroy($src);
	}
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	Driver::refresh();
}

// Retrieve current user's data from DB
$res = $driver->query("SELECT text, firstname, lastname FROM profiles WHERE user='$user'");
if ($res->num_rows) {
	$data = $res->fetch_assoc();
	$text		= stripslashes(preg_replace('/\s\s+/', ' ', $data['text']		));
	$firstname	= stripslashes(preg_replace('/\s\s+/', ' ', $data['firstname']	));
	$lastname	= stripslashes(preg_replace('/\s\s+/', ' ', $data['lastname']	));
} else {
	$text = $firstname = $lastname = NULL;
}

// Image for avatar changing button
if (file_exists(BASE_URI . AVATARS_DIR . strtolower("$user.jpg"))) {
	$img = BASE_URL . AVATARS_DIR . strtolower("$user.jpg");
} else {
	$img = BASE_URL . DEF_AVATAR;
}

// HTML Form
echo <<<_END
<script>
function askSave() {
	O('avatar_img').innerHTML = '<h2>Save changes to upload your new image!</h2>';
	O('profile_title').innerHTML = '';
}
</script>
<h1 class="center" id="profile_title">Edit your details or upload an avatar image</h1>
<form method="post" action="profile" enctype="multipart/form-data">
<div style="float: right;">
<label class="image_button">
	<span id="avatar_img"><img src="$img" title="Click here to change your avatar" title="Click here to change your avatar" /></span>
	<span><input id="image" type="file" name="image" size="14" maxlength="32" onchange="return askSave()" /></span>
</label>
<div class="clear"></div>
</div>

<div style="float: left;">
<label class="fieldname" for="firstname" style="display:block;">Firstname</label>
<input id="firstname" type="text" maxlength="20" size="20" name="firstname" value="$firstname" />
<div class="clear"></div>
<label class="fieldname" for="lastname" style="display:block;">Lastname</label>
<input id="lastname" type="text" maxlength="20" size="20" name="lastname" value="$lastname" />
<div class="clear"></div><br />
</div>
<br />
<textarea placeholder="Short story about yourself" id="about" maxlength="2000" name="text" rows="3">$text</textarea>
<div class="clear"></div>

<input id="save" type="submit" value="Save Changes" />
</form>
_END;
