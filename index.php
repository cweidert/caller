<!DOCTYPE html>
<link rel="import" href="head.html">
<html>
	<head>
		<meta charset="utf-8"/>
		<meta name="mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="mobile-web-app-capable" content="yes">
		<link rel="manifest" href="/manifest.json">
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
			<p class="justify">This is a tool I made in my free time to let teachers call on students randomly and mark their responses.  For me it's mostly for fun, but maybe it'll be useful for you.  You are welcome to it, but I can't guarantee it'll work perfectly or be around forever.</p>
			
			<p class="justify">If you notice things that should be fixed or have questions or comments, you could try e-mailing me, Craig Weidert, at <a href="mailto:heliomug.caller@gmail.com">heliomug.caller@gmail.com</a>, but no promises.  </p>

			<a class='button' href="view_sections.php">Cool.  Let me try this thing.  </a>
		</main>
		<footer>
		</footer>
	</body>
	<script>
		$(document).ready(function() {
			$('header').load('header_no_login.php');
		});
	</script>
</html>
