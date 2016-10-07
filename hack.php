<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title>Student Caller Hacker</title>
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
			<span>Hacker Thing</span>
			<div id="results"></div>
		</main>
		<footer>
		</footer>
	</body>
	<script>
	var sectionInfo = {};
	sectionInfo['id_section'] = 27;
	$.post('call_deleteSection.php', sectionInfo, function(data) {
		$('#results').html(data);
	});
	</script>
</html>

