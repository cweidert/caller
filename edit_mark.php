<?php
	require_once "util_require_login.php";
	require_once "util_database_info.php";
	require_once "util_functions.php";
	
	$conn = new mysqli($hn, $un, $pw, $db);
	if ($conn->connect_error) die ("Could not open database");

	if (isset($_GET['id_enrollment'])) {
		$id_enrollment = sanitizeMySQL($conn, $_GET['id_enrollment']);
		$id_section = getSection($conn, $id_enrollment);
		if (!isOwnerEnrollment($conn, $id_user, $id_enrollment)) {
			die ("no permission for that enrollment");
		}
	} else if (isset($_GET['id_section'])) {
		$id_section = sanitizeMySQL($conn, $_GET['id_section']);
		if (isOwnerSection($conn, $id_user, $id_section)) {
			if (getStudentsInSection($conn, $id_section) > 0){
				$id_enrollment = getRandomEnrollment($conn, $id_section);
			} else {
				header( "Location: view_section.php?id_section=$id_section" );
			}
		} else {
			die ("no permission for that section");
		}
	} else {
		die("no class or enrollment set");
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
			<div id="enrollmentInfo" class="heading"><?php echoEnrollmentHeading($conn, $id_enrollment); ?></div><br>
			<div id="scoreButtons">
			<table>
			<tr><td>
			<a href="#" class="button score" data-score="5">+</a>
			</td><td>
			<a href="#" class="button score" data-score="4">A</a>
			</td></tr><tr><td>
			<a href="#" class="button score" data-score="3">B</a>
			</td><td>
			<a href="#" class="button score" data-score="2">C</a>
			</td></tr><tr><td>
			<a href="#" class="button score" data-score="1">D<a>
			</td><td>
			<a href="#" class="button score" data-score="0">F</a>
			</td></tr>
			</table>
			</div>
			<div id="results"></div>
		</main>
		<footer>
			<a id="buttonSkip" href="#" class="button">Repick</a>
			<a id="buttonReturn" href="#" class="button">to Class</a>
			<a id="buttonSave" href="#" class="button" data-id_enrollment="<?php echo $id_enrollment; ?>">Save</a>
			<a id="buttonSaveAndRandom" href="#" class="button" data-id_enrollment="<?php echo $id_enrollment; ?>">Save & Repick</a>
		</footer>
		<form id="formReturnToSection" class="hide" action="view_section.php" method="get">
			<input type="hidden" name="id_section" value="<?php echo $id_section; ?>">
		</form>
		<form id="formGetRandomNewStudent" class="hide" action='edit_mark.php' method='get'>
			<input type='hidden' name='id_section' value='<?php echo $id_section; ?>'>
		</form>	
	</body>
	<script>
		var getScore = function() {
			var score = -1;
			$.each($('#scoreButtons').find('a'), function(ind, child) {
				if ($(child).hasClass("selected")) {
					score = $(child).data().score;
				}
			});
			return score;
		}
		
		$(document).ready(function () {
			$('header').load("header.php");
		});
		
		$('#scoreButtons').on('click', 'a', function(evt) {
			evt.preventDefault();
			$.each($(evt.target).closest('table').find('a'), function(index, sibling) {
				$(sibling).removeClass("selected");
			});
			$(evt.target).addClass("selected");
		});
		
		$('#buttonSkip').click(function(evt) {
			evt.preventDefault();
			var id_enrollment = $(evt.target).data().next_id_enrollment;
			$("#formGetRandomNewStudent").submit();
		});
		
		var insertMark = function(id_enrollment, score) {
			var mark = {};
			mark['id_enrollment'] = id_enrollment;
			mark['score'] = score;
			$.post('call_insertMark.php', mark);
		}
		
		$('#buttonSave').click(function(evt) {
			evt.preventDefault();
			if (getScore() >= 0) { 
				insertMark($(evt.target).data().id_enrollment, getScore());
				$.each($('#scoreButtons').find('.button'), function(index, button) {
					$(button).removeClass("selected");
				});
			}
		});

		$('#buttonSaveAndRandom').click( function(evt) {
			evt.preventDefault();
			if (getScore() >= 0) {
				insertMark($(evt.target).data().id_enrollment, getScore());
				$("#formGetRandomNewStudent").submit();
			}
		});
		
		$('#buttonReturn').click(function(evt) {
			evt.preventDefault();
			$('#formReturnToSection').submit();
		});
	</script>
</html>
<?php
	$conn->close();
?>