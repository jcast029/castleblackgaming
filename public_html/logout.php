<?php
	session_start();
	session_destroy();
	
	// unset cookies
	setcookie("id", "", time()-3600);
	header("Location: http://www.castleblackgaming.com/index.php");
?>
