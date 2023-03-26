<?php
require __DIR__ . "/includes/header.php";
ob_get_clean();

// Make sure the user is logged in.
if(!isset($session)) {
	header("Location: login.php", true, 401);
	die();
}

// Add it to favorites!
$remove_from_favorites = $conn->prepare("DELETE FROM favorites WHERE member_id = :member_id AND video_id = :video_id");
$remove_from_favorites->execute([
	":member_id" => $session['member_id'],
	":video_id" => $_GET['video_id']
]);

header("Location: my_favorites.php");
?>