<?php
require_once "util_database_info.php";
require_once "util_functions.php";

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die ("Could not open database");

if (isset($_POST['username']) && isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['email']) && isset($_POST['password'])) {

	
	
	$first = sanitizeMySQL($conn, $_POST['firstname']);
	$last =  sanitizeMySQL($conn, $_POST['lastname']);
	$email = sanitizeMySQL($conn, $_POST['email']);
	$username = sanitizeMySQL($conn, $_POST['username']);
	$password = sanitizeMySQL($conn, $_POST['password']);
	$token = tokenize($password);

	insertUser($conn, $first, $last, $email, $username, $token);
	
} 

$conn->close();
?>