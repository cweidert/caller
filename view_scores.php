<?php
	require_once "util_require_login.php";
	require_once "util_database_info.php";
	require_once "util_functions.php";
	
	$conn = new mysqli($hn, $un, $pw, $db);
	if ($conn->connect_error) die ("Could not open database");
	
	if (isset($_GET['id_section'])) {
		$id_section = sanitizeMySQL($conn, $_GET['id_section']);
		if (!isOwnerSection($conn, $id_user, $id_section)) {
			die ("no permission for that section");
		}
	} else {
		die ("section not set");
	}
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
		<script src="scripts/jquery.validate.js"></script>
		<script src="scripts/jquery-serialization.js"></script>
	</head>
	<body>
		<header>
		</header>
		<main>
			<form id="formSectionInfo" class="hide"><input type="hidden" name="id_section" value="<?php echo $id_section; ?>"></form>
			<div class="heading">Scores for <?php echoSectionHeading($conn, $id_section); ?></div>
			<div>
				You can copy the text below and paste it into any spreadsheet program.
			</div>
			<div class='plain'><pre><?php echoSectionScores($conn, $id_section);?></pre></div>
		</main>
		<footer>
			<a id="buttonReturnToSection" class="button" href="#">Return to Class</a>
		</footer>
		<form id="formReturnToSection" class="hide" action="edit_section.php" method="get">
			<input type='hidden' name='id_section' value='<?php echo $id_section;?>'>
		</form>
	</body>
	<script>
		$('document').ready(function () {
			$('header').load("header.php");
		});

		$('#buttonReturnToSection').click(function(evt) {
			evt.preventDefault();
			$('#formReturnToSection').submit();
		});
	</script>
</html>
<?php
	$conn->close();
?>