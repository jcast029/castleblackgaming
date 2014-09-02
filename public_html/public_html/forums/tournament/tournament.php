<?php
	session_start();
	$username="cbdb";
	$password="CastleBlack111!";
	$host="localhost";
	$dbname="castleblackgaming";
	$con=mysqli_connect($host, $username, $password, $dbname);
	if(mysqli_connect_errno()){
		$aResult['error'] = "Error!";
	}
	$query = "SELECT * FROM tournament ORDER BY id DESC";
	$result = mysqli_query($con, $query);
	$row = mysqli_fetch_array($result);
	$cur_tournament = $row['tid'];
	
	$url = 'http://binarybeast.com/' . $cur_tournament . '/full';
	$content = file_get_contents($url);
	$content = str_replace("../../", "http://www.binarybeast.com/", $content);
	
	$left = strpos($content, "href=", 0);
	$left = strpos($content, "\"", $left);
	$left++;
	$right = strpos($content, "\"", $left);
	$stylesheet = substr($content, $left, $right - $left);
	$stylesheetContent = file_get_contents($stylesheet);
	$stylesheetContent = str_replace("url(/img/bg.jpg) top center #10110E", "transparent", $stylesheetContent);
	$stylesheetContent = str_replace("#1A1A1A!important", "rgba(84, 84, 84, 0.2)", $stylesheetContent);
	$stylesheetContent = str_replace("text-align:left", "text-align:center", $stylesheetContent);
	file_put_contents("style2.css", $stylesheetContent);
	$content = str_replace($stylesheet, "style2.css", $content);
	
	$left = strpos($content, "http://www.binarybeast.com/content/tourney/tasks/load/js/index.js", 0);
	$left = strpos($content, "href=", $left);
	$left = strpos($content, "\"", $left);
	$left++;
	$right = strpos($content, "\"", $left);
	$stylesheet = substr($content, $left, $right - $left);
	$stylesheetContent = file_get_contents($stylesheet);
	$stylesheetContent = str_replace("rgba(0,136,221", "rgba(47, 115, 33", $stylesheetContent);
	$stylesheetContent = str_replace("rgba(0,170,255", "rgba(47, 115, 33", $stylesheetContent);
	$stylesheetContent = str_replace("url(/img/dreamhack/groups/bg.jpg)", "transparent", $stylesheetContent);
	$stylesheetContent = str_replace("1A1A1A", "602D80", $stylesheetContent);
	$stylesheetContent = str_replace("#0AF", "#4EF", $stylesheetContent);
	
	file_put_contents("style.css", $stylesheetContent);
	$content = str_replace($stylesheet, "style.css", $content);
	$content = str_replace("<img src=\"http://www.binarybeast.com/img/bb_logo_embed.png\" alt=\"Logo\"/>", "Binary Beast", $content);
	echo $content;
?>