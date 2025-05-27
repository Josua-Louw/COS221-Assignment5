<?php 			
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	session_unset();
	session_destroy();
	window.location.replace('login.php');
	exit();
?>