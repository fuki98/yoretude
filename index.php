<?php
require __DIR__ . "/includes/header.php";

if(isset($_GET['v'])) {
	header("Location: watch.php?v=".$_GET['v'], true, 303);
	die();
}

require 'home.php';
?>
