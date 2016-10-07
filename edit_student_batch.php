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
		die("section not set");
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
			<form id="formSectionInfo" class="hide">
				<input type="hidden" name="id_section" value="<?php echo $id_section; ?>">
			</form>
			<div id="sectionInfo" class="heading">Batch Student Load for <?php echoSectionHeading($conn, $id_section); ?></div>
			<p>You can use this page to upload students in a batch format.  That way, instead of typing the students one by one, you can just copy and paste them from somewhere on your computer.  </p>
			<p>Your file must have on student on each line in the format "Last Name", "First Name".  For an example:</p>
			<div>
			<span class="info" style="text-align:left"><pre>
	Allen, Joe
	Brown, Daisy
	Cabrera, Maria
	Davis, Kaomi
	Enriquez, Jesse
			</pre></span>
			</div>
			<form id="formSaveBatch" method="post" action="save_student_batch.php" enctype='multipart/form-data'>
				<input type='file' name='toUpload' required="required"><br>
				<input type="hidden" name="id_section" value="<?php echo $id_section; ?>">
				<a href="#" id="buttonBatchImport" class="button">Upload Students!</a>
			</form>
			<div id="results"></div>
		</main>
	</body>
	<script>
		$(document).ready(function () {
			$('header').load("header.php");
		});

		$("#buttonBatchImport").click( function(evt) {
			evt.preventDefault();
			var fileInfo = $('#formSaveBatch').toObject();
			$("#formSaveBatch").submit();
		});
	</script>
</html>
<?php
	$conn->close();
?>