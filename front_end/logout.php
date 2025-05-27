<?php 			
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	session_unset();
    $_SESSION = [];
	session_destroy();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Logging out…</title>
  <script>
    sessionStorage.clear();
    window.location.replace('index.php');
  </script>
</head>
<body>

</body>
</html>