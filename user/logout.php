<!--logout.php-->
<?php
	#start the session
	session_start();
	#destroy the session
	session_destroy();
	#redirect the user to index.php
	header('location: ../index.php')
?>