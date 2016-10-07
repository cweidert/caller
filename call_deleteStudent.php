<?php

require_once "util_database_info.php";
require_once "util_functions.php";
require_once "util_require_login.php";

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die ("Could not open database");

if (isset($_POST['id_student'])) {
	$id_student = sanitizeMySQL($conn, $_POST['id_student']);
	if (isOwnerStudent($conn, $id_user, $id_student)) {
		deleteStudent($conn, $id_student);	
	} else {
		echo "nice try, bozo";
	}
} 

$conn->close();

?>