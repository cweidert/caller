<?php
	require_once "util_require_login.php";
	require_once "util_database_info.php";
	require_once "util_functions.php";
	
	$conn = new mysqli($hn, $un, $pw, $db);
	if ($conn->connect_error) die ("Could not open database");
?>
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
		<link rel="manifest" href="/manifest.json">
		<script src="scripts/jquery.validate.js"></script>
		<script src="scripts/jquery-serialization.js"></script>
	</head>
	<body>
		<header>
		</header>
		<main>
			<div class="heading"><?php echo "$userFirst $userLast's Class List"; ?></div><br>
			<div id="sectionList"><?php echoSectionTable($conn, $id_user); ?></div>
			<div id="results"></div>
		</main>
		<footer>
			<a href="edit_section.php" class="button">Make a new class</a>
		</footer>
	</body>
	<script>
		$(document).ready(function () {
			$('header').load("header.php");
		});

		$('#sectionList').on('click', '.exportScores', function(evt) {
			evt.preventDefault();
			var id_section = $(evt.target).data().id_section; 
			$('<form action="export_scores.php" method="get">' + 
			'<input type="hidden" name="id_section" value="' + 
			id_section + '"></form>').submit();
		});
		
		$('#sectionList').on('click', '.editSection', function(evt) {
			evt.preventDefault();
			var id_section = $(evt.target).data().id_section; 
			$('<form action="edit_section.php" method="get">' + 
			'<input type="hidden" name="id_section" value="' + 
			id_section + '"></form>').submit();
		});
		
		$('#sectionList').on('click', '.viewSection', function(evt) { 					
			evt.preventDefault();
			var id_section = $(evt.target).data().id_section; 
			$('<form action="view_section.php" method="get">' + 
			'<input type="hidden" name="id_section" value="' + 
			id_section + '"></form>').submit();
		});
	</script>
</html>
<?php
	$conn->close();
?>