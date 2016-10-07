<?php
require_once "util_database_info.php";
require_once "util_require_login.php";
require_once "util_functions.php";

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die ("Could not open database");

if (isset($_POST['id_section']) && $_FILES) {
	$id_section = sanitizeMYSQL($conn, $_POST['id_section']);
	
	if (isOwnerSection($conn, $id_user, $id_section)) {
		$fileName = sanitizeString($_FILES['toUpload']['name']);
		$tempFileName = "students.txt";
		move_uploaded_file($_FILES['toUpload']['tmp_name'], $tempFileName);


		echo "Uploaded File: '$fileName'<br>";
		$fh = fopen($tempFileName, "r") or die("Could not read student file.");

		while (($line = fgets($fh)) != false) {
			$line = trim($line);
			$names = explode(", ", $line);
			if (count($names) != 2) {
				echo "could not handle line: '$line'";
			} else {
				$last = trim(sanitizeMySQL($conn, $names[0]));
				$first = trim(sanitizeMySQL($conn, $names[1]));
				
				insertStudent($conn, $first, $last, $id_section);
			}
			echo "<br>";
		}
		
		fclose($fh);
	} else {
		echo "nice try, bozo";
	}
} else {
	echo "didn't see the id_section and/or file";
}
echo "<a class='button' href='view_section.php?id_section=$id_section'>Click Here to Return to your Class </a>";

$conn->close();

?>
