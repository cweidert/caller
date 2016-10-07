<?php

require_once "util_database_info.php";
require_once "util_functions.php";
require_once "util_require_login.php";

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die ("Could not open database");

echo $_POST['id_section'];

if (isset($_POST['id_section']) && isset($_POST['id_student'])) {
	$id_section = sanitizeMySQL($conn, $_POST['id_section']);
	$id_student = sanitizeMySQL($conn, $_POST['id_student']);
	if (isOwnerSection($conn, $id_user, $id_section)) {
		deleteScores($conn, $id_student, $id_section);	
	} else {
		echo "no permissions to delete scores for that student";
	}
} 

$conn->close();
?>