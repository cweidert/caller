<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<meta name="mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<title>Student Caller</title>
		<link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="styles/caller.css" media="screen" />
		<link rel="icon" href="heliomug.ico" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
		<script src="scripts/jquery.validate.js"></script>
		<script src="scripts/jquery-serialization.js"></script>
	</head>
	<body>
		<header>
		</header>
		<main>
			<div class="heading">Create a new user!</div>
			<form id="formNewUser">	
				<div>First name: </div>
				<input type="text" name="firstname" required="required"><br>
				<div>Last name: </div>
				<input type="text" name="lastname" required="required"><br>
				<div>Username:</div>
				<input id="inputUsername" type="text" name="username" required="required"><br>
				<span id='usernameResults'></span>
				<div>E-mail: </div>
				<input type="email" name="email" required="required"><br>
				<div>Password: </div>
				<input type="password" name="password" required="required"><br>
				<a id="buttonCreateUser" href="#" class="button">Create new user</a>
			</form>
			<div id="results">
			</div>
		</main>
		<footer>
			<a id="buttonReturnToLogin" href="#" class="button">Return to Login</a>
		</footer>
	</body>
	<script>
		$('#inputUsername').blur(function(evt) {
			var info = {};
			info['username'] = $(evt.target).val();
			if ($(evt.target).val().length > 0) {
				$.post("call_checkUsername.php", info, function(data) {
					$('#usernameResults').html(data);
				});
			} else {
				$('#usernameResults').html("");
			}
		});
		
		$('#buttonCreateUser').click(function(evt) {
			evt.preventDefault();
			if ($('#formNewUser').valid()) {
				var user = $('#formNewUser').toObject();
				$.post('call_insertUser.php', user, function(data) {
					//$('#results').html(data);
					window.location.replace("view_sections.php");
				});
			} 
		});

		$('#buttonReturnToLogin').click(function(evt) {
			evt.preventDefault();
			window.location.replace("util_login.php");
		});

		$(document).ready(function () {
			$('header').load('header_no_login.php');
		});
	</script>
</html>