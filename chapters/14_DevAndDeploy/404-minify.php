<?php
if($_SERVER['REDIRECT_URL']=='/minify.css') {
	header("HTTP/1.0 200 OK");
	require_once "minify.php";
	exit;
}
?>
