<?php
	session_start();
	
	if (isset($_SESSION['id_user']) && isset($_SESSION['username']) && isset($_SESSION['userLast']) && isset($_SESSION['userFirst'])) {
		$id_user = $_SESSION['id_user'];
		$username = $_SESSION['username'];
		$userFirst = $_SESSION['userFirst'];
		$userLast = $_SESSION['userLast'];
	} else {
		header( 'Location: util_login.php' );
	}
?>
