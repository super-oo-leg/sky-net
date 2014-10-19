<?php # File name: showProfile.fun.php; Location: /includes/;

function showProfile($user, $driver) {
	echo '<div id="profile">';

	// User wants to view own or someone's profile
	if ($user === $_SESSION['user']) {
		$own = TRUE;
	} else {
		$own = FALSE;
	}

	// Avatar image path
	$img_url = BASE_URL . AVATARS_DIR . strtolower("$user.jpg");

	// When user doesn't have an avatar yet viewing default one
	if (!file_exists(BASE_URI . AVATARS_DIR . strtolower("$user.jpg"))) {
		$img_url = BASE_URL . DEF_AVATAR;
	}

	// Preparing Html Avatar Code
	$img = sprintf('<img src="%s" alt="%s" align="left" />', $img_url, $user);

	// Expanding Code with a link to user's home
	if (!$own) {
		$img = sprintf('<a title="%s" href="%s">%s</a>', $user, BASE_URL . "$user/home", $img);
	}

	// Outputting prepared html code
	echo $img . '<div id="prof_info">';

	// Retreiving user's info
	if ($own) {
		$info['text'] = $_SESSION['about'];
		$info['firstname'] = $_SESSION['firstname'];
		$info['lastname'] = $_SESSION['lastname'];
	} else {
		$r = $driver->query("SELECT text, firstname, lastname, score, last_visit
			FROM profiles WHERE user='$user'");
		if ($r->num_rows) {
			$info = $r->fetch_assoc();
			$info['last_visit'] = strtotime($info['last_visit']);
		}
	}

	// Display Username or 'You' label
	echo ($own) ? '<h1>You</h1>' : "<h1>$user</h1>";

	// Build HTML
	echo '<div class="prof_about"><bdi>';

	// User's full name
	if ($info['firstname'] || $info['lastname']) {
		echo stripcslashes($info['firstname']) . ' ' . stripcslashes($info['lastname']);
	}

	// Online
	if ( !$own ) {
		if ( (time() - $info[ 'last_visit' ]) > 60 * 7 ) {
			if (date('mjY', time()) === date('mjY', $info[ 'last_visit' ])) {
				echo ' was online at ' . date( 'g:i A',
					Driver::timeToUserTime( $info[ 'last_visit' ] ));
			} else {
				echo ' was online on ' . date( 'F j',
					Driver::timeToUserTime( $info[ 'last_visit' ] ));
			}
		} else {
			echo ' # Online';
		}
	}

	// About user
	echo '</bdi><br /><p><bdi>' . stripslashes($info['text']) . '</bdi></p>';

	if (!$own) {
		$user_level = Driver::getUserLevel( $info[ 'score' ] );
		if ( $user_level > $_SESSION[ 'level' ] ) {
			echo "<p style=\"color:#e73\">Level: $user_level</p>";
		} else {
			echo "<p>Level: $user_level</p>";
		}
	}

	// Display Links Under user's info
	echo '</div></div><div class="links">';
	if (!$own) {
		printf('<a class="button" href="%s">%s</a>', BASE_URL . "$user/joins", "$user's joins");
	} else {
		echo '<a class="button" href="profile">Edit</a>';
	}

	// Make more spacing
	echo '</div></div><div class="clear"></div><div style="width:100%;height:20px;"></div>';
}