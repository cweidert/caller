<?php

require_once "util_database_info.php";
require_once "util_functions.php";
require_once "util_require_login.php";

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die ("Could not open database");

if (isset($_POST['id_section'])) {
	$id_section = sanitizeMySQL($conn, $_POST['id_section']);
	if (isOwnerSection($conn, $id_user, $id_section)) {
		deleteSection($conn, $id_section);	
	} else {
		echo "nice try, bozo";
	}
} 

$conn->close();
?>