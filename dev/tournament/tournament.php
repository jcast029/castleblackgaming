<?php
	session_start();
	$url = 'http://binarybeast.com/xHotS1408191/full';
	$content = file_get_contents($url);
	$content = str_replace("../../", "http://www.binarybeast.com/", $content);
	$left = strpos($content, "http://www.binarybeast.com/content/tourney/tasks/load/js/index.js", 0);
	$left = strpos($content, "href=", $left);
	$left = strpos($content, "\"", $left);
	$left++;
	$right = strpos($content, "\"", $left);
	$stylesheet = substr($content, $left, $right - $left);
	$stylesheetContent = file_get_contents($stylesheet);
	$stylesheetContent = str_replace("rgba(0,136,221,0.30)", "rgba(193, 44, 44, 0.30)", $stylesheetContent);
	$stylesheetContent = str_replace("rgba(0,136,221,0.60)", "rgba(193, 44, 44, 0.60)", $stylesheetContent);
	$stylesheetContent = str_replace("rgba(0,136,221,0.07)", "rgba(193, 44, 44, 0.07)", $stylesheetContent);
	
	file_put_contents("style.css", $stylesheetContent);
	$content = str_replace($stylesheet, "style.css", $content);
	echo $content;
?>