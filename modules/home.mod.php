<?php # File name: home.mod.php; Location: /modules/;

/*
 * This page shows user's or someone's: profile, wall with messages.
 * This page requires: to be logged in.
 * This page handles: leaving a message on the wall, constantly refrashing wall.
 */

// Blocking direct access [For all modules]
if (!defined('BASE_URL')) {
	require('../includes/block.direct.access.php');
}

// Detect which page user wants to view
if (isset($_GET['view'])) {
	$view = $driver->sanitize($_GET['view']);
} else {
	$view = $user;
}

// jQuery: Auto-growing textarea, Masonry columns sorting.
// JS: Validation for non-empty messages, Submit buttons dynamic visibility.
// Ajax: Wall messages getting and refreshing.
$base_url = BASE_URL;

echo <<<_END
<script src="$base_url/includes/auto-grow-txt.js"></script>
<script src="$base_url/includes/masonry.pkgd.min.js"></script>
<script>
$(function() { $("#wall_msg_input").autogrow(); } );
function check() {
	if (O("wall_msg_input").value.trim()) {
		S("wall_msg_btn_1").display = "block";
		S("wall_msg_btn_2").display = "block";
		S("wall_msg_input").borderBottom = "0";
	} else {
		S("wall_msg_btn_1").display = "none";
		S("wall_msg_btn_2").display = "none";
		S("wall_msg_input").borderBottom = "2px solid #5a5";
	}
}
function sort_msgs() {
	var container = document.querySelector("#user_messages");
	var msnry = new Masonry( container, {
		columnWidth: 310,
		itemSelector: ".wall_message"
	});
}
lastresponse = "";
function showMessages(id) {
	params = "visit=" + "$view";
	if (id > -1) params +=  "&id=" + id;
	request = new ajaxRequest();
	request.open("POST", "$base_url/ajax/getmessages.ajax.php", true);
	request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	request.setRequestHeader("Content-length", params.length);
	request.setRequestHeader("Connection", "close");
	request.onreadystatechange = function() {
		if (this.readyState == 4)
			if (this.status == 200)
				if (this.responseText) {
					if (lastresponse != this.responseText) {
						O('user_messages').innerHTML = this.responseText;
						lastresponse = this.responseText;
						sort_msgs();
					}
				}
	}
	request.send(params);
}
showMessages(-1);
setInterval(function() { showMessages(-1) } , 5000);
</script>
_END;

// Posting new message on the wall
if (isset($_POST['text']) && !empty($_POST['text'])) {
	$q = sprintf("INSERT INTO messages (auth,recip,pm,time,message) VALUES ('%s','%s',%d,%d,'%s')",
		$user,
		$view,
		($_POST['pm'] === 'Send public') ? 0 : 1,
		time(),
		$driver->sanitize($_POST['text'])
	);
	$driver->query($q);
	$driver->refresh();
}

// Showing the Home
if (!empty($view)) {

	// Display user's or someone's profile
	require_once(BASE_URI . 'includes/showProfile.fun.php');
	showProfile($view, $driver);

	// Detect the page's relative url
	$action = BASE_URL;
	$action .= ($view === $user) ? 'home' : "$view/home";

	// HTML: Sending message form.
	echo <<<_END
<div id="wall_msg_box">
<form method="post" action="$action">
<textarea id="wall_msg_input" name="text" placeholder="Leave a message"
	maxlength="1000" rows="1" onkeyup="return check()"></textarea>
<input id="wall_msg_btn_1" style="display:none;" type="submit" name="pm" value="Send private" />
<input id="wall_msg_btn_2" style="display:none;" type="submit" name="pm" value="Send public" />
</form>
</div><div class="clear"></div>
<div id="user_messages"></div>
_END;
}
