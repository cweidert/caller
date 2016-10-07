<?php
require_once "util_require_login.php";
require_once "util_database_info.php";
require_once "util_functions.php";

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die ("Could not open database");

if (isset($_POST['studentLast']) && isset($_POST['studentFirst']) && isset($_POST['id_section'])) {
	$studentFirst = sanitizeMySQL($conn, $_POST['studentFirst']);
	$studentLast = sanitizeMySQL($conn, $_POST['studentLast']);
	$id_section = sanitizeMySQL($conn, $_POST['id_section']);
	$id_student = sanitizeMySQL($conn, $_POST['id_student']);

	if ($id_student == 0) {
		if (isOwnerSection($conn, $id_user, $id_section)) {
			insertStudent($conn, $studentFirst, $studentLast, $id_section);
		} else {
			echo "nice try, bozo";
		}
	} else {
		if (isOwnerStudent($conn, $id_user, $id_student)) {
			updateStudent($conn, $id_student, $studentFirst, $studentLast);
		} else {
			echo "nice try, bozo";
		}
	}
} 

$conn->close();
?>