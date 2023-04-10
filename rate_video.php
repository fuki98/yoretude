<?php
require __DIR__ . "/includes/header.php";
ob_get_clean();

// Make sure the user is logged in.
if(!isset($session)) {
	header("Location: login.php", true, 401);
	die();
}

// Check if the video in question exists.
$video_exists = $conn->prepare("SELECT video_id FROM videos WHERE video_id = :video_id AND converted = 1");
$video_exists->execute([
	":video_id" => $_GET['video_id']
]);

if($video_exists->rowCount() == 0) {
	die();
}


// Add it to ratings!
$add_to_ratings = $conn->prepare("INSERT INTO star_rating (member_id, video_id, rating) VALUES (:member_id, :video_id, :rating)");
$add_to_ratings->execute([
	":member_id" => $session['member_id'],
	":video_id" => $_GET['video_id'],
  ":rating" => $_GET['am']
]);
header('Location: watch?v='.$_GET['video_id']);
	die();
?>
