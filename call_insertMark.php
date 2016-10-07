<?php
require_once "util_require_login.php";
require_once "util_database_info.php";
require_once "util_functions.php";

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die ("Could not open database");

if (isset($_POST['score']) && isset($_POST['id_enrollment'])) {
	$score = sanitizeMySQL($conn, $_POST['score']);
	$id_enrollment = sanitizeMySQL($conn, $_POST['id_enrollment']);
	
	if (isOwnerEnrollment($conn, $id_user, $id_enrollment)) {
		insertMark($conn, $id_enrollment, $score);
	} else {
		echo "nice try, bozo.";
	}

} 
$conn->close();
?>