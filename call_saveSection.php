<?php
require_once "util_require_login.php";
require_once "util_database_info.php";
require_once "util_functions.php";

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die ("Could not open database");

if (isset($_POST['title']) && isset($_POST['abbrev']) && isset($_POST['period']) && isset($_POST['subject']) && isset($_POST['id_section'])) {

	$title = sanitizeMySQL($conn, $_POST['title']);
	$abbrev =  sanitizeMySQL($conn, $_POST['abbrev']);
	$period = sanitizeMySQL($conn, $_POST['period']);
	$subject = sanitizeMySQL($conn, $_POST['subject']);
	$id_section = sanitizeMySQL($conn, $_POST['id_section']);
	
	if ($id_section == 0) {
		insertSection($conn, $id_user, $title, $abbrev, $period, $subject);
	} else {
		if (isOwnerSection($conn, $id_user, $id_section)) {
			updateSection($conn, $id_section, $title, $abbrev, $period, $subject);
		} else {
			echo "nice try, bozo";
		}
	}
} 

$conn->close();
?>