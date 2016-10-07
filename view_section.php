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
		die("no section selected");
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
			<div id="sectionInfo" class="heading"><?php echoSectionHeading($conn, $id_section); ?></div>
			<div id="studentTable"><?php echoStudentTable($conn, $id_section); ?></div>
		</main>
		<footer>
			<nav>
			<a id="buttonCallRandomStudent" href="#" class="button" data-id_enrollment="<?php echo getRandomEnrollment($conn, $id_section); ?>">Pick Random!</a>
			<a id="buttonAddStudent" href="#" class="button">Add Student</a>
			<a id="buttonAddStudentsBatch" href="#" class="button">Import Students</a>
			</nav>
		</footer>
		<form id="formAddStudent" class="hide" action="edit_student.php" method="get"><input type="hidden" name="id_section" value="<?php echo $id_section; ?>"></form>
		<form id="formCallRandom" class="hide" action="edit_mark.php" method="get"><input type="hidden" name="id_section" value="<?php echo $id_section; ?>"></form>
		<form id="formAddBatch" class="hide" action="edit_student_batch.php" method="get"><input type="hidden" name="id_section" value="<?php echo $id_section; ?>"></form>
		
	</body>
	<script>
		getSectionId = function() {
			return $('#formSectionInfo').toObject()['id_section'];
		}
	
		$(document).ready(function () {
			$('header').load("header.php");
		});
		
		$("#buttonAddStudent").click( function(evt) {
			evt.preventDefault();
			$('#formAddStudent').submit();
		});

		$("#buttonAddStudentsBatch").click( function(evt) {
			evt.preventDefault();
			$('#formAddBatch').submit();
		});

		$("#buttonCallRandomStudent").click( function(evt) {
			evt.preventDefault();
			$('#formCallRandom').submit();
		});
		
		$("#studentTable").on('click', '.callStudent', function(evt) {
			evt.preventDefault();
			var id_enrollment = $(evt.target).data().id_enrollment;
			$('<form action="edit_mark.php" method="get"><input type="hidden" name="id_enrollment" value="' + 
			id_enrollment + '"></form>').submit();
		});

		$("#studentTable").on('click', '.editStudent', function(evt) {
			evt.preventDefault();
			var id_student = $(evt.target).data().id_student;
			var id_section = $(evt.target).data().id_section;
			$('<form action="edit_student.php" method="get"><input type="hidden" name="id_student" value="' + 
			id_student + '"><input type="hidden" name="id_section" value="' + id_section + '"></form>').submit();
		});
		</script>
</html>
<?php
	$conn->close();
?>