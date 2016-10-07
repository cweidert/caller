<?php
require_once "util_database_info.php";
require_once "util_functions.php";

	
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die ("Could not open database");

if (isset($_POST['username'])) {
	$username = sanitizeMySQL($conn, $_POST['username']);
	if (isUserExists($conn, $username)) {
		echo "<span class='notCool'>username '$username' taken</span>";
	} else {
		echo "<span class='cool'>username '$username' available</span>";
	}
} else {
	echo "no username chosen";
}

$conn->close();
?>