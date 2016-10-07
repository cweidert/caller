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
		if (isset($_GET['id_student'])) {
			$id_student = $_GET['id_student'];
			if (isOwnerStudent($conn, $id_user, $id_student)) {
				$id_student = sanitizeMySQL($conn, $_GET['id_student']);
			} else {
				die ("no permission for student");
			}
		} else {
			$id_student = 0;
		}
	} else {
		die("trying to add student without id_section set");
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title>Student Caller</title>
		<meta name="mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-capable" content="yes">
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
			<?php 
				if ($id_student == 0) {
					echo "<div class='heading'>Create a new student</div><br>";
				} else {
					echo "<div class='heading'>Student Information</div><br>";
				}
			?>
			<form id="formStudentInfo">	
				<div>First Name:</div>
				<input type="text" name="studentFirst" value="<?php echo getStudentField($conn, $id_student, "first"); ?>"><br>
				<div>Last Name:</div>
				<input type="text" name="studentLast" value="<?php echo getStudentField($conn, $id_student, "last"); ?>"><br>
				<input type='hidden' name='id_student' value='<?php echo $id_student;?>'>
				<input type='hidden' name='id_section' value='<?php echo $id_section;?>'>
				<?php 
					if ($id_student == 0) {
						echo "<a id='buttonSaveStudent' class='button' href='#'>Create Student!</a>";
					} else {
						echo "<a id='buttonSaveStudent' class='button' href='#'>Save Edits</a>";
						echo "<a id='buttonClearScores' class='button' href='#' data-id_student='$id_student' data-id_section='$id_section'>Clear All Scores</a>";
					}
				?>
			</form>
			<div id="results">
			</div>
		</main>
		<footer>
		<a id="buttonReturnToSection" class="button" href="#">Return to Class</a>
		<?php 
			if ($id_student != 0) {
				echo "<a id='buttonDeleteStudent' class='button' href='#' data-id_student='$id_student'>Remove Student!</a>";
			}
		?>
		</footer>
		<form id="formReturnToSection" class="hide" action="view_section.php" method="get">
			<input type='hidden' name='id_section' value='<?php echo $id_section;?>'>
		</form>
	</body>
	<script>
		$('#buttonSaveStudent').click(function(evt) {
			evt.preventDefault();
			if ($('#formStudentInfo').valid()) {
				var student = $('#formStudentInfo').toObject();
				$.post('call_saveStudent.php', student, function(data) {
					$('#formReturnToSection').submit();
				});
			} 
		});

		$('#buttonClearScores').click(function(evt) {
			evt.preventDefault();
			var sure = confirm("Are you sure you want to clear all scores for this student?  This cannot be undone!");
			if (sure) {
				var enrollmentInfo = {};
				enrollmentInfo['id_student'] = $(evt.target).data().id_student;
				enrollmentInfo['id_section'] = $(evt.target).data().id_section;
				$.post('call_deleteScores.php', enrollmentInfo, function(data) {
					//$('#results').html(data);
					$('#formReturnToSection').submit();
				});
			}
		});
		
		$('#buttonDeleteStudent').click(function(evt) {
			evt.preventDefault();
			var sure = confirm("Are you sure you want to delete this student?");
			if (sure) {
				var studentInfo = {};
				studentInfo['id_student'] = $(evt.target).data().id_student;
				console.log(studentInfo);
				$.post('call_deleteStudent.php', studentInfo, function(data) {
					$('#formReturnToSection').submit();
				});
			}
		});
		
		$('#buttonReturnToSection').click(function(evt) {
			evt.preventDefault();
			$('#formReturnToSection').submit();
		});
		
		$('document').ready(function () {
			$('header').load("header.php");
		});
	</script>
</html>
<?php 
	$conn->close();
?>