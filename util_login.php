<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
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
			<div class="heading">Login, Please!</div>
			<form id="userLoginForm" method="post">
				<div>Username: </div>
				<input type="text" name="username"><br>
				<div>Password: </div>
				<input type="password" name="password"><br>
				<a id="buttonValidateUser" class="button" href="#">Login!</a>
			</form>
		</main>
		<footer>
			<a id="buttonCreateUser" class="button" href="#">Create new user</a>
		</footer>
	</body>
	<script>
		$(document).ready( function(evt) {
			$('header').load("header_no_login.php");
		});

		$('#buttonValidateUser').click(function(evt) {
			evt.preventDefault();
			if ($('#userLoginForm').valid()) {
				var loginInfo = $('#userLoginForm').toObject();
				$.post('util_validate_user.php', loginInfo, function(data) {
					window.location.replace("view_sections.php");
				});
			} 
		});
		
		$('#buttonCreateUser').click(function(evt) {
			evt.preventDefault();
			console.log("here");
			document.location.replace("edit_user.php");
		});
	</script>
</html>