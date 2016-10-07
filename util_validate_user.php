<?php
require_once "util_database_info.php";
require_once "util_functions.php";

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die ("Could not open database");

$un_temp = sanitizeMySQL($conn, $_POST['username']);
$pw_temp = sanitizeMySQL($conn, $_POST['password']);

validateUser($conn, $un_temp, $pw_temp);

$conn->close();
?>