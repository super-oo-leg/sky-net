function validateUsername(field) {
	field = field.trim();
	var error = "";
	if (field.length < 2) error += "&#x2718; Username must be at least 2 characters<br />";
	else {
		if (field.match(/[^a-zA-Z0-9_-]/))
			error += "&#x2718; Only english letters, numbers and either - or _ sign allowed<br />";
		if ((field.match(/[-|_]/g) || []).length > 1)
			error += "&#x2718; Only 1 either _ or - sign is allowed<br />";
		if (!field.match(/^[a-zA-Z]/))
			error += "&#x2718; Only letter can be used as a first character<br />";
		if (field.match(/[-|_]$/))
			error += "&#x2718; Login can not end with sign<br />";
	}
	if (error) error += '<br />';
	O('info_u').innerHTML = error;
	return error;
}

function validateAndCheckUsername(field) {
	if (!validateUsername(field)) checkUser(field);
}

function validatePassword(field) {
	field = field.trim();
	var error = '';
	if (field.length < 6) error += '&#x2718; Password must be at least 6 characters<br />';
	else {
		if (!/[^0-9]/.test(field))
		error += '&#x2718; Password cannot consist of numbers only<br />';
	}
	if (error) error += '<br />';
	O('info_p').innerHTML = error;
	return error;
}

function validate(form) {
	var fail = '';
	fail += validateUsername(form.user.value);
	fail += validatePassword(form.pass.value);
	if (fail == '') return true;
	else return false;
}

function checkUser(user) {
	params = "user=" + user;
	request = new ajaxRequest();
	request.open("POST", "ajax/checkuser.ajax.php", true);
	request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	request.setRequestHeader("Content-length", params.length);
	request.setRequestHeader("Connection", "close");
	request.onreadystatechange = function() {
		if (this.readyState == 4)
			if (this.status == 200)
				if (this.responseText) {
					if (this.responseText == 1) {
						O('info_u').innerHTML =
							'<span class="available">&#x2714; This username is free</span><br /><br />';
					} else {
						O('info_u').innerHTML =
							'<span class="taken">&#x2718; Sorry, this username is taken</span><br /><br />';
					}
				}
	}
	request.send(params);
}
