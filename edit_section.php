<?php
	require_once "util_require_login.php";
	require_once "util_functions.php";
	require_once "util_database_info.php";
	
	$conn = new mysqli($hn, $un, $pw, $db);
	if ($conn->connect_error) die ("Could not open database");
	
	if (isset($_GET['id_section'])) {
		$id_section = $_GET['id_section'];
		if (!isOwnerSection($conn, $id_user, $id_section)) {
			die ("no permission for that section");
		}
	} else {
		$id_section = 0;
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
		<div class="heading"><?php if ($id_section == 0) echo "Create a New Class"; else echo "Edit Class"; ?></div>
		<form id="formSectionInfo">	
			<input type="hidden" name="id_section" value="<?php echo $id_section; ?>">
			<div>Title:</div>
			<input type="text" name="title" value="<?php echo getSectionField($conn, $id_section, "title"); ?>"><br>
			<div>Abbreviation:</div>
			<input type="text" name="abbrev" value="<?php echo getSectionField($conn, $id_section, "abbrev"); ?>"><br>
			<div>Period:</div>
			<select name="period">
				<?php
					$per = -1;
					if ($id_section != 0) {
						$per = getSectionField($conn, $id_section, "period");
					}
					for ($i = 0 ; $i < 9 ; $i++) {
						if ($i == $per) {
							echo "<option value='$i' selected='selected'>$i</option>"; 
						} else {
							echo "<option value='$i'>$i</option>"; 
						}
					}
				?>
			</select><br>
			<div>Subject:</div>
			<input type="text" name="subject" value="<?php echo getSectionField($conn, $id_section, "subject"); ?>"><br>
			<?php 
				if ($id_section == 0) {
					echo "<a id='buttonSaveSection' class='button' href='#'>Create a New Class!</a>";
				} else {
					echo "<a id='buttonSaveSection' class='button' href='#'>Save Edits</a>";
					echo "<a id='buttonDeleteAllScores' class='button' href='#' data-id_section='$id_section'>Delete All Scores</a>";
				}
			?>
		</form>		
		<div id="results">
		</div>
		</main>
		<footer>
			<?php 
				if ($id_section != 0) {
					echo "<a id='buttonExport' href='#' class='button edit' data-id_section='$id_section'>Export Scores</a>";
					echo "<a id='buttonDeleteSection' href='#' class='button' data-id_section='$id_section'>Delete Class!</a>";
				}
			?>
		</footer>
		<?php
			if ($id_section != 0) {
				echo "<form id='formExportScores' class='hide' action='view_scores.php' method='get'>";
				echo "<input type='hidden' name='id_section' value='$id_section'>";
				echo "</form>";
			}
		?>
	</body>
	<script>
		$('#buttonSaveSection').click(function(evt) {
			evt.preventDefault();
			if ($('#formSectionInfo').valid()) {
				var section = $('#formSectionInfo').toObject();
				$.post('call_saveSection.php', section, function(data) {
					window.location.replace("view_sections.php");
				});
			} else {
				$('#results').html("fix the form");
			}
		});

		$('#buttonExport').click( function(evt) {
			evt.preventDefault();
			$('#formExportScores').submit();
		});
		
		$('#buttonDeleteAllScores').click(function(evt) {
			evt.preventDefault();
			var sure = confirm("Are you sure you want to delete all scores from this class?  This cannot be undone!");
			if (sure) {
				var sectionInfo = {};
				sectionInfo['id_section'] = $(evt.target).data().id_section;
				$.post('call_deleteAllScores.php', sectionInfo, function(data) {
					//$('#results').html(data);
					window.location.replace("view_sections.php");
				});
			}
		});
		
		$('#buttonDeleteSection').click(function(evt) {
			evt.preventDefault();
			var sure = confirm("Are you sure you want to delete this class?");
			if (sure) {
				var sectionInfo = {};
				sectionInfo['id_section'] = $(evt.target).data().id_section;
				$.post('call_deleteSection.php', sectionInfo, function(data) {
					//$('#results').html(data);
					window.location.replace("view_sections.php");
				});
			}
		});
		

		
		$('document').ready(function () {
			$('header').load("header.php");
		});
	</script>
</html>
<?php
	$conn->close();
?>